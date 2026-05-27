<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with([
            'order.user',
            'order.orderItems.ticketType.event',
            'paymentMethod',
            'verifiedBy',
        ])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('payment_code', 'like', '%' . $request->search . '%')
                    ->orWhereHas(
                        'order.user',
                        fn($u) =>
                        $u->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('email', 'like', '%' . $request->search . '%')
                    );
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->paginate(15)->withQueryString();

        $stats = [
            'pending'  => Payment::where('status', 'pending')->count(),
            'verified' => Payment::where('status', 'verified')->count(),
            'rejected' => Payment::where('status', 'rejected')->count(),
            'expired'  => Payment::where('status', 'expired')->count(),
        ];

        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('admin.payments.index', compact('payments', 'stats', 'paymentMethods'));
    }

    public function show(Payment $payment)
    {
        $payment->load([
            'order.user',
            'order.orderItems.ticketType.event',
            'order.orderItems.tickets',
            'paymentMethod.bankAccounts',
            'verifiedBy',
        ]);

        return view('admin.payments.show', compact('payment'));
    }

    public function verify(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran ini tidak dapat diverifikasi.');
        }

        $payment->update([
            'status'      => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'notes'       => $request->notes,
        ]);

        // Update order status ke paid
        $payment->order->update(['status' => 'paid']);

        return back()->with('success', 'Pembayaran berhasil diverifikasi!');
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'notes' => 'required|string|max:255',
        ]);

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran ini tidak dapat ditolak.');
        }

        $payment->update([
            'status' => 'rejected',
            'notes'  => $request->notes,
        ]);

        // Kembalikan order ke pending
        $payment->order->update(['status' => 'pending']);

        return back()->with('success', 'Pembayaran berhasil ditolak.');
    }

    // ─── Payment Methods ──────────────────────────────────────────

    public function methods()
    {
        $methods = PaymentMethod::withCount('payments')->with('bankAccounts')->get();
        return view('admin.payments.methods', compact('methods'));
    }

    public function storeMethod(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'code'        => 'required|string|max:50|unique:payment_methods,code',
            'type'        => 'required|in:bank_transfer,e_wallet,qris,credit_card,other',
            'fee'         => 'nullable|numeric|min:0',
            'fee_percent' => 'nullable|numeric|min:0|max:100',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'is_active'   => 'boolean',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('payment-methods', 'public');
        }

        PaymentMethod::create([
            'name'        => $request->name,
            'code'        => Str::upper($request->code),
            'type'        => $request->type,
            'fee'         => $request->fee ?? 0,
            'fee_percent' => $request->fee_percent ?? 0,
            'logo'        => $logoPath,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.payments.methods')
            ->with('success', 'Metode pembayaran berhasil ditambahkan!');
    }

    public function updateMethod(Request $request, PaymentMethod $method)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'code'        => 'required|string|max:50|unique:payment_methods,code,' . $method->id,
            'type'        => 'required|in:bank_transfer,e_wallet,qris,credit_card,other',
            'fee'         => 'nullable|numeric|min:0',
            'fee_percent' => 'nullable|numeric|min:0|max:100',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'is_active'   => 'boolean',
        ]);

        $logoPath = $method->logo;
        if ($request->hasFile('logo')) {
            if ($method->logo) Storage::disk('public')->delete($method->logo);
            $logoPath = $request->file('logo')->store('payment-methods', 'public');
        }

        $method->update([
            'name'        => $request->name,
            'code'        => Str::upper($request->code),
            'type'        => $request->type,
            'fee'         => $request->fee ?? 0,
            'fee_percent' => $request->fee_percent ?? 0,
            'logo'        => $logoPath,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.payments.methods')
            ->with('success', 'Metode pembayaran berhasil diperbarui!');
    }

    public function destroyMethod(PaymentMethod $method)
    {
        if ($method->logo) Storage::disk('public')->delete($method->logo);
        $method->delete();

        return redirect()->route('admin.payments.methods')
            ->with('success', 'Metode pembayaran berhasil dihapus.');
    }

    // ─── Bank Accounts ────────────────────────────────────────────

    public function storeBankAccount(Request $request, PaymentMethod $method)
    {
        $request->validate([
            'bank_name'      => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_name'   => 'required|string|max:100',
            'logo'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('bank-accounts', 'public');
        }

        $method->bankAccounts()->create([
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name'   => $request->account_name,
            'logo'           => $logoPath,
            'is_active'      => true,
        ]);

        return redirect()->route('admin.payments.methods')
            ->with('success', 'Rekening bank berhasil ditambahkan!');
    }

    public function destroyBankAccount(BankAccount $bankAccount)
    {
        if ($bankAccount->logo) Storage::disk('public')->delete($bankAccount->logo);
        $bankAccount->delete();

        return redirect()->route('admin.payments.methods')
            ->with('success', 'Rekening bank berhasil dihapus.');
    }
}
