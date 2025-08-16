<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Penjualan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color:#000; }
        h3, h5 { margin: 0 0 10px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-muted { color: #777; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>BANK SAMPAH BUMI INSPIRASI</h2>
        <p>Invoice Penjualan</p>
        <hr>
    </div>

    <h3>Detail Penjualan</h3>
    <table>
        <tr>
            <th>Pengepul</th>
            <td>{{ $penjualan->pengepul->nama_pengepul }}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Total Harga</th>
            <td>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Hasil Negosiasi</th>
            <td>
                @if($penjualan->hasil_negosiasi)
                    Rp {{ number_format($penjualan->hasil_negosiasi, 0, ',', '.') }}
                @else
                    <span class="text-muted">Belum divalidasi</span>
                @endif
            </td>
        </tr>
    </table>

    <h5>Detail Barang</h5>
    <table>
        <thead>
            <tr>
                <th>Jenis Sampah</th>
                <th>Berat (Kg)</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan->detailPenjualan as $d)
            <tr>
                <td>{{ $d->sampah->jenis_sampah }}</td>
                <td>{{ $d->berat_kg }}</td>
                <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p style="text-align:right; margin-top:30px;">
        Hormat Kami,<br><br><br>
        (_____________________)
    </p>
</body>
</html>
