@extends('layout.main')

@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Data Nasabah</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active">Edit Data Nasabah</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Form Edit Data Nasabah</h3>
            </div>

            <form action="/registrasi/update/{{ $registrasi->id_registrasi }}" method="POST">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label>Nama Lengkap</label>
                  <input type="text" class="form-control" name="nama_lengkap" placeholder="Masukkan Nama Lengkap" value="{{ $registrasi->nama_lengkap }}" required>
                </div>

                <div class="form-group">
                  <label>Alamat</label>
                  <input type="text" class="form-control" name="alamat" placeholder="Masukkan Alamat" value="{{ $registrasi->alamat }}" required>
                </div>

                <div class="form-group">
                  <label>Nomor Telepon</label>
                  <input type="text" class="form-control" name="nomer_telepon" placeholder="Masukkan Nomor Telepon" value="{{ $registrasi->nomer_telepon }}" required>
                </div>

                <div class="form-group">
                  <label>Nomor Induk Nasabah</label>
                  <input type="text" class="form-control" name="nomer_induk_nasabah" placeholder="Masukkan Nomor Induk Nasabah" value="{{ $registrasi->nomer_induk_nasabah }}" required>
                </div>

                <div class="form-group">
                  <label>Password Baru (opsional)</label>
                  <input type="password" class="form-control" name="password" placeholder="Masukkan Password Baru">
                  <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                </div>

                <div class="form-group">
                  <label>Tanggal</label>
                  <input type="date" class="form-control" name="tanggal" value="{{ $registrasi->tanggal }}" required>
                </div>
              </div>

              <div class="card-footer">
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                <a href="/registrasi" class="btn btn-secondary">Kembali</a>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
