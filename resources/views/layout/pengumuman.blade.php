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
      <div class="row justify-content-center">
        <div class="col-md-10">
          <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
              <h3 class="card-title"><i class="fas fa-bullhorn mr-2"></i>Daftar Pengumuman</h3>
              <button type="button" class="btn btn-light text-primary" data-toggle="modal" data-target="#modal-tambah-pengumuman">
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
                <thead class="thead-dark">
                  <tr>
                    <th style="width:5%">No</th>
                    <th style="width:20%">Judul</th>
                    <th>Isi</th>
                    <th style="width:10%">Status</th>
                    <th style="width:15%">Aksi</th>
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
                        <a href="{{ route('pengumuman.edit', $item->id_pengumuman) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('pengumuman.destroy', $item->id_pengumuman) }}" method="POST" style="display:inline-block;">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')"><i class="fas fa-trash"></i></button>
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
        </div>
      </div>
      <!-- Modal Tambah Pengumuman -->
      <div class="modal fade" id="modal-tambah-pengumuman">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h4 class="modal-title">Tambah Pengumuman</h4>
              <button type="button" class="close text-white" data-dismiss="modal">
                <span>&times;</span>
              </button>
            </div>
            <form action="{{ route('pengumuman.store') }}" method="POST">
              @csrf
              <div class="modal-body">
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
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-success">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
