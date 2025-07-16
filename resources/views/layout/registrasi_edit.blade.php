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
              <h3 class="card-title">Form Edit Registrasi</h3>
            </div>
            <form action="/registrasi/update/{{ $registrasi->id_registrasi }}" method="POST">
              @csrf
              <div class="card-body">
                <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" name="nama_lengkap" placeholder="Masukkan Nama Lengkap" value="{{ $registrasi->nama_lengkap }}">
                </div>

                <div class="form-group">
                <label>Usia</label>
                <input type="number" class="form-control" name="usia" placeholder="Masukkan Usia" value="{{ $registrasi->usia }}">
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select class="form-control" name="jenis_kelamin">
                        <option value="" {{ $registrasi->jenis_kelamin == '' ? 'selected' : '' }}>=Pilih=</option>
                        <option value="Pria" {{ $registrasi->jenis_kelamin == 'Pria' ? 'selected' : '' }}>Pria</option>
                        <option value="Wanita" {{ $registrasi->jenis_kelamin == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                    </select>
                </div>

                <div class="form-group">
                <label>Alamat</label>
                <input type="text" class="form-control" name="alamat" placeholder="Masukan Alamat" value="{{ $registrasi->alamat }}">
                </div>

                <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="number" class="form-control" name="nomer_telepon" placeholder="Masukan Nomor Telepon" value="{{ $registrasi->nomer_telepon }}">
                </div>

                <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" placeholder="Masukan Email" value="{{ $registrasi->email }}">
                </div>

                <div class="form-group">
                <label>Pekerjaan</label>
                <input type="text" class="form-control" name="pekerjaan" placeholder="Masukan Pekerjaan" value="{{ $registrasi->pekerjaan }}">
                </div>

                <div class="form-group">
                <label>Nama Rekening</label>
                <input type="text" class="form-control" name="nama_rekening" placeholder="Masukan Nama Rekening" value="{{ $registrasi->nama_rekening }}">
                </div>

                <div class="form-group">
                <label>Nomor Rekening</label>
                <input type="text" class="form-control" name="nomor_rekening" placeholder="Masukan Nomor Rekening" value="{{ $registrasi->nomor_rekening }}">
                </div>

                <div class="form-group">
                    <label>Transportasi</label>
                    <select class="form-control" name="transportasi">
                        <option value="" {{ $registrasi->transportasi == '' ? 'selected' : '' }}>=Pilih=</option>
                        <option value="Jalan" {{ $registrasi->transportasi == 'Jalan' ? 'selected' : '' }}>Jalan</option>
                        <option value="Motor" {{ $registrasi->transportasi == 'Motor' ? 'selected' : '' }}>Motor</option>
                        <option value="Mobil" {{ $registrasi->transportasi == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Mengetahui</label>
                    <select class="form-control" name="mengetahui">
                        <option value="" {{ $registrasi->mengetahui == '' ? 'selected' : '' }}>=Pilih=</option>
                        <option value="Keluarga" {{ $registrasi->mengetahui == 'Keluarga' ? 'selected' : '' }}>Keluarga</option>
                        <option value="Tetangga" {{ $registrasi->mengetahui == 'Tetangga' ? 'selected' : '' }}>Tetangga</option>
                        <option value="Website" {{ $registrasi->mengetahui == 'Website' ? 'selected' : '' }}>Website</option>
                        <option value="Petugas BSBI" {{ $registrasi->mengetahui == 'Petugas BSBI' ? 'selected' : '' }}>Petugas BSBI</option>
                        <option value="Tahu Sendiri" {{ $registrasi->mengetahui == 'Tahu Sendiri' ? 'selected' : '' }}>Tahu Sendiri</option>
                        <option value="Rekanan Kegiatan" {{ $registrasi->mengetahui == 'Rekanan Kegiatan' ? 'selected' : '' }}>Rekanan Kegiatan</option>
                        <option value="Lainnya" {{ $registrasi->mengetahui == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                <label>Alasan</label>
                <input type="text" class="form-control" name="alasan" placeholder="Alasan" value="{{ $registrasi->alasan }}">
                </div>

                <div class="form-group">
                <label>Tanggal</label>
                <input type="date" class="form-control" name="tanggal" placeholder="Masukan Tanggal" value="{{ $registrasi->tanggal }}">
                </div>

                <div class="card-footer">
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                <a href="/" class="btn btn-secondary">Kembali</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection