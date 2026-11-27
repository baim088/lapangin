<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $meta['title'] ?? 'Laporan Keuangan LapangIn' }}</title>
    <style>
        @font-face {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 400;
            src: url('{{ str_replace('\\', '/', resource_path('fonts/Poppins-Regular.ttf')) }}') format('truetype');
        }

        @font-face {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 600;
            src: url('{{ str_replace('\\', '/', resource_path('fonts/Poppins-SemiBold.ttf')) }}') format('truetype');
        }

        @font-face {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 700;
            src: url('{{ str_replace('\\', '/', resource_path('fonts/Poppins-Bold.ttf')) }}') format('truetype');
        }

        @page {
            size: A4 portrait;
            margin: 3.1cm 1.6cm 2.1cm 1.6cm;
        }

        body {
            font-family: Helvetica, 'DejaVu Sans', sans-serif;
            font-size: 8.5pt;
            line-height: 1.4;
            color: #222222;
            margin: 0;
            padding: 0;
        }

        /* ===== FIXED KOP SURAT (EVERY PAGE) ===== */
        .page-header {
            position: fixed;
            top: -2.75cm;
            left: -1.6cm;
            right: -1.6cm;
            height: 2.35cm;
            padding: 0.25cm 1.6cm 0 1.6cm;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-logo-cell {
            width: 34%;
            vertical-align: middle;
            padding-right: 12px;
        }

        .kop-logo {
            height: 44px;
            width: auto;
        }

        .kop-brand-fallback {
            font-size: 18pt;
            font-weight: bold;
            color: #087B50;
            letter-spacing: 0.5px;
        }

        .kop-info-cell {
            width: 66%;
            vertical-align: middle;
            border-left: 1.5px solid #087B50;
            padding-left: 12px;
            font-size: 7.5pt;
            line-height: 1.38;
            color: #222222;
        }

        .kop-tagline {
            font-family: 'Poppins', sans-serif;
            font-size: 8.5pt;
            font-weight: 700;
            color: #087B50;
            margin-bottom: 2px;
        }

        .kop-bars {
            margin-top: 7px;
        }

        .bar-green {
            height: 4px;
            background-color: #087B50;
        }

        .bar-orange {
            height: 1.5px;
            background-color: #F47716;
            margin-top: 2px;
        }

        /* ===== FIXED FOOTER (EVERY PAGE) ===== */
        .page-footer {
            position: fixed;
            bottom: -1.75cm;
            left: -1.6cm;
            right: -1.6cm;
            height: 1.35cm;
            padding: 0 1.6cm;
        }

        .footer-meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            font-size: 7.5pt;
            color: #555555;
        }

        .footer-page::after {
            content: "Halaman " counter(page);
        }

        /* ===== DOCUMENT TITLE & META ===== */
        .doc-title-wrap {
            margin-bottom: 10px;
        }

        .doc-title {
            font-family: 'Poppins', sans-serif;
            font-size: 14pt;
            font-weight: 700;
            color: #101F35;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin: 0 0 2px 0;
        }

        .doc-subtitle {
            font-family: 'Poppins', sans-serif;
            font-size: 8.5pt;
            color: #087B50;
            font-weight: 600;
            margin: 0;
        }

        .meta-box {
            width: 100%;
            border-collapse: collapse;
            background-color: #F3F5F4;
            border-left: 3px solid #087B50;
            margin-bottom: 10px;
        }

        .meta-box td {
            padding: 3.5px 7px;
            font-size: 7.8pt;
            vertical-align: top;
        }

        .meta-key {
            font-weight: bold;
            color: #101F35;
            white-space: nowrap;
            width: 12%;
        }

        .meta-val-left {
            color: #222222;
            width: 31%;
        }

        .meta-key-right {
            font-weight: bold;
            color: #101F35;
            white-space: nowrap;
            width: 13%;
        }

        .meta-val-right {
            color: #222222;
            width: 44%;
        }

        /* ===== FINANCIAL SUMMARY STRIP ===== */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .summary-table td {
            border: 1px solid #d7dedb;
            background-color: #F3F5F4;
            padding: 6px 8px;
            vertical-align: top;
        }

        .sum-label {
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #555555;
            margin-bottom: 2px;
        }

        .sum-value {
            font-family: 'Poppins', sans-serif;
            font-size: 9.5pt;
            font-weight: 700;
            color: #101F35;
        }

        .sum-value.green {
            color: #087B50;
        }

        .sum-value.orange {
            color: #F47716;
        }

        .sum-note {
            font-size: 7pt;
            color: #555555;
            margin-top: 2px;
        }

        /* ===== DATA TABLE ===== */
        .section-heading {
            font-family: 'Poppins', sans-serif;
            font-size: 9.5pt;
            font-weight: 700;
            color: #101F35;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin: 0 0 5px 0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            display: table-header-group;
        }

        .data-table th {
            font-family: 'Poppins', sans-serif;
            background-color: #087B50;
            color: #ffffff;
            font-size: 7.8pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.25px;
            padding: 6px 6px;
            border: 1px solid #076a44;
            text-align: center;
            vertical-align: middle;
        }

        .data-table td.right {
            text-align: right;
        }

        .data-table td.center {
            text-align: center;
        }

        .data-table tbody tr {
            page-break-inside: avoid;
        }

        .data-table tbody tr.row-even {
            background-color: #ffffff;
        }

        .data-table tbody tr.row-odd {
            background-color: #F3F5F4;
        }

        .data-table td {
            padding: 5.5px 6px;
            border: 1px solid #dce2df;
            font-size: 8pt;
            vertical-align: top;
            color: #222222;
        }

        .code-mono {
            font-family: 'DejaVu Sans Mono', monospace;
            font-weight: bold;
            color: #101F35;
            font-size: 7.8pt;
        }

        .sub-text {
            font-size: 7.2pt;
            color: #555555;
        }

        .status-text {
            font-weight: bold;
            color: #101F35;
        }

        .empty-row {
            text-align: center;
            padding: 16px;
            color: #666666;
            font-style: italic;
        }
    </style>
</head>
<body>
    {{-- KOP SURAT HEADER --}}
    <div class="page-header">
        <table class="kop-table">
            <tr>
                <td class="kop-logo-cell">
                    @if(!empty($logoDataUri))
                        <img src="{{ $logoDataUri }}" alt="LapangIn" class="kop-logo">
                    @else
                        <div class="kop-brand-fallback">Lapangin</div>
                    @endif
                </td>
                <td class="kop-info-cell">
                    <div class="kop-tagline">Smart Sports Venue Booking &amp; Management Platform</div>
                    <div>Gedung Menara Sudirman Lt. 12, Jl. Jend. Sudirman Kav. 60, Jakarta Selatan 12190</div>
                    <div>Telp: (021) 2345-6789 &nbsp;|&nbsp; WhatsApp: +62 123-4567-8910</div>
                    <div>Email: halo@lapangin.id &nbsp;|&nbsp; Website: www.lapangin.id</div>
                </td>
            </tr>
        </table>
        <div class="kop-bars">
            <div class="bar-green"></div>
            <div class="bar-orange"></div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="page-footer">
        <table class="footer-meta">
            <tr>
                <td>LapangIn — Dokumen Laporan Resmi Sistem</td>
                <td style="text-align: right;"><span class="footer-page"></span></td>
            </tr>
        </table>
        <div class="bar-green"></div>
        <div class="bar-orange"></div>
    </div>

    {{-- JUDUL LAPORAN --}}
    <div class="doc-title-wrap">
        <h1 class="doc-title">{{ $meta['title'] ?? 'Laporan Keuangan & Kas Venue' }}</h1>
        @if(!empty($meta['subtitle']))
            <p class="doc-subtitle">{{ $meta['subtitle'] }}</p>
        @endif
    </div>

    {{-- METADATA DOKUMEN --}}
    <table class="meta-box">
        <tr>
            <td class="meta-key">Cakupan Venue</td>
            <td class="meta-val-left">: {{ $meta['scope'] ?? '-' }}</td>
            <td class="meta-key-right">Dicetak Oleh</td>
            <td class="meta-val-right">: {{ $meta['printed_by'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="meta-key">Periode / Filter</td>
            <td class="meta-val-left">: {{ $meta['filter'] ?? 'Semua Data' }}</td>
            <td class="meta-key-right">Waktu Cetak</td>
            <td class="meta-val-right">: {{ $meta['printed_at'] ?? now()->format('d M Y H:i') }} WIB</td>
        </tr>
    </table>

    {{-- RINGKASAN KEUANGAN --}}
    @if(!empty($summary))
        <table class="summary-table">
            <tr>
                @foreach($summary as $label => $value)
                    @php
                        $isCash = str_contains(strtolower($label), 'kas masuk');
                        $isPiutang = str_contains(strtolower($label), 'piutang') || str_contains(strtolower($label), 'sisa');
                    @endphp
                    <td style="width: {{ round(100 / max(1, count($summary)), 2) }}%;">
                        <div class="sum-label">{{ $label }}</div>
                        <div class="sum-value {{ $isCash ? 'green' : ($isPiutang ? 'orange' : '') }}">{{ $value }}</div>
                    </td>
                @endforeach
            </tr>
        </table>
    @endif

    {{-- TABEL RINCIAN TRANSAKSI --}}
    <div class="section-heading">Rincian Transaksi ({{ count($entries) }} Data)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th class="center" style="width: 5%;">No</th>
                <th style="width: 18%;">Kode &amp; Waktu</th>
                <th style="width: 33%;">Penyewa &amp; Lapangan</th>
                <th class="right" style="width: 23%;">Rincian Biaya</th>
                <th style="width: 21%;">Status &amp; Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($entries as $index => $item)
                <tr class="{{ $index % 2 === 0 ? 'row-even' : 'row-odd' }}">
                    <td class="center">{{ $index + 1 }}</td>
                    <td>
                        <div class="code-mono">{{ $item['booking_code'] }}</div>
                        <div class="sub-text">{{ $item['created_at'] }}</div>
                    </td>
                    <td>
                        <div><strong>{{ $item['customer_name'] }}</strong> <span class="sub-text">({{ $item['customer_phone'] }})</span></div>
                        @if(!empty($item['venue']))
                            <div class="sub-text">{{ $item['venue'] }} — {{ $item['court'] }}</div>
                        @else
                            <div class="sub-text">{{ $item['court'] }}</div>
                        @endif
                        <div class="sub-text">Main: {{ $item['booking_date'] }} | {{ $item['times'] }}</div>
                    </td>
                    <td class="right">
                        <div><strong>Total: Rp {{ number_format($item['total_amount'], 0, ',', '.') }}</strong></div>
                        <div class="sub-text">Bayar: Rp {{ number_format($item['paid_amount'], 0, ',', '.') }}</div>
                        <div class="sub-text">Sisa: Rp {{ number_format($item['remaining_amount'], 0, ',', '.') }}</div>
                    </td>
                    <td>
                        <div class="status-text">{{ $item['status'] }}</div>
                        <div class="sub-text">Metode: {{ $item['payment_method'] }}</div>
                        @if($item['settled_by'] !== '-')
                            <div class="sub-text">Kasir: {{ $item['settled_by'] }}</div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-row">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
