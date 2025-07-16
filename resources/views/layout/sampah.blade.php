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
      @if(session('sukses'))
        <div class="alert alert-success alert-dismissible">
          {{ session('sukses') }}
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      @endif
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
          <h1 class="m-0">Sampah</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active">Sampah</li>
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
              <h3 class="card-title">Data Sampah</h3>
            </div>
            <div class="card-body">
              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-lg">Tambah</button>
              <br><br>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Jenis Sampah</th>
                    <th>Harga Pengepul</th>
                    <th>Harga Ditabung</th>
                    <th>Deskripsi</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($data as $d)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $d->jenis_sampah }}</td>
                      <td>{{ $d->harga_pengepul_rp }}</td>
                      <td>{{ $d->harga_ditabung_rp }}</td>
                      <td>{{ $d->deskripsi ?? '-' }}</td>
                      <td>
                        @if($d->foto)
                          <img src="{{ asset($d->foto) }}" alt="Foto Sampah" width="60" height="60" style="object-fit: cover;">
                        @else
                          -
                        @endif
                      </td>
                      <td>
                        <div class="btn-group" role="group">
                          <a href="{{ route('sampah.edit', $d->id_sampah) }}" class="btn btn-warning btn-sm">Edit</a>
                          <a href="{{ route('sampah.hapus', $d->id_sampah) }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus data ini?')">Hapus</a>
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
<div class="modal fade" id="modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Data Sampah</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form action="/sampah/input" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
          <div class="form-group">
            <label>Jenis Sampah</label>
            <input type="text" name="jenis_sampah" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Harga Pengepul</label>
            <input type="number" name="harga_pengepul" id="harga_pengepul" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Harga Ditabung (80%)</label>
            <input type="number" name="harga_ditabung" id="harga_ditabung" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="2"></textarea>
          </div>

          <div class="form-group">
            <label>Foto</label>
            <div class="custom-file">
              <input type="file" name="foto" class="custom-file-input" id="foto" required>
              <label class="custom-file-label" for="foto">Pilih file foto</label>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('foto');
    const label = document.querySelector('.custom-file-label');
    if (fileInput && label) {
      fileInput.addEventListener('change', function () {
        const fileName = this.files[0] ? this.files[0].name : 'Pilih file foto';
        label.textContent = fileName;
      });
    }

    // Hitung harga ditabung otomatis
    const hargaPengepul = document.getElementById('harga_pengepul');
    const hargaDitabung = document.getElementById('harga_ditabung');

    if (hargaPengepul && hargaDitabung) {
      hargaPengepul.addEventListener('input', function () {
        const value = parseFloat(this.value);
        if (!isNaN(value)) {
          hargaDitabung.value = Math.floor(value * 0.8);
        } else {
          hargaDitabung.value = '';
        }
      });
    }
  });
</script>
@endsection
