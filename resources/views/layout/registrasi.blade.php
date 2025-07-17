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
      @if(session('sukses'))
        <div class="alert alert-success alert-dismissible">
          {{ session('sukses') }}
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      @endif
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Registrasi Nasabah</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active">Registrasi</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Data Registrasi Nasabah</h3>
        </div>
        <div class="card-body">
          {{-- Tombol Tambah (Modal) dan Import --}}
          <div class="d-flex justify-content-between mb-3">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-registrasi">
              Tambah
            </button>
            <form action="{{ route('registrasi.import') }}" method="POST" enctype="multipart/form-data" class="form-inline">
              @csrf
              <input type="file" name="file" class="form-control-file mr-2" accept=".xlsx,.xls" required>
              <button type="submit" class="btn btn-primary">Import Excel</button>
            </form>
          </div>

          {{-- Tabel --}}
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Alamat</th>
                <th>No. Telepon</th>
                <th>No. Induk Nasabah</th>
                <th>Tanggal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($data as $index => $item)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_lengkap }}</td>
                <td>{{ $item->alamat }}</td>
                <td>{{ $item->nomer_telepon }}</td>
                <td>{{ $item->nomer_induk_nasabah }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>
                  <div class="btn-group" role="group" aria-label="Aksi">
                    <a href="/registrasi/edit/{{ $item->id_registrasi }}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="/registrasi/hapus/{{ $item->id_registrasi }}" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                  </div>
                </td>
              </tr>
              @endforeach
              @if($data->isEmpty())
              <tr>
                <td colspan="7" class="text-center">Belum ada data registrasi.</td>
              </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </section>
</div>

<!-- Modal Registrasi -->
<div class="modal fade" id="modal-registrasi">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title text-white">Form Registrasi Baru</h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('registrasi.input') }}" method="POST">
        @csrf
        <div class="modal-body row">
          <div class="form-group col-md-6">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <label>Alamat</label>
            <input type="text" name="alamat" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <label>No. Telepon</label>
            <input type="text" name="nomer_telepon" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <label>No. Induk Nasabah</label>
            <input type="text" name="nomer_induk_nasabah" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <label>Password (Opsional)</label>
            <input type="password" name="password" class="form-control">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
