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

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
          {{ session('error') }}
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      @endif

      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Riwayat Sampah</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active">Riwayat Sampah</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <!-- Tabel -->
      <div class="card">
        <div class="card-body">
            <div class="mb-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalMutasi">
          Ubah Stok Manual
        </button>
      </div>
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Jenis Sampah</th>
                <th>Aksi</th>
                <th>Berat (Kg)</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
              </tr>
            </thead>
            <tbody>
              @foreach($mutasis as $m)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $m->sampah->jenis_sampah }}</td>
                <td>
                  <span class="badge badge-{{ $m->aksi == 'Masuk' ? 'success' : 'danger' }}">
                    {{ $m->aksi }}
                  </span>
                </td>
                <td>{{ number_format($m->berat, 2) }}</td>
                <td>{{ $m->tanggal }}</td>
                <td>{{ $m->keterangan }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </section>
</div>

<!-- Modal Input Mutasi -->
<div class="modal fade" id="modalMutasi" tabindex="-1" role="dialog" aria-labelledby="modalMutasiLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ route('mutasi.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="modalMutasiLabel">Input Mutasi Manual</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="row">
              <div class="col-md-4">
                  <label>Tanggal</label>
                  <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
              </div>
              <div class="col-md-4">
                  <label>Jenis Sampah</label>
                  <select name="id_sampah" class="form-control" id="select-sampah" required>
                      <option value="">-- Pilih --</option>
                      @foreach($sampahs as $s)
                          <option value="{{ $s->id_sampah }}">{{ $s->jenis_sampah }}</option>
                      @endforeach
                  </select>
                  <small id="stok-info" class="form-text text-muted mt-1">Stok saat ini: -</small>
              </div>
              <div class="col-md-4">
                  <label>Aksi</label>
                  <select name="aksi" class="form-control" required>
                      <option value="Masuk">Masuk</option>
                      <option value="Keluar">Keluar</option>
                  </select>
              </div>
              <div class="col-md-6 mt-2">
                  <label>Berat (Kg)</label>
                  <input type="number" name="berat" step="0.01" class="form-control" required>
              </div>
              <div class="col-md-6 mt-2">
                  <label>Keterangan</label>
                  <input type="text" name="keterangan" class="form-control" required>
              </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Simpan</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

<script>
$(document).ready(function () {
    $('#select-sampah').on('change', function () {
        let idSampah = $(this).val();

        if (idSampah) {
            $.get('/stok/sampah/' + idSampah, function (data) {
                $('#stok-info').text('Stok saat ini: ' + data.jumlah + ' Kg');
            });
        } else {
            $('#stok-info').text('Stok saat ini: -');
        }
    });

    // Reset saat modal ditutup
    $('#modalMutasi').on('hidden.bs.modal', function () {
        $('#stok-info').text('Stok saat ini: -');
        $('#select-sampah').val('');
    });
});
</script>