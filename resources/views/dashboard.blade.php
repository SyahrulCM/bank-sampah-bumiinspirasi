@extends('layout.main')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dasbor</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
            <li class="breadcrumb-item active">Dasbor</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>{{ $jumlahTransaksi }}</h3>
              <p>Transaksi Nasabah</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{ route('layout.transaksi')}}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{ $jumlahRegistrasi }}</h3>
              <p>Data Nasabah</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="{{ route('registrasi.index') }}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{ $jumlahSampah }}kg</h3>

                <p>Stok Sampah</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="{{ route('stok.index') }}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ $jumlahPenjualan }}</h3>

                <p>Penjualan</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="{{ route('penjualans.index') }}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-12">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h5 class="card-title mb-0">Grafik Transaksi Perbulan</h5>
            </div>
            <div class="card-body">
              <canvas id="chartTransaksi" height="80"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-12">
          <div class="card">
            <div class="card-header bg-success text-white">
              <h5 class="card-title mb-0">Grafik Penjualan Perbulan</h5>
            </div>
            <div class="card-body">
              <canvas id="chartPenjualan" height="80"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
  var bulan = @json($chartBulan ?? []);
  var jumlah = @json($chartJumlah ?? []);
  var ctx = document.getElementById('chartTransaksi').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: bulan,
      datasets: [{
        label: 'Jumlah Transaksi',
        data: jumlah,
        backgroundColor: 'rgba(54, 162, 235, 0.7)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        datalabels: {
          display: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            precision: 0 // <- hanya angka bulat tanpa koma
          }
        }
      }
    },
    plugins: [ChartDataLabels]
  });

  var bulanPenjualan = @json($chartBulanPenjualan ?? []);
  var jumlahPenjualan = @json($chartJumlahPenjualan ?? []);
  var ctx2 = document.getElementById('chartPenjualan').getContext('2d');
  new Chart(ctx2, {
    type: 'bar',
    data: {
      labels: bulanPenjualan,
      datasets: [{
        label: 'Jumlah Penjualan',
        data: jumlahPenjualan,
        backgroundColor: 'rgba(40, 167, 69, 0.7)',
        borderColor: 'rgba(40, 167, 69, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        datalabels: {
          display: false // Tidak tampilkan angka di atas bar
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            precision: 0 // <- hanya angka bulat tanpa koma
          }
        }
      }
    },
    plugins: [ChartDataLabels]
  });
</script>
@endsection