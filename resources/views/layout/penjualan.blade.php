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
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Penjualan</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active">Penjualan</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h3>Data Penjualan</h3>
          <button class="btn btn-info" data-toggle="modal" data-target="#modalTambahPenjualan">Tambah</button>
        </div>
        <div class="card-body">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Pengepul</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Hasil Negosiasi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($penjualans as $p)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->pengepul->nama_pengepul }}</td>
                <td>{{ $p->tanggal }}</td>
                <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                <td>
                  @if($p->hasil_negosiasi)
                    Rp {{ number_format($p->hasil_negosiasi, 0, ',', '.') }}
                  @else
                    <span class="text-muted">Belum divalidasi</span>
                  @endif
                </td>
                <td>
                  <div class="btn-group" role="group" aria-label="Aksi">
                    <a href="{{ route('penjualans.detail', $p->id_penjualan) }}" class="btn btn-sm btn-primary">Detail</a>
                    <a href="{{ route('penjualans.hapus', $p->id_penjualan) }}" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
                    <a href="{{ route('penjualans.invoice', $p->id_penjualan) }}" target="_blank" class="btn btn-sm btn-success">Cetak</a>
                    @if(!$p->hasil_negosiasi)
                      <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalValidasiHarga{{ $p->id_penjualan }}">Validasi Harga</button>
                    @endif
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahPenjualan" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('penjualans.simpan') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Penjualan</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            <script>
              $(document).ready(function(){
                $('#modalTambahPenjualan').modal('show');
              });
            </script>
          @endif
          <div class="form-group">
            <label>Pengepul</label>
            <select name="id_pengepul" class="form-control select-pengepul" required data-placeholder="Cari nama pengepul...">
              <option value="">-- Pilih Pengepul --</option>
              @foreach($pengepuls as $pengepul)
                <option value="{{ $pengepul->id_pengepul }}" {{ old('id_pengepul') == $pengepul->id_pengepul ? 'selected' : '' }}>{{ $pengepul->nama_pengepul }}</option>
              @endforeach
            </select>
            <small class="text-muted">Ketik nama pengepul untuk mencari</small>
          </div>

          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
          </div>

          <hr>
          <h5>Detail Penjualan</h5>
          <div id="detail-penjualan">
            <div class="row mb-2 detail-item">
              <div class="col-md-5">
                <select name="id_sampah[]" class="form-control select-sampah" required>
                  <option value="">-- Pilih Sampah --</option>
                  @foreach($sampahs as $s)
                    <option value="{{ $s->id_sampah }}">{{ $s->jenis_sampah }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <input type="number" name="berat[]" step="0.01" class="form-control" placeholder="Berat (kg)" required>
              </div>
              <div class="col-md-3">
                <button type="button" class="btn btn-success tambah-barang">+</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

@foreach($penjualans as $p)
  @if(!$p->hasil_negosiasi)
  <div class="modal fade" id="modalValidasiHarga{{ $p->id_penjualan }}" tabindex="-1">
    <div class="modal-dialog">
      <form method="POST" action="{{ route('penjualans.validasiHarga', $p->id_penjualan) }}">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Validasi Harga Negosiasi</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Hasil Negosiasi (Rp)</label>
              <input type="number" name="hasil_negosiasi" class="form-control" min="0" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Simpan</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  @endif
@endforeach


<script>
document.addEventListener('click', function (e) {
  if (e.target && e.target.classList.contains('tambah-barang')) {
    const container = document.getElementById('detail-penjualan');
    const row = document.createElement('div');
    row.className = 'row mb-2 detail-item';
    row.innerHTML = `
      <div class="col-md-5">
        <select name="id_sampah[]" class="form-control select-sampah" required>
          <option value="">-- Pilih Sampah --</option>
          @foreach($sampahs as $s)
            <option value="{{ $s->id_sampah }}">{{ $s->jenis_sampah }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <input type="number" name="berat[]" step="0.01" class="form-control" placeholder="Berat (kg)" required>
      </div>
      <div class="col-md-3">
        <button type="button" class="btn btn-danger" onclick="this.closest('.row').remove()">-</button>
      </div>
    `;
    container.appendChild(row);
  }
});
</script>


<script>
const formPenjualan = document.querySelector('#modalTambahPenjualan form');
const btnSubmit = formPenjualan ? formPenjualan.querySelector('button[type="submit"]') : null;
let pesanStok = null;

function cekStokPenjualan() {
  let valid = true;
  let pesan = '';
  @foreach($sampahs as $s)
    window['stok_{{ $s->id_sampah }}'] = {{ $s->stok ?? (isset($s->stok_rel) ? $s->stok_rel->jumlah : 0) }};
  @endforeach
  const idSampah = Array.from(formPenjualan.querySelectorAll('select[name="id_sampah[]"]')).map(x => x.value);
  const berat = Array.from(formPenjualan.querySelectorAll('input[name="berat[]"]')).map(x => parseFloat(x.value));
  idSampah.forEach((id, i) => {
    if(id && berat[i] > window['stok_' + id]) {
      valid = false;
      pesan += `Stok tidak cukup untuk jenis sampah: ${formPenjualan.querySelectorAll('select[name="id_sampah[]"]')[i].selectedOptions[0].text}\n`;
    }
  });
  if(!pesanStok) {
    pesanStok = document.createElement('div');
    pesanStok.className = 'text-danger font-weight-bold mt-2';
    formPenjualan.querySelector('#detail-penjualan').appendChild(pesanStok);
  }
  pesanStok.textContent = valid ? '' : pesan.trim();
  if(btnSubmit) btnSubmit.disabled = !valid;
}

if(formPenjualan) {
  formPenjualan.addEventListener('input', function(e) {
    if(e.target.name === 'berat[]' || e.target.name === 'id_sampah[]') {
      cekStokPenjualan();
    }
  });
  formPenjualan.addEventListener('submit', function(e) {
    cekStokPenjualan();
    if(btnSubmit && btnSubmit.disabled) {
      e.preventDefault();
    }
  });
  
  $(document).on('shown.bs.modal', '#modalTambahPenjualan', function () {
    cekStokPenjualan();
  });
}
</script>

<script>
$(document).ready(function() {
  $('.select-sampah').select2({
    width: '100%',
    placeholder: '-- Pilih Sampah --'
  });
  $('.select-pengepul').select2({
    width: '100%',
    placeholder: 'Cari nama pengepul...',
    allowClear: true
  });
});
</script>
@endsection