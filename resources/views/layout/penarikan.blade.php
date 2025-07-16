<style>
  .dataTables_wrapper .dataTables_filter {
    display: flex;
    justify-content: flex-end;
  }
</style>

@extends('layout.main')
@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">

      {{-- Pesan Sukses --}}
      @if(session('sukses'))
        <div class="alert alert-success alert-dismissible">
          {{ session('sukses') }}
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      @endif

      {{-- Pesan Error --}}
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
          {{ session('error') }}
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      @endif

      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Penarikan</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active">Penarikan</li>
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
              <h3 class="card-title">Data Penarikan</h3>
            </div>

            <div class="card-body">
              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-lg">
                Tambah Penarikan
              </button>
              <br><br>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($penarikans as $p)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->registrasi->nama_lengkap ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d-m-Y') }}</td>
                    <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                    <td>{{ $p->keterangan }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal Tambah Penarikan -->
<div class="modal fade" id="modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Penarikan</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{ route('penarikan.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="form-group">
            <label>Nama Nasabah</label>
            <select name="id_registrasi" class="form-control" required>
              <option value="">-- Pilih Nasabah --</option>
              @foreach($registrasis as $r)
              <option value="{{ $r->id_registrasi }}">{{ $r->nama_lengkap }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Jumlah Penarikan</label>
            <input type="number" name="jumlah" class="form-control" placeholder="Masukkan jumlah (min. 1000)" required min="1000">
          </div>

          <div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="keterangan" class="form-control" placeholder="Opsional (misal: Penarikan mingguan)">
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
