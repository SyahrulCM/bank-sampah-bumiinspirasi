@extends('layout.main')

@section('content')
<style>
  .select2-container--default .select2-selection--single {
      height: 38px !important;
      padding: 6px 12px;
      border: 1px solid #ced4da;
      border-radius: 4px;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 24px;
  }

  .dataTables_wrapper .dataTables_filter {
    display: flex;
    justify-content: flex-end;
  }
</style>

<div class="content-wrapper">
  <!-- Header -->
  <div class="content-header">
    <div class="container-fluid">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('error') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Transaksi</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active">Transaksi</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Content -->
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Data Transaksi</h3>
        </div>
        <div class="card-body">
          <!-- Tombol Tambah -->
          <button type="button" class="btn btn-info mb-3" data-toggle="modal" data-target="#modal-transaksi">
            Tambah Transaksi
          </button>

          <!-- Tabel -->
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Nasabah</th>
                <th>Jenis Sampah</th>
                <th>Berat Sampah (kg)</th>
                <th>Saldo Ditambah</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($transaksis as $t)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $t->nasabah->nama_lengkap ?? '-' }}</td>
                <td>
                  <ul class="mb-0 pl-3">
                    @foreach($t->detailTransaksi as $detail)
                      <li>{{ $detail->sampah->jenis_sampah }}</li>
                    @endforeach
                  </ul>
                </td>
                <td>
                  <ul class="mb-0 pl-3">
                    @foreach($t->detailTransaksi as $detail)
                      <li>{{ $detail->berat_sampah }}</li>
                    @endforeach
                  </ul>
                </td>
                <td>Rp {{ number_format($t->saldo, 0, ',', '.') }}</td>
                <td>
                  <div class="btn-group" role="group" aria-label="Aksi">
                    <a href="{{ route('transaksi.show', $t->id_transaksi) }}" class="btn btn-sm btn-info">Lihat</a>

                    <form action="{{ route('transaksi.hapus', $t->id_transaksi) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')" style="display:inline;">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="card mt-3">
        <div class="card-header">
          <h3 class="card-title">Cetak Data Transaksi</h3>
        </div>
        <div class="card-body">
          <form method="GET" action="{{ url('/transaksi/export-manual') }}">
            <div class="form-row align-items-end">
              <div class="form-group mb-0 mr-2">
                <label>Dari Tanggal:</label>
                <input type="date" name="from" class="form-control" required>
              </div>
              <div class="form-group mb-0 mr-2">
                <label>Sampai Tanggal:</label>
                <input type="date" name="to" class="form-control" required>
              </div>
              <div class="form-group mb-0">
                <button type="submit" class="btn btn-success">Download Excel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal Tambah Transaksi -->
<div class="modal fade" id="modal-transaksi">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{ route('transaksi.simpan') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h4 class="modal-title">Tambah Transaksi</h4>
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="id_registrasi">Nama Nasabah</label>
              <select name="id_registrasi" id="select-nasabah" class="form-control" required>
                <option value="">-- Pilih Nasabah --</option>
                @foreach($nasabah as $n)
                  <option value="{{ $n->id_registrasi }}">{{ $n->nama_lengkap }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-6"></div>
          </div>
          <div id="form-sampah">
            <div class="form-row mb-2">
              <div class="form-group col-md-6">
                <label>Jenis Sampah</label>
                <select name="id_sampah[]" class="form-control" required>
                  <option value="">-- Pilih Jenis Sampah --</option>
                  @foreach($sampah as $s)
                    <option value="{{ $s->id_sampah }}">{{ $s->jenis_sampah }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-md-4">
                <label>Berat (kg)</label>
                <input type="text" name="berat_sampah[]" class="form-control" placeholder="Berat (boleh koma)" required>
              </div>
              <div class="form-group col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-success btn-block add-row">+</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {
    $('.add-row').click(function () {
        const row = `
          <div class="form-row mb-2">
            <div class="form-group col-md-6">
              <select name="id_sampah[]" class="form-control" required>
                <option value="">-- Pilih Jenis Sampah --</option>
                @foreach($sampah as $s)
                  <option value="{{ $s->id_sampah }}">{{ $s->jenis_sampah }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-4">
              <input type="text" name="berat_sampah[]" class="form-control" placeholder="Berat (boleh koma)" required>
            </div>
            <div class="form-group col-md-2 d-flex align-items-end">
              <button type="button" class="btn btn-danger remove-row">-</button>
            </div>
          </div>
        `;
        $('#form-sampah').append(row);
    });

    // Tombol hapus baris
    $(document).on('click', '.remove-row', function () {
        $(this).closest('.row').remove();
    });
});
</script>

<script>
  $(document).ready(function() {
    $('#select-nasabah').select2({
      placeholder: "-- Pilih Nasabah --",
      allowClear: true,
      width: '100%',
      language: {
        searchInputPlaceholder: 'Search nasabah...'
      }
    });
  });
</script>
@endpush
