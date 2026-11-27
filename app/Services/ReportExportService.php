<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportService
{
    /**
     * Resolve report date range and display label from request query parameters.
     *
     * @return array{period: string, startDate: Carbon, endDate: Carbon, periodLabel: string}
     */
    public function resolvePeriod(Request $request): array
    {
        $period = (string) $request->query('period', 'bulanan');
        $today = Carbon::today();

        switch ($period) {
            case 'harian':
                $startDate = $today->copy()->startOfDay();
                $endDate = $today->copy()->endOfDay();
                $periodLabel = 'Hari Ini ('.$today->translatedFormat('d F Y').')';
                break;
            case 'mingguan':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $periodLabel = 'Minggu Ini ('.$startDate->translatedFormat('d M').' - '.$endDate->translatedFormat('d M Y').')';
                break;
            case 'custom':
                $startDate = $request->query('start_date') ? Carbon::parse((string) $request->query('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
                $endDate = $request->query('end_date') ? Carbon::parse((string) $request->query('end_date'))->endOfDay() : Carbon::now()->endOfDay();
                $periodLabel = 'Periode '.$startDate->translatedFormat('d M Y').' s/d '.$endDate->translatedFormat('d M Y');
                break;
            case 'bulanan':
            default:
                $period = 'bulanan';
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $periodLabel = 'Bulan Ini ('.Carbon::now()->translatedFormat('F Y').')';
                break;
        }

        return [
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'periodLabel' => $periodLabel,
        ];
    }

    /**
     * Stream bookings data as a UTF-8 CSV file.
     *
     * @param  Collection<int, Booking>  $bookings
     */
    public function exportBookingsCsv(string $filename, Collection $bookings, bool $includeVenue = false): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($bookings, $includeVenue): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            // UTF-8 BOM for proper Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            $columns = ['Kode Booking'];
            if ($includeVenue) {
                $columns[] = 'Venue';
            }
            array_push(
                $columns,
                'Nama Pemesan',
                'Nomor WhatsApp',
                'Email',
                'Lapangan',
                'Tanggal Main',
                'Jam Main',
                'Total (Rp)',
                'Sudah Dibayar (Rp)',
                'Sisa Tagihan (Rp)',
                'Status',
                'Metode Bayar',
                'Petugas/Kasir',
                'Waktu Dibuat'
            );

            fputcsv($handle, $columns);

            foreach ($bookings as $booking) {
                $row = $this->formatBookingRow($booking);
                $csvRow = [$row['booking_code']];
                if ($includeVenue) {
                    $csvRow[] = $row['venue'];
                }
                array_push(
                    $csvRow,
                    $row['customer_name'],
                    $row['customer_phone'],
                    $row['customer_email'],
                    $row['court'],
                    $row['booking_date'],
                    $row['times'],
                    $row['total_amount'],
                    $row['paid_amount'],
                    $row['remaining_amount'],
                    $row['status'],
                    $row['payment_method'],
                    $row['settled_by'],
                    $row['created_at']
                );

                fputcsv($handle, $csvRow);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Render and download a PDF report (text-based or corporate table layout).
     *
     * @param  array{title?: string, subtitle?: string, scope?: string, filter?: string, printed_by?: string, printed_at?: string}  $meta
     * @param  array<string, string>  $summary
     * @param  Collection<int, Booking>  $bookings
     */
    public function exportBookingsPdf(
        string $filename,
        array $meta,
        array $summary,
        Collection $bookings,
        bool $includeVenue = false,
        string $view = 'exports.report-table-pdf'
    ): Response {
        $entries = $bookings->map(function (Booking $booking) use ($includeVenue): array {
            $row = $this->formatBookingRow($booking);
            if (! $includeVenue) {
                $row['venue'] = '';
            }

            return $row;
        })->all();

        $logoDataUri = null;
        $logoPath = public_path('images/logo.png');
        if ($view === 'exports.report-table-pdf' && is_file($logoPath)) {
            $logoDataUri = 'data:image/png;base64,'.base64_encode((string) file_get_contents($logoPath));
        }

        $pdf = Pdf::loadView($view, [
            'meta' => $meta,
            'summary' => $summary,
            'entries' => $entries,
            'logoDataUri' => $logoDataUri,
        ])->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }

    /**
     * Build text-based summary lines from bookings (and optional cash-flow breakdown).
     *
     * @param  Collection<int, Booking>  $bookings
     * @param  array{grossRevenue: int, dpIncome: int, settlementIncome: int, fullPaymentIncome: int, totalCashIn: int, receivables: int}|null  $financial
     * @return array<string, string>
     */
    public function buildSummary(Collection $bookings, ?array $financial = null): array
    {
        if ($financial !== null) {
            return [
                'Gross Transaction' => 'Rp '.number_format($financial['grossRevenue'], 0, ',', '.'),
                'Total Kas Masuk Riil' => 'Rp '.number_format($financial['totalCashIn'], 0, ',', '.'),
                'Rincian Kas Masuk' => 'DP: Rp '.number_format($financial['dpIncome'], 0, ',', '.')
                    .' | Pelunasan: Rp '.number_format($financial['settlementIncome'], 0, ',', '.')
                    .' | Lunas Awal: Rp '.number_format($financial['fullPaymentIncome'], 0, ',', '.'),
                'Sisa Piutang' => 'Rp '.number_format($financial['receivables'], 0, ',', '.'),
            ];
        }

        $activeBookings = $bookings->reject(
            fn (Booking $b) => in_array($b->status, [BookingStatus::Cancelled, BookingStatus::Expired], true)
        );

        $totalGross = (int) $activeBookings->sum('total_amount');
        $totalPaid = (int) $activeBookings->sum('paid_amount');
        $totalRemaining = (int) $activeBookings->sum('remaining_amount');

        return [
            'Total Nilai Sewa' => 'Rp '.number_format($totalGross, 0, ',', '.'),
            'Total Sudah Dibayar' => 'Rp '.number_format($totalPaid, 0, ',', '.'),
            'Total Sisa Tagihan' => 'Rp '.number_format($totalRemaining, 0, ',', '.'),
        ];
    }

    /**
     * Normalize a booking model into plain text values for CSV/PDF export.
     *
     * @return array{
     *     booking_code: string,
     *     venue: string,
     *     customer_name: string,
     *     customer_phone: string,
     *     customer_email: string,
     *     court: string,
     *     booking_date: string,
     *     times: string,
     *     total_amount: int,
     *     paid_amount: int,
     *     remaining_amount: int,
     *     status: string,
     *     payment_method: string,
     *     settled_by: string,
     *     created_at: string
     * }
     */
    private function formatBookingRow(Booking $booking): array
    {
        $firstItem = $booking->items->first();
        $courtName = $firstItem?->court?->name ?? 'Lapangan';
        $bookingDate = $firstItem?->booking_date
            ? Carbon::parse($firstItem->booking_date)->translatedFormat('d M Y')
            : '-';
        $times = $booking->items->pluck('start_time')
            ->map(fn (?string $t) => $t ? substr($t, 0, 5) : '')
            ->filter()
            ->join(', ');

        $initialPayment = $booking->payments->firstWhere('payment_type', '!=', 'settlement') ?? $booking->payment;
        $method = $initialPayment?->payment_method?->label()
            ?? ($initialPayment?->payment_method?->value ?? '-');

        return [
            'booking_code' => $booking->booking_code,
            'venue' => $booking->venue->name ?? '-',
            'customer_name' => $booking->customer_name,
            'customer_phone' => $booking->customer_phone ?? '-',
            'customer_email' => $booking->customer_email ?? '-',
            'court' => $courtName,
            'booking_date' => $bookingDate,
            'times' => $times !== '' ? $times.' WIB' : '-',
            'total_amount' => (int) $booking->total_amount,
            'paid_amount' => (int) $booking->paid_amount,
            'remaining_amount' => (int) $booking->remaining_amount,
            'status' => $booking->status->label(),
            'payment_method' => $method,
            'settled_by' => $booking->settledBy?->name ?? '-',
            'created_at' => $booking->created_at?->format('d/m/Y H:i') ?? '-',
        ];
    }
}
