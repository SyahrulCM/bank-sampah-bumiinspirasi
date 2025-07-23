@extends('layout.main')
@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Pengumuman</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active">Pengumuman</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title">Daftar Pengumuman</h3>
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah-pengumuman">
            <i class="fas fa-plus"></i> Tambah Pengumuman
          </button>
        </div>
        <div class="card-body">
          @if(session('sukses'))
            <div class="alert alert-success alert-dismissible">
              {{ session('sukses') }}
              <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
          @endif
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Isi</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($data as $index => $item)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->judul }}</td>
                <td>{{ $item->isi }}</td>
                <td>
                  <span class="badge {{ $item->status == 'aktif' ? 'badge-success' : 'badge-secondary' }}">{{ ucfirst($item->status) }}</span>
                </td>
                <td>
                  <div class="btn-group" role="group">
                    <a href="{{ route('pengumuman.edit', $item->id_pengumuman) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('pengumuman.destroy', $item->id_pengumuman) }}" method="POST" style="display:inline-block;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
              @if($data->isEmpty())
              <tr>
                <td colspan="5" class="text-center">Belum ada data pengumuman.</td>
              </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
      <!-- Modal Tambah Pengumuman -->
      <div class="modal fade" id="modal-tambah-pengumuman">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Pengumuman</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <form action="{{ route('pengumuman.store') }}" method="POST">
        @csrf
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
            <label>Status</label>
            <select name="status" class="form-control" required>
              <option value="aktif">Aktif</option>
              <option value="nonaktif">Nonaktif</option>
            </select>
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
