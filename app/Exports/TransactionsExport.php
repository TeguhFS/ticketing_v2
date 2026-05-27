<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TransactionsExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths,
    WithTitle,
    WithEvents
{
    protected $dateFrom;
    protected $dateTo;
    protected $paymentMethodId;
    protected $totalPaidAccumulator = 0; // Pembeda nama agar lebih bersih
    protected $no    = 0;

    public function __construct(?string $dateFrom, ?string $dateTo, ?int $paymentMethodId)
    {
        $this->dateFrom        = $dateFrom;
        $this->dateTo          = $dateTo;
        $this->paymentMethodId = $paymentMethodId;
    }

    public function query()
    {
        $query = Payment::with([
            'order.user',
            'order.orderItems.ticketType.event',
            'paymentMethod',
            'verifiedBy',
        ])->where('status', 'verified');

        if ($this->dateFrom) {
            $query->whereDate('verified_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('verified_at', '<=', $this->dateTo);
        }
        if ($this->paymentMethodId) {
            $query->where('payment_method_id', $this->paymentMethodId);
        }

        return $query->latest('verified_at');
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Pembayaran',
            'Nama User',
            'Email User',
            'Event',
            'Metode Pembayaran',
            'Jumlah',
            'Biaya Admin',
            'Total Dibayar',
            'Diverifikasi Oleh',
            'Tanggal Verifikasi',
        ];
    }

    public function map($payment): array
    {
        $this->no++;
        $this->totalPaidAccumulator += $payment->total_paid;

        return [
            $this->no,
            $payment->payment_code,
            $payment->order->user->name ?? '-',
            $payment->order->user->email ?? '-',
            $payment->order->orderItems->first()?->ticketType?->event?->title ?? '-',
            $payment->paymentMethod->name ?? '-',
            'Rp ' . number_format($payment->amount, 0, ',', '.'),
            'Rp ' . number_format($payment->fee, 0, ',', '.'),
            'Rp ' . number_format($payment->total_paid, 0, ',', '.'),
            $payment->verifiedBy->name ?? '-',
            $payment->verified_at?->format('d/m/Y H:i'),
        ];
    }

    /**
     * Menggunakan registerEvents untuk menyisipkan baris total di akhir sheet
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $summaryRow = $this->no + 2;

                $event->sheet->setCellValue('F' . $summaryRow, 'TOTAL');
                $event->sheet->setCellValue('I' . $summaryRow, 'Rp ' . number_format($this->totalPaidAccumulator, 0, ',', '.'));

                $event->sheet->getStyle('A' . $summaryRow . ':K' . $summaryRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFF3F4F6'],
                    ],
                ]);
            },
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
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
            'A' => 5,
            'B' => 22,
            'C' => 25,
            'D' => 30,
            'E' => 35,
            'F' => 20,
            'G' => 18,
            'H' => 15,
            'I' => 20,
            'J' => 20,
            'K' => 20,
        ];
    }

    public function title(): string
    {
        return 'Laporan Transaksi';
    }
}
