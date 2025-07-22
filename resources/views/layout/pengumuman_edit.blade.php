@extends('layout.main')
@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Pengumuman</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item"><a href="/pengumuman">Pengumuman</a></li>
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
          <h3 class="card-title">Form Edit Pengumuman</h3>
        </div>
        <form action="{{ route('pengumuman.update', $pengumuman->id_pengumuman) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="card-body row">
            <div class="form-group col-md-12">
              <label>Judul</label>
              <input type="text" name="judul" class="form-control" value="{{ old('judul', $pengumuman->judul) }}" required>
            </div>
            <div class="form-group col-md-12">
              <label>Isi</label>
              <textarea name="isi" class="form-control" rows="5" required>{{ old('isi', $pengumuman->isi) }}</textarea>
            </div>
            <div class="form-group col-md-6">
              <label>Status</label>
              <select name="status" class="form-control" required>
                <option value="aktif" {{ $pengumuman->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ $pengumuman->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
              </select>
            </div>
          </div>
          <div class="card-footer">
            <a href="/pengumuman" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-success float-right">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>
@endsection
