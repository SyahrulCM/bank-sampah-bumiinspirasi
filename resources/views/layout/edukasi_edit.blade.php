@extends('layout.main')

@section('content')
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Edukasi</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Beranda</a></li>
              <li class="breadcrumb-item"><a href="/edukasi">Edukasi</a></li>
              <li class="breadcrumb-item active">Edit Edukasi</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <section class="content">
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-md-8">
            <div class="card card-warning shadow">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-edit mr-2"></i>Form Edit Edukasi</h3>
              </div>
              <form action="{{ route('edukasi.update', $edukasi->id_edukasi) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="judul"><i class="fas fa-heading mr-1"></i> Judul</label>
                    <input type="text" name="judul" id="judul" class="form-control" value="{{ $edukasi->judul }}" required>
                  </div>
                  <div class="form-group">
                    <label for="isi"><i class="fas fa-align-left mr-1"></i> Isi</label>
                    <textarea name="isi" id="isi" class="form-control" rows="6" required>{{ $edukasi->isi }}</textarea>
                  </div>
                  <div class="form-group">
                    <label for="foto"><i class="fas fa-image mr-1"></i> Foto</label><br>
                    @if($edukasi->foto && file_exists(public_path('storage/' . $edukasi->foto)))
                      <img src="{{ asset('storage/' . $edukasi->foto) }}" width="120" class="mb-2 rounded shadow">
                    @endif
                    <input type="file" name="foto" id="foto" class="form-control mt-2">
                    <small class="text-muted">Upload foto baru jika ingin mengganti.</small>
                  </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                  <a href="{{ route('edukasi.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
                  <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Update</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection
