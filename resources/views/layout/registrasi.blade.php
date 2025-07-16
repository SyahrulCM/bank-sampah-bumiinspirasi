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
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Registrasi</h1>
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
      @if(session('sukses'))
        <div class="alert alert-success alert-dismissible">
          {{ session('sukses') }}
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      @endif

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h3 class="card-title">Data Nasabah</h3>
              
            </div>
            <div class="card-body">
              <h6>*hanya file excel<h6>
              <form action="{{ route('registrasi.import') }}" method="POST" enctype="multipart/form-data"
                    class="d-flex align-items-center" style="gap: 10px;">
                  @csrf
                  <div class="form-group mb-0" style="width: 250px;">
                      <input type="file" id="fileInput" name="file" class="form-control p-1 py-1" required>
                  </div>
                  <button type="submit" class="btn btn-success btn-sm">Import</button>
              </form>
              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-lg">Tambah</button>
              <br><br>

              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped" style="width: 100%;">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama lengkap</th>
                      <th>Usia</th>
                      <th>Alamat</th>
                      <th>Nomor Telepon</th>
                      <th>Tanggal</th>
                      <th>AKSI</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data as $d)
                      <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$d->nama_lengkap}}</td>
                        <td>{{$d->usia}}</td>
                        <td>{{$d->alamat}}</td>
                        <td>{{$d->nomer_telepon}}</td>
                        <td>{{$d->tanggal}}</td>
                        <td>
                          <div class="btn-group" role="group" aria-label="Aksi">
                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailModal{{$d->id_registrasi}}">Detail</button>
                            <a href="{{ route('registrasi.edit', $d->id_registrasi) }}" class="btn btn-sm btn-warning">Edit</a>
                            <a href="/registrasi/hapus/{{ $d->id_registrasi }}" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus data ini?')">Hapus</a>
                          </div>
                        </td>
                      </tr>
                      <!-- Modal Detail -->
                      <div class="modal fade" id="detailModal{{$d->id_registrasi}}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{$d->id_registrasi}}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="detailModalLabel{{$d->id_registrasi}}">Detail Registrasi</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <div class="row">
                                <div class="col-md-6">
                                  <p><strong>Nama Lengkap:</strong> {{$d->nama_lengkap}}</p>
                                  <p><strong>Usia:</strong> {{$d->usia}}</p>
                                  <p><strong>Jenis Kelamin:</strong> {{$d->jenis_kelamin}}</p>
                                  <p><strong>Alamat:</strong> {{$d->alamat}}</p>
                                  <p><strong>Nomor Telepon:</strong> {{$d->nomer_telepon}}</p>
                                  <p><strong>Email:</strong> {{$d->email}}</p>
                                  <p><strong>Pekerjaan:</strong> {{$d->pekerjaan}}</p>
                                </div>
                                <div class="col-md-6">
                                  <p><strong>Nama Rekening:</strong> {{$d->nama_rekening}}</p>
                                  <p><strong>Nomor Rekening:</strong> {{$d->nomor_rekening}}</p>
                                  <p><strong>Transportasi:</strong> {{$d->transportasi}}</p>
                                  <p><strong>Mengetahui:</strong> {{$d->mengetahui}}</p>
                                  <p><strong>Alasan:</strong> {{$d->alasan}}</p>
                                  <p><strong>Tanggal:</strong> {{$d->tanggal}}</p>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                          </div>
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
    </div>
  </section>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Data Registrasi</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="/registrasi/input" method="POST">
        @csrf
        <div class="card-body">
          <div class="form-group"><label>Nama Lengkap</label><input type="text" class="form-control" name="nama_lengkap" required></div>
          <div class="form-group"><label>Usia</label><input type="number" class="form-control" name="usia" required></div>
          <div class="form-group"><label>Jenis Kelamin</label>
            <select class="form-control" name="jenis_kelamin">
              <option value="">=Pilih=</option>
              <option value="Pria">Pria</option>
              <option value="Wanita">Wanita</option>
            </select>
          </div>
          <div class="form-group"><label>Alamat</label><input type="text" class="form-control" name="alamat"></div>
          <div class="form-group"><label>Nomor Telepon</label><input type="number" class="form-control" name="nomer_telepon"></div>
          <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email"></div>
          <div class="form-group"><label>Pekerjaan</label><input type="text" class="form-control" name="pekerjaan"></div>
          <div class="form-group"><label>Nama Rekening</label><input type="text" class="form-control" name="nama_rekening"></div>
          <div class="form-group"><label>Nomor Rekening</label><input type="text" class="form-control" name="nomor_rekening"></div>
          <div class="form-group"><label>Transportasi</label>
            <select class="form-control" name="transportasi">
              <option>=Pilih=</option>
              <option>Jalan</option>
              <option>Motor</option>
              <option>Mobil</option>
            </select>
          </div>
          <div class="form-group"><label>Mengetahui</label>
            <select class="form-control" name="mengetahui">
              <option>=Pilih=</option>
              <option>Keluarga</option>
              <option>Tetangga</option>
              <option>Website</option>
              <option>Petugas BSBI</option>
              <option>Tahu Sendiri</option>
              <option>Rekanan Kegiatan</option>
              <option>Lainnya</option>
            </select>
          </div>
          <div class="form-group"><label>Alasan</label><input type="text" class="form-control" name="alasan"></div>
          <div class="form-group"><label>Tanggal</label><input type="date" class="form-control" name="tanggal"></div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
