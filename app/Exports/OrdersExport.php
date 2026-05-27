<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class OrdersExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths,
    WithTitle
{
    protected $status;
    protected $dateFrom;
    protected $dateTo;

    public function __construct(?string $status, ?string $dateFrom, ?string $dateTo)
    {
        $this->status   = $status;
        $this->dateFrom = $dateFrom;
        $this->dateTo   = $dateTo;
    }

    public function query()
    {
        $query = Order::with(['user', 'orderItems.ticketType.event', 'payment'])->latest();

        if ($this->status) {
            $query->where('status', $this->status);
        }
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'No',
            'Order Number',
            'Nama User',
            'Email User',
            'Event',
            'Subtotal',
            'Diskon',
            'Pajak',
            'Total',
            'Status',
            'Tanggal Order',
        ];
    }

    public function map($order): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $order->order_number,
            $order->user->name ?? '-',
            $order->user->email ?? '-',
            $order->orderItems->first()?->ticketType?->event?->title ?? '-',
            'Rp ' . number_format($order->subtotal, 0, ',', '.'),
            'Rp ' . number_format($order->discount, 0, ',', '.'),
            'Rp ' . number_format($order->tax, 0, ',', '.'),
            'Rp ' . number_format($order->total, 0, ',', '.'),
            ucfirst($order->status),
            $order->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold'  => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size'  => 11,
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF111827'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 22,
            'C' => 25,
            'D' => 30,
            'E' => 35,
            'F' => 18,
            'G' => 18,
            'H' => 18,
            'I' => 18,
            'J' => 14,
            'K' => 20,
        ];
    }

    public function title(): string
    {
        return 'Data Orders';
    }
}
