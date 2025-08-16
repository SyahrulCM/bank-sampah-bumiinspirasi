<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice Penjualan - {{ $penjualan->id_penjualan ?? '' }}</title>
    <style>
        /* PDF-friendly fonts (dompdf supports DejaVu) */
        body { font-family: 'DejaVu Sans', 'Helvetica', Arial, sans-serif; color: #222; font-size: 12px; }
        .container { width: 100%; padding: 10px 18px; }
        .header {
            display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;
        }
        .brand {
            display: flex; align-items: center; gap: 12px;
        }

        .logo { width: 68px; height: 68px; border-radius: 8px; background: #eafaf1; display:flex; align-items:center; justify-content:center; font-weight:700; color:#218838; }
        .title { font-size: 18px; font-weight: 700; color: #218838; }
        .meta { text-align: right; font-size: 11px; color: #546374; }

        .card {
            border-radius: 6px; padding: 12px; background: #fff; box-shadow: 0 0 0 #000; margin-bottom: 12px;
        }

        .row { display: flex; gap: 12px; }
        .col { flex: 1; }
        .col-40 { flex: 0 0 40%; }
        .col-60 { flex: 0 0 60%; }

        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { padding: 8px 10px; border: 1px solid #c3e6cb; }
        th { background: #eafaf1; color:#218838; font-weight:700; text-align: left; }
        tbody tr:nth-child(even) td { background: #f6fff9; }
        .text-right { text-align: right; }
        .muted { color: #6c757d; font-size: 11px; }

        .total-row td { border-top: 2px solid #c3e6cb; font-weight:700; background:#fff; }
        .badge { display:inline-block; padding:4px 8px; border-radius:12px; font-size:11px; }
        .badge-success { background:#eafaf1; color:#218838; border:1px solid #c3e6cb; }
        .badge-warn { background:#fff4e5; color:#d97706; border:1px solid #ffe0b2; }

        .signature { margin-top: 36px; text-align: right; }
        .signature .name { display:inline-block; border-top:1px solid #ccc; padding-top:6px; margin-top:24px; }

        /* Small helper for printing */
        .small { font-size: 11px; }

        @page { margin: 15mm 10mm; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand">
                <div class="logo">
                    <img src="{{ public_path('lte/dist/img/BankSampahLogo.png') }}" style="width:60px; height:60px; object-fit:cover; background:#fff;">
                </div>
                <div>
                    <div class="title">Bank Sampah Bumi Inspirasi</div>
                    <div class="muted small">Jalan Cisitu Indah VI No. 188 Bandung 40135 - Indonesia / bumiinspirasi4@gmail.com</div>
                </div>
            </div>
            <div class="meta">
                @php
                    $tgl = \Carbon\Carbon::parse($penjualan->tanggal)->format('Ymd');
                    $urut = str_pad(($penjualan->id_penjualan ?? 1), 3, '0', STR_PAD_LEFT);
                    $kodeInvoice = 'PJL' . $tgl . $urut;
                @endphp
                <div><strong>Kode Invoice:</strong> {{ $kodeInvoice }}</div>
                <div class="small">Tanggal: {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d/m/Y H:i') }}</div>
                <div class="small">Cetak: {{ now()->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <div class="card">
            <div class="row">
                <div class="col col-40">
                    <strong>Pengepul</strong>
                    <div>{{ $penjualan->pengepul->nama_pengepul ?? '-' }}</div>
                    <div class="muted small">No. HP: {{ $penjualan->pengepul->telp ?? '-' }}</div>
                </div>
                <div class="col col-60">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                        <div>
                            <strong>Ringkasan</strong>
                            <div class="muted small">Total Barang: {{ $penjualan->detailPenjualan->count() }}</div>
                        </div>
                        <div style="text-align:right">
                            <div class="muted small">Total Harga</div>
                            <div style="font-size:16px; font-weight:700">Rp {{ number_format($penjualan->total_harga ?? 0,0,',','.') }}</div>
                            @if($penjualan->hasil_negosiasi)
                                <div class="badge badge-success">Hasil Negosiasi: Rp {{ number_format($penjualan->hasil_negosiasi,0,',','.') }}</div>
                            @else
                                <div class="badge badge-warn">Belum divalidasi</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <strong>Detail Penjualan</strong>
            <table class="small">
                <thead>
                    <tr>
                        <th style="width:56%">Jenis Sampah</th>
                        <th style="width:18%">Berat (kg)</th>
                        <th style="width:26%" class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penjualan->detailPenjualan as $d)
                        <tr>
                            <td>{{ $d->sampah->jenis_sampah ?? '-' }}</td>
                            <td class="text-right">{{ number_format($d->berat_kg ?? 0, 2, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($d->subtotal ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td style="text-align:left">Total</td>
                        <td class="text-right">{{ number_format($penjualan->detailPenjualan->sum('berat_kg'), 2, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($penjualan->total_harga ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @if($penjualan->hasil_negosiasi)
                    <tr>
                        <td colspan="2" style="text-align:left">Hasil Negosiasi</td>
                        <td class="text-right">Rp {{ number_format($penjualan->hasil_negosiasi, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                </tfoot>
            </table>
        </div>

        <div class="signature">
            <div class="muted small">Diterima oleh</div>
            <div class="name">{{ auth()->user()->name ?? 'Petugas' }}</div>
        </div>
    </div>
</body>
</html>
