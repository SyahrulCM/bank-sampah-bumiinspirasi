@extends('layout.main')

@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <h1 class="m-0">Detail Penjualan</h1>
      <a href="{{ route('penjualans.index') }}" class="btn btn-secondary mb-3">← Kembali</a>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <h5><strong>Nama Pengepul:</strong> {{ $penjualan->pengepul->nama_pengepul }}</h5>
          <h5><strong>Tanggal:</strong> {{ $penjualan->tanggal }}</h5>
          <h5><strong>Total Harga:</strong> Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</h5>
          
          <hr>
          <h5>Rincian Sampah</h5>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>Jenis Sampah</th>
                <th>Berat (kg)</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($penjualan->detailPenjualan as $detail)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $detail->sampah->jenis_sampah }}</td>
                <td>{{ $detail->berat_kg }}</td>
                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection