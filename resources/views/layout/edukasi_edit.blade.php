@extends('layout.main')

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <h1>Edit Edukasi</h1>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-warning col-md-6">
        <div class="card-header">
          <h3 class="card-title">Form Edit Edukasi</h3>
        </div>

        <form action="{{ route('edukasi.update', $edukasi->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label>Judul</label>
              <input type="text" name="judul" class="form-control" value="{{ $edukasi->judul }}" required>
            </div>
            <div class="form-group">
              <label>Isi</label>
              <textarea name="isi" class="form-control" rows="5" required>{{ $edukasi->isi }}</textarea>
            </div>
            <div class="form-group">
              <label>Foto</label><br>
              @if($edukasi->foto && file_exists(public_path('storage/' . $edukasi->foto)))
                <img src="{{ asset('storage/' . $edukasi->foto) }}" width="100"><br><br>
              @endif
              <input type="file" name="foto" class="form-control">
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>
  </section>
@endsection
