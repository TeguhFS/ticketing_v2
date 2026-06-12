<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.ticketType.event', 'payment'])
            ->latest();

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%'));
            });
        }

        // Filter tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(15)->withQueryString();

        // Stats
        $stats = [
            'total'     => Order::count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'paid'      => Order::where('status', 'paid')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'refunded' => Order::where('status', 'refunded')->count(),
            'expired' => Order::where('status', 'expired')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'orderItems.ticketType.event',
            'orderItems.tickets',
            'payment.paymentMethod',
            'payment.verifiedBy',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled,refunded,expired',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status order berhasil diperbarui.');
    }

    public function export(Request $request)
    {
        // Jika tidak ada parameter, tampilkan halaman konfirmasi
        if (!$request->hasAny(['status', 'date_from', 'date_to', 'download'])) {
            return view('admin.orders.export');
        }

        $filename = 'orders-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(
            new OrdersExport(
                $request->status,
                $request->date_from,
                $request->date_to,
            ),
            $filename
        );
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order berhasil dihapus.');
    }
}
