<style>
  .dataTables_wrapper .dataTables_filter {
    display: flex;
    justify-content: flex-end;
}
</style>

@extends('layout.main')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">

    {{-- Pesan Sukses --}}
      @if(session('sukses'))
        <div class="alert alert-success alert-dismissible">
          {{ session('sukses') }}
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      @endif
      
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Stok</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active">Stok</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <!-- Main content -->
  <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Data Stok</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <a href="{{ route('mutasi.index') }}" class="btn btn-info">
                  <i class="fas fa-history"></i> Riwayat Sampah
                </a>
                <br><br>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis Sampah</th>
                                <th>Total Berat (kg)</th>
                                <th>Tanggal Update Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stokData as $index => $sampah)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $sampah->jenis_sampah }}</td>
                                    <td>
                                        {{ $sampah->stok->sum('jumlah') ?: '-' }}
                                    </td>
                                    <td>
                                        {{ optional($sampah->stok->sortByDesc('updated_at')->first())->updated_at ? optional($sampah->stok->sortByDesc('updated_at')->first()->updated_at)->format('Y-m-d') : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
@endsection