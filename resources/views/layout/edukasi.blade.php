@extends('layout.main')
@section('content')
<style>
  .dataTables_wrapper .dataTables_filter {
    display: flex;
    justify-content: flex-end;
  }
</style>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible">
          {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edukasi</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active">Edukasi</li>
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
              <h3 class="card-title">Data Edukasi</h3>
            </div>
            <div class="card-body">
              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalTambahEdukasi">Tambah</button>
              <br><br>
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Isi</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($edukasis as $e)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $e->judul }}</td>
                      <td>{{ Str::limit(strip_tags($e->isi), 80) }}</td>
                      <td>
                        @if($e->foto)
                          <img src="{{ asset($e->foto) }}" width="60" height="60" style="object-fit: cover;">
                        @else
                          -
                        @endif
                      </td>
                      <td>
                        <div class="btn-group" role="group">
                          <a href="{{ route('edukasi.edit', $e->id_edukasi) }}" class="btn btn-warning btn-sm">Edit</a>
                          <form action="{{ route('edukasi.destroy', $e->id_edukasi) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus data ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                          </form>
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

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahEdukasi">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{ route('edukasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h4 class="modal-title">Tambah Edukasi</h4>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label>Judul</label>
            <input type="text" name="judul" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Isi</label>
            <textarea name="isi" class="form-control" rows="4" required></textarea>
          </div>
          <div class="form-group">
            <label>Foto (Opsional)</label>
            <div class="custom-file">
              <input type="file" name="foto" class="custom-file-input" id="fotoEdukasi">
              <label class="custom-file-label" for="fotoEdukasi">Pilih file foto</label>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('fotoEdukasi');
    const label = document.querySelector('.custom-file-label');
    if (fileInput && label) {
      fileInput.addEventListener('change', function () {
        const fileName = this.files[0] ? this.files[0].name : 'Pilih file foto';
        label.textContent = fileName;
      });
    }
  });
</script>
@endsection
