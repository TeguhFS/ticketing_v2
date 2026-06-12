<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpireOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function handle(): void
    {
        DB::beginTransaction();

        try {
            // Ambil semua order pending yang sudah melewati expired_at
            $expiredOrders = Order::where('status', 'pending')
                ->where('expired_at', '<=', now())
                ->whereNotNull('expired_at')
                ->with(['orderItems.ticketType', 'payment'])
                ->get();

            $count = 0;

            foreach ($expiredOrders as $order) {
                // Update status order ke expired
                $order->update([
                    'status' => 'expired',
                    'notes'  => 'Pesanan otomatis expired pada ' . now()->format('d M Y H:i') .
                        ' karena pembayaran tidak diselesaikan dalam waktu 24 jam.',
                ]);

                // Update payment ke expired jika ada
                if ($order->payment && $order->payment->status === 'pending') {
                    $order->payment->update(['status' => 'expired']);
                }

                // Kembalikan kuota tiket
                foreach ($order->orderItems as $item) {
                    $item->ticketType->update([
                        'sold' => max(0, $item->ticketType->sold - $item->quantity)
                    ]);
                }

                $count++;

                Log::info('Order expired', [
                    'order_id'     => $order->id,
                    'order_number' => $order->order_number,
                    'expired_at'   => $order->expired_at,
                ]);
            }

            DB::commit();

            Log::info("ExpireOrdersJob: {$count} order(s) expired successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ExpireOrdersJob failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
