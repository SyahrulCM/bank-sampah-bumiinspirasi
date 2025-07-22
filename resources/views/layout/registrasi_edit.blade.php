@extends('layout.main')
@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Registrasi Nasabah</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item"><a href="/registrasi">Registrasi</a></li>
            <li class="breadcrumb-item active">Edit</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header bg-warning">
          <h3 class="card-title">Form Edit Data Registrasi</h3>
        </div>
        <form action="{{ url('/registrasi/update/' . $data->id_registrasi) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="card-body row">
            <div class="form-group col-md-6">
              <label>Nama Lengkap</label>
              <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $data->nama_lengkap) }}" required>
            </div>
            <div class="form-group col-md-6">
              <label>Alamat</label>
              <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $data->alamat) }}" required>
            </div>
            <div class="form-group col-md-6">
              <label>No. Telepon</label>
              <input type="text" name="nomer_telepon" class="form-control" value="{{ old('nomer_telepon', $data->nomer_telepon) }}" required>
            </div>
            <div class="form-group col-md-6">
              <label>No. Induk Nasabah</label>
              <input type="text" name="nomer_induk_nasabah" class="form-control" value="{{ old('nomer_induk_nasabah', $data->nomer_induk_nasabah) }}" required>
            </div>
            <div class="form-group col-md-6">
              <label>Tanggal</label>
              <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $data->tanggal) }}" required>
            </div>
            <div class="form-group col-md-6">
              <label>Password (Kosongkan jika tidak diubah)</label>
              <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label>Foto</label><br>
              @if($data->foto)
                <img src="{{ asset($data->foto) }}" alt="Foto" width="80" height="80" class="mb-2" style="object-fit:cover;">
              @endif
              <input type="file" name="foto" class="form-control-file" accept="image/*">
              <small class="text-muted">Biarkan kosong jika tidak ingin mengganti foto.</small>
            </div>
          </div>
          <div class="card-footer">
            <a href="/registrasi" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-success float-right">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>
@endsection
