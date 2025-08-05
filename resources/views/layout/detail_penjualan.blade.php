@extends('layout.main')

@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <h3>Detail Penjualan</h3>
          </div>
          <div class="card-body">
            <table class="table table-bordered">
              <tr>
                <th>Pengepul</th>
                <td>{{ $penjualan->pengepul->nama_pengepul }}</td>
              </tr>
              <tr>
                <th>Tanggal</th>
                <td>{{ $penjualan->tanggal }}</td>
              </tr>
              <tr>
                <th>Total Harga Otomatis</th>
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
            <hr>
            <h5>Detail Barang</h5>
            <table class="table table-bordered">
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
          </div>
        </div>
          </section>
        </div>
      </div>
    </div>
  </div>
@endsection