@extends('layout.main')

@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Data Sampah</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active">Edit Sampah</li>
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
              <h3 class="card-title">Form Edit Sampah</h3>
            </div>
            <form action="/sampah/update/{{ $sampah->id_sampah }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label>Jenis Sampah</label>
                  <input type="text" name="jenis_sampah" class="form-control" value="{{ $sampah->jenis_sampah }}" required>
                </div>

                <div class="form-group">
                  <label>Harga Pengepul</label>
                  <input type="number" id="harga_pengepul" name="harga_pengepul" class="form-control" value="{{ $sampah->harga_pengepul }}" required>
                </div>

                <div class="form-group">
                  <label>Harga Ditabung (80%)</label>
                  <input type="text" id="harga_ditabung" class="form-control" value="{{ $sampah->harga_ditabung }}" readonly>
                </div>

                <div class="form-group">
                  <label>Deskripsi</label>
                  <textarea name="deskripsi" class="form-control" rows="2">{{ $sampah->deskripsi }}</textarea>
                </div>

                <div class="form-group">
                  <label>Foto (biarkan kosong jika tidak ingin mengubah)</label>
                  <div class="custom-file">
                    <input type="file" name="foto" class="custom-file-input" id="foto">
                    <label class="custom-file-label" for="foto">Pilih file foto</label>
                  </div>
                </div>

                @if($sampah->foto)
                <div class="form-group mt-2">
                  <label>Foto Saat Ini:</label><br>
                  <img src="{{ asset($sampah->foto) }}" alt="Foto Sampah" width="100" height="100" style="object-fit:cover;">
                </div>
                @endif
              </div>

              <div class="card-footer">
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                <a href="/sampah" class="btn btn-secondary">Kembali</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
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

    // Hitung ulang harga ditabung saat harga pengepul diubah
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
