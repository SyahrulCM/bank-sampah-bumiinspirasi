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
      @if(session('sukses'))
        <div class="alert alert-success alert-dismissible">
          {{ session('sukses') }}
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      @endif
      
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Pengepul</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active">Pengepul</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Data Pengepul</h3>
            </div>

            <div class="card-body">
              <button class="btn btn-info" data-toggle="modal" data-target="#modalTambahPengepul">Tambah</button>
              <br><br>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Pengepul</th>
                    <th>Kontak</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($pengepuls as $p)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->nama_pengepul }}</td>
                    <td>{{ $p->kontak }}</td>
                    <td>{{ $p->alamat }}</td>
                    <td>
                      <div class="btn-group" role="group" aria-label="Aksi">
                        <a href="{{ route('pengepul.edit', $p->id_pengepul) }}" class="btn btn-sm btn-warning">Edit</a>
                        <a href="{{ route('pengepul.hapus', $p->id_pengepul) }}" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pengepul ini?')">Hapus</a>
                      </div>
                    </td>
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

<!-- Modal Tambah Pengepul -->
<div class="modal fade" id="modalTambahPengepul" tabindex="-1" role="dialog" aria-labelledby="modalTambahPengepulLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('pengepul.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTambahPengepulLabel">Tambah Pengepul</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Pengepul</label>
            <input type="text" name="nama_pengepul" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Kontak</label>
            <input type="text" name="kontak" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" rows="3" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection