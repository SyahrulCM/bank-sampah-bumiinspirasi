@extends('layout.main')

@section('title', 'Laporan Saldo')

@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Laporan</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active">Laporan Saldo</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Data Laporan Saldo</h3>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped" id="saldoTable">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Tanggal</th>
                      <th>Aksi</th>
                      <th>Nama</th>
                      <th>Jumlah (Rp)</th>
                      <th>Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($laporan as $index => $item)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                        <td>
                          @if ($item['aksi'] === 'Masuk')
                            <span class="badge bg-success">Masuk</span>
                          @else
                            <span class="badge bg-danger">Keluar</span>
                          @endif
                        </td>
                        <td>{{ $item['nama'] }}</td>
                        <td>Rp. {{ number_format($item['jumlah'], 0, ',', '.') }}</td>
                        <td>{{ $item['keterangan'] }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

              <!-- Ringkasan Saldo -->
              <hr>
              <div class="mt-4">
                <h5>Ringkasan Saldo:</h5>
                <p><strong>Total Masuk:</strong> Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                <p><strong>Total Keluar:</strong> Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                <p><strong>Saldo Akhir:</strong> Rp {{ number_format($totalSaldo, 0, ',', '.') }}</p>
              </div>

            </div>
          </div>

          <!-- Card baru untuk export manual -->
          <div class="card mt-3">
            <div class="card-header">
              <h3 class="card-title">Export Laporan Saldo</h3>
            </div>
            <div class="card-body">
              <form action="{{ route('laporan-saldo.export-manual') }}" method="GET" class="row align-items-end">
                <div class="col-md-4">
                  <label>Dari Tanggal</label>
                  <input type="date" name="from" class="form-control" required>
                </div>
                <div class="col-md-4">
                  <label>Sampai Tanggal</label>
                  <input type="date" name="to" class="form-control" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                  <button type="submit" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Manual Excel
                  </button>
                </div>
              </form>
            </div>
          </div>

          <div class="card mt-3">
            <div class="card-header">
              <h3 class="card-title">Export Laporan Transaksi</h3>
            </div>
            <div class="card-body">
              <form action="{{ route('laporan-transaksi.export-manual') }}" method="GET" class="row align-items-end">
                <div class="col-md-4">
                  <label>Dari Tanggal</label>
                  <input type="date" name="from" class="form-control" required>
                </div>
                <div class="col-md-4">
                  <label>Sampai Tanggal</label>
                  <input type="date" name="to" class="form-control" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                  <button type="submit" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Manual Excel
                  </button>
                </div>
              </form>
            </div>
          </div>

           <!-- Card Export Laporan Mutasi -->
          <div class="card mt-3">
            <div class="card-header">
              <h3 class="card-title">Export Laporan Stok Masuk dan Keluar</h3>
            </div>
            <div class="card-body">
              <form method="GET" action="{{ route('mutasi.exportManual') }}">
                <div class="form-row align-items-end">
                  <div class="form-group mb-0 mr-2">
                    <label>Dari Tanggal:</label>
                    <input type="date" name="from" class="form-control" required>
                  </div>
                  <div class="form-group mb-0 mr-2">
                    <label>Sampai Tanggal:</label>
                    <input type="date" name="to" class="form-control" required>
                  </div>
                  <div class="form-group mb-0 mr-2">
                    <label>Aksi:</label>
                    <select name="aksi" class="form-control">
                      <option value="Semua">Semua</option>
                      <option value="Masuk">Masuk</option>
                      <option value="Keluar">Keluar</option>
                    </select>
                  </div>
                  <div class="form-group mb-0">
                    <button type="submit" class="btn btn-success">Download Excel</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@push('scripts')
<script>
  $(document).ready(function () {
    $('#saldoTable').DataTable();
  });
</script>
@endpush
