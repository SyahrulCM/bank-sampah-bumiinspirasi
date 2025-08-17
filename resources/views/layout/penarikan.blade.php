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

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
          {{ session('error') }}
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      @endif

      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Penarikan</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active">Penarikan</li>
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
              <h3 class="card-title">Data Penarikan</h3>
            </div>

            <div class="card-body">
              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-lg">
                Tambah Penarikan
              </button>
              <br><br>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($penarikans as $p)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->registrasi->nama_lengkap ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d-m-Y') }}</td>
                    <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                    <td>{{ $p->keterangan }}</td>
                    <td>
                      @if($p->status === 'pending')
                        <span class="badge badge-warning">Pending</span>
                      @elseif($p->status === 'disetujui')
                        <span class="badge badge-success">Disetujui</span>
                      @else
                        <span class="badge badge-danger">Ditolak</span>
                      @endif
                    </td>
                    <td>
                      @if($p->status === 'pending')
                      <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalValidasi{{ $p->id_penarikan }}">
                        Validasi
                      </button>
                      @elseif($p->status === 'ditolak')
                        <small><i>Alasan: {{ $p->alasan_ditolak }}</i></small>
                      @else
                        <span>-</span>
                      @endif
                    </td>
                  </tr>

                  <!-- Modal Validasi -->
                  <div class="modal fade" id="modalValidasi{{ $p->id_penarikan }}">
                    <div class="modal-dialog">
                      <form method="POST" action="{{ route('penarikan.validasi', $p->id_penarikan) }}">
                        @csrf
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Validasi Penarikan</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                          <div class="modal-body">
                            <div class="form-group">
                              <label>Status</label>
                              <select name="status" class="form-control status-select" data-target="#alasanField{{ $p->id_penarikan }}">
                                <option value="disetujui">Setujui</option>
                                <option value="ditolak">Tolak</option>
                              </select>
                            </div>

                            <div class="form-group" id="alasanField{{ $p->id_penarikan }}" style="display: none;">
                              <label>Alasan Penolakan</label>
                              <textarea name="alasan_ditolak" class="form-control" rows="3"></textarea>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Simpan</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>

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

<!-- Modal Tambah Penarikan -->
<div class="modal fade" id="modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Penarikan</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('penarikan.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="form-group">
            <label>Nama Nasabah</label>
            <select name="id_registrasi" class="form-control" required>
              <option value="">-- Pilih Nasabah --</option>
              @foreach($registrasis as $r)
              <option value="{{ $r->id_registrasi }}">
                {{ $r->nama_lengkap }} - {{ $r->nomer_induk_nasabah }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Jumlah Penarikan</label>
            <input type="number" name="jumlah" class="form-control" placeholder="Masukkan jumlah (min. 1000)" required min="1000">
          </div>

          <div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="keterangan" class="form-control" placeholder="Opsional (misal: Penarikan mingguan)">
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Script Toggle Alasan Penolakan -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.status-select').forEach(function(select) {
      select.addEventListener('change', function () {
        const targetId = this.getAttribute('data-target');
        const alasanField = document.querySelector(targetId);
        if (this.value === 'ditolak') {
          alasanField.style.display = 'block';
        } else {
          alasanField.style.display = 'none';
        }
      });
    });
  });
</script>
@endsection
