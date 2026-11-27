<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $meta['title'] ?? 'Laporan Transaksi LapangIn' }}</title>
    <style>
        @page {
            margin: 18mm 16mm 18mm 16mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9.5px;
            line-height: 1.45;
            color: #111827;
            margin: 0;
            padding: 0;
        }

        .doc-header {
            border-bottom: 1.5px solid #111827;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .brand {
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #0f2444;
        }

        .doc-title {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 3px 0 2px 0;
            color: #111827;
        }

        .doc-subtitle {
            font-size: 9px;
            color: #4b5563;
            margin: 0;
        }

        .section-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #0f2444;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 3px;
            margin: 12px 0 6px 0;
        }

        .meta-line {
            margin: 1.5px 0;
            font-size: 9px;
        }

        .meta-label {
            display: inline-block;
            width: 115px;
            font-weight: bold;
            color: #374151;
        }

        .summary-box {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            margin-top: 6px;
            background-color: #f9fafb;
        }

        .summary-line {
            margin: 2px 0;
            font-size: 9px;
        }

        .entry {
            padding: 7px 0;
            border-bottom: 1px dashed #d1d5db;
            page-break-inside: avoid;
        }

        .entry-head {
            font-size: 9.5px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 3px;
        }

        .entry-code {
            font-family: 'DejaVu Sans Mono', monospace;
        }

        .entry-line {
            margin: 1px 0 1px 14px;
            font-size: 8.8px;
            color: #1f2937;
        }

        .entry-key {
            display: inline-block;
            width: 105px;
            color: #4b5563;
            font-weight: bold;
        }

        .empty-note {
            padding: 16px 0;
            font-style: italic;
            color: #6b7280;
            text-align: center;
        }

        .doc-footer {
            margin-top: 14px;
            padding-top: 6px;
            border-top: 1px solid #d1d5db;
            font-size: 8px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="doc-header">
        <div class="brand">LapangIn — Platform Reservasi Venue Olahraga</div>
        <div class="doc-title">{{ $meta['title'] ?? 'Laporan Transaksi' }}</div>
        @if(!empty($meta['subtitle']))
            <p class="doc-subtitle">{{ $meta['subtitle'] }}</p>
        @endif
    </div>

    <div class="section-title">Informasi Dokumen</div>
    <div class="meta-line"><span class="meta-label">Cakupan Venue</span>: {{ $meta['scope'] ?? '-' }}</div>
    <div class="meta-line"><span class="meta-label">Periode / Filter</span>: {{ $meta['filter'] ?? 'Semua Data' }}</div>
    <div class="meta-line"><span class="meta-label">Dicetak Oleh</span>: {{ $meta['printed_by'] ?? '-' }}</div>
    <div class="meta-line"><span class="meta-label">Waktu Cetak</span>: {{ $meta['printed_at'] ?? now()->format('d M Y H:i') }} WIB</div>
    <div class="meta-line"><span class="meta-label">Jumlah Data</span>: {{ count($entries) }} transaksi</div>

    @if(!empty($summary))
        <div class="section-title">Ringkasan Keuangan</div>
        <div class="summary-box">
            @foreach($summary as $label => $value)
                <div class="summary-line">
                    <span class="meta-label">{{ $label }}</span>: {{ $value }}
                </div>
            @endforeach
        </div>
    @endif

    <div class="section-title">Rincian Daftar Transaksi</div>

    @forelse($entries as $index => $item)
        <div class="entry">
            <div class="entry-head">
                {{ $index + 1 }}. <span class="entry-code">[{{ $item['booking_code'] }}]</span> — Status: {{ $item['status'] }} | Dibuat: {{ $item['created_at'] }}
            </div>
            @if(!empty($item['venue']))
                <div class="entry-line"><span class="entry-key">Venue</span>: {{ $item['venue'] }}</div>
            @endif
            <div class="entry-line"><span class="entry-key">Lapangan</span>: {{ $item['court'] }}</div>
            <div class="entry-line"><span class="entry-key">Penyewa</span>: {{ $item['customer_name'] }} ({{ $item['customer_phone'] }} | {{ $item['customer_email'] }})</div>
            <div class="entry-line"><span class="entry-key">Jadwal Main</span>: {{ $item['booking_date'] }} | {{ $item['times'] }}</div>
            <div class="entry-line"><span class="entry-key">Rincian Biaya</span>: Total Rp {{ number_format($item['total_amount'], 0, ',', '.') }} | Dibayar Rp {{ number_format($item['paid_amount'], 0, ',', '.') }} | Sisa Rp {{ number_format($item['remaining_amount'], 0, ',', '.') }}</div>
            <div class="entry-line"><span class="entry-key">Pembayaran</span>: {{ $item['payment_method'] }} | Kasir/Pelunasan: {{ $item['settled_by'] }}</div>
        </div>
    @empty
        <div class="empty-note">Tidak ada data transaksi yang sesuai dengan filter pada dokumen ini.</div>
    @endforelse

    <div class="doc-footer">
        Dokumen laporan berbasis teks ini dihasilkan secara otomatis oleh sistem LapangIn pada {{ $meta['printed_at'] ?? now()->format('d M Y H:i') }} WIB.
    </div>
</body>
</html>
