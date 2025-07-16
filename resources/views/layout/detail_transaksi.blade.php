@extends('layout.main')

@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <h1>Detail Transaksi</h1>
      <a href="{{ route('layout.transaksi') }}" class="btn btn-secondary mb-3">Kembali</a>

      <div class="card">
        <div class="card-header">
          <strong>Tanggal: </strong> {{ $transaksi->tanggal }}<br>
          <strong>Nama Nasabah: </strong> {{ $transaksi->nasabah->nama_lengkap }}
        </div>
        <div class="card-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>Jenis Sampah</th>
                <th>Berat (kg)</th>
                <th>Harga/Unit</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach($transaksi->detailTransaksi as $index => $detail)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->sampah->jenis_sampah }}</td>
                <td>{{ $detail->berat_sampah }}</td>
                <td>Rp {{ number_format($detail->sampah->harga_ditabung, 0, ',', '.') }}</td>
                <td>
                  Rp {{ number_format($detail->berat_sampah * $detail->sampah->harga_ditabung, 0, ',', '.') }}
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <hr>
          <h4>Total Saldo Ditambahkan: Rp {{ number_format($transaksi->saldo, 0, ',', '.') }}</h4>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Optional: DataTables support -->
@push('scripts')
<script>
    $(document).ready(function () {
        $('#dataTable').DataTable();
    });
</script>
@endpush
@endsection
