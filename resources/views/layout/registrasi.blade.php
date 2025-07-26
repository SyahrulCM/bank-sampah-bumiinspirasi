<style>
  .dataTables_wrapper .dataTables_filter {
    display: flex;
    justify-content: flex-end;
  }
</style>

@extends('layout.main')
@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      @if(session('sukses'))
        <div class="alert alert-success alert-dismissible">
          {{ session('sukses') }}
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      @endif
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Registrasi Nasabah</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active">Registrasi</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card mb-3">
        <div class="card-header bg-info text-white">
          <h3 class="card-title">Import Data Registrasi dari Excel</h3>
        </div>
        <form action="{{ route('registrasi.import') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="card-body row align-items-end">
            <div class="form-group col-md-8 mb-0">
              <label>Pilih File Excel (.xlsx atau .xls)</label>
              <input type="file" name="file" class="form-control-file" accept=".xlsx,.xls" required>
            </div>
            <div class="form-group col-md-4 mb-0 d-flex align-items-end">
              <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-file-import"></i> Import Excel</button>
            </div>
          </div>
        </form>
      </div>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Data Registrasi Nasabah</h3>
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-between mb-3">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-lg">
              Tambah
            </button>
          </div>
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>No. Induk Nasabah</th>
                <th>Saldo</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($data as $index => $item)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_lengkap }}</td>
                <td>{{ $item->nomer_induk_nasabah ?? '-' }}</td>
                <td>Rp {{ number_format($item->saldo ?? 0, 0, ',', '.') }}</td>
                <td>
                  <div class="btn-group mb-1" role="group">
                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailModal{{ $item->id_registrasi }}">Detail</button>
                    @if(!$item->nomer_induk_nasabah)
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#validasiModal{{ $item->id_registrasi }}">Validasi</button>
                    @endif
                    <a href="/registrasi/edit/{{ $item->id_registrasi }}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="/registrasi/hapus/{{ $item->id_registrasi }}" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                  </div>
                </td>
              </tr>

              {{-- Modal Detail --}}
              <div class="modal fade" id="detailModal{{ $item->id_registrasi }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                      <h5 class="modal-title">Detail Nasabah</h5>
                      <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body row">
                      <div class="col-md-6">
                        <p><strong>Nama:</strong> {{ $item->nama_lengkap }}</p>
                        <p><strong>Alamat:</strong> {{ $item->alamat }}</p>
                        <p><strong>No Telepon:</strong> {{ $item->nomer_telepon }}</p>
                        <p><strong>No Induk Nasabah:</strong> {{ $item->nomer_induk_nasabah ?? '-' }}</p>
                        <p><strong>Tanggal:</strong> {{ $item->tanggal }}</p>
                      </div>
                      <div class="col-md-6">
                        <p><strong>Saldo:</strong> Rp {{ number_format($item->saldo ?? 0, 0, ',', '.') }}</p>
                        <p><strong>Foto:</strong></p>
                        @if($item->foto)
                          <img src="{{ asset($item->foto) }}" class="img-fluid" style="max-width: 200px;">
                        @else
                          <p>-</p>
                        @endif
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Modal Validasi --}}
              <div class="modal fade" id="validasiModal{{ $item->id_registrasi }}" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="{{ route('registrasi.validasi.simpan', $item->id_registrasi) }}" method="POST">
                      @csrf
                      <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Validasi Nomor Induk Nasabah</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <label>Masukkan Nomor Induk Nasabah</label>
                          <input type="text" name="nomer_induk_nasabah" class="form-control" required>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              @endforeach
              @if($data->isEmpty())
              <tr>
                <td colspan="5" class="text-center">Belum ada data registrasi.</td>
              </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal Registrasi -->
<div class="modal fade" id="modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{ route('registrasi.input') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h4 class="modal-title">Form Registrasi Baru</h4>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body row">
          <div class="form-group col-md-6">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <label>Alamat</label>
            <input type="text" name="alamat" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <label>No. Telepon</label>
            <input type="text" name="nomer_telepon" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <label>No. Induk Nasabah</label>
            <input type="text" name="nomer_induk_nasabah" class="form-control">
            <small class="text-muted">Kosongkan jika ingin divalidasi nanti</small>
          </div>
          <div class="form-group col-md-6">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <label>Foto (Upload atau Kamera)</label>
            <input type="file" name="foto" class="form-control" accept="image/*" capture="environment">
            <small class="text-muted">Bisa pilih file atau langsung ambil dari kamera jika di buka dari Handphone.</small>
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
@endsection
