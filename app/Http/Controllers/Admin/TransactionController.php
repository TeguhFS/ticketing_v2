<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $query  = Payment::with([
            'order.user',
            'order.orderItems.ticketType.event',
            'paymentMethod',
            'verifiedBy',
        ])->where('status', 'verified');

        // Filter pencarian
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

        // Filter metode pembayaran
        if ($request->filled('payment_method_id')) {
            $query->where('payment_method_id', $request->payment_method_id);
        }

        // Filter tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('verified_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('verified_at', '<=', $request->date_to);
        }

        $transactions = $query->latest('verified_at')->paginate(15)->withQueryString();

        // Summary stats
        $totalRevenue    = Payment::where('status', 'verified')->sum('total_paid');
        $totalToday      = Payment::where('status', 'verified')->whereDate('verified_at', today())->sum('total_paid');
        $totalThisWeek   = Payment::where('status', 'verified')->whereBetween('verified_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_paid');
        $totalThisMonth  = Payment::where('status', 'verified')->whereMonth('verified_at', now()->month)->whereYear('verified_at', now()->year)->sum('total_paid');
        $totalCount      = Payment::where('status', 'verified')->count();

        // Chart data berdasarkan period
        $chartData = $this->getChartData($period);

        // Payment methods
        $paymentMethods = \App\Models\PaymentMethod::where('is_active', true)->get();

        return view('admin.transactions.index', compact(
            'transactions',
            'totalRevenue',
            'totalToday',
            'totalThisWeek',
            'totalThisMonth',
            'totalCount',
            'chartData',
            'period',
            'paymentMethods',
        ));
    }

    public function show(Payment $transaction)
    {
        $transaction->load([
            'order.user',
            'order.orderItems.ticketType.event',
            'order.orderItems.tickets',
            'paymentMethod',
            'verifiedBy',
        ]);

        return view('admin.transactions.show', compact('transaction'));
    }

    public function export(Request $request)
    {
        if (!$request->hasAny(['date_from', 'date_to', 'payment_method_id', 'download'])) {
            $paymentMethods = \App\Models\PaymentMethod::where('is_active', true)->get();
            return view('admin.transactions.export', compact('paymentMethods'));
        }

        $filename = 'transactions-' . now()->format('Ymd-His') . '.xlsx';
        return Excel::download(
            new TransactionsExport(
                $request->date_from,
                $request->date_to,
                $request->payment_method_id,
            ),
            $filename
        );
    }

    private function getChartData(string $period): array
    {
        $labels = [];
        $data   = [];

        if ($period === 'daily') {
            // 7 hari terakhir
            for ($i = 6; $i >= 0; $i--) {
                $date     = now()->subDays($i);
                $labels[] = $date->format('d M');
                $data[]   = Payment::where('status', 'verified')
                    ->whereDate('verified_at', $date)
                    ->sum('total_paid');
            }
        } elseif ($period === 'weekly') {
            // 8 minggu terakhir
            for ($i = 7; $i >= 0; $i--) {
                $start    = now()->subWeeks($i)->startOfWeek();
                $end      = now()->subWeeks($i)->endOfWeek();
                $labels[] = 'W' . $start->weekOfYear . ' ' . $start->format('M');
                $data[]   = Payment::where('status', 'verified')
                    ->whereBetween('verified_at', [$start, $end])
                    ->sum('total_paid');
            }
        } else {
            // 6 bulan terakhir (default monthly)
            for ($i = 5; $i >= 0; $i--) {
                $month    = now()->subMonths($i);
                $labels[] = $month->translatedFormat('M Y');
                $data[]   = Payment::where('status', 'verified')
                    ->whereMonth('verified_at', $month->month)
                    ->whereYear('verified_at', $month->year)
                    ->sum('total_paid');
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }
}
