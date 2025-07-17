@extends('layout.main')
@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <h1 class="m-0">Registrasi Nasabah</h1>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      @if(session('sukses'))
        <div class="alert alert-success">{{ session('sukses') }}</div>
      @endif

      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title">Data Nasabah</h3>
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">Tambah Nasabah</button>
        </div>
        <div class="card-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Alamat</th>
                <th>Nomor Telepon</th>
                <th>Nomor Induk Nasabah</th>
                <th>Tanggal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($data as $d)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $d->nama_lengkap }}</td>
                  <td>{{ $d->alamat }}</td>
                  <td>{{ $d->nomer_telepon }}</td>
                  <td>{{ $d->nomer_induk_nasabah }}</td>
                  <td>{{ $d->tanggal }}</td>
                  <td>
                    <a href="{{ route('registrasi.edit', $d->id_registrasi) }}" class="btn btn-warning btn-sm">Edit</a>
                    <a href="/registrasi/hapus/{{ $d->id_registrasi }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modal-tambah">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="/registrasi/input" method="POST">
        @csrf
        <div class="modal-header">
          <h4 class="modal-title">Tambah Nasabah</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Nomor Telepon</label>
            <input type="number" name="nomer_telepon" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Nomor Induk Nasabah</label>
            <input type="text" name="nomer_induk_nasabah" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
