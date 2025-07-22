<style>
  .nav-divider {
    border-bottom: 1px solid #ccc;
    margin: 10px 0;
  }
</style>

<aside class="main-sidebar sidebar-light-primary elevation-4">
  <a href="/" class="brand-link">
    <img src="{{ asset('lte/dist/img/BankSampahLogo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">BANKSAMPAH</span>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <!-- <div class="image">
        <img src="{{ asset('lte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
      </div> -->
      <div class="info">
        <h5 class="d-block">Admin</h5>
      </div>
    </div>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        
        <li class="nav-item">
          <a href="{{ route('dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dasbor</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('registrasi.index') }}" class="nav-link">
            <i class="nav-icon fas fa-user-plus"></i>
            <p>Data Nasabah</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('layout.transaksi') }}" class="nav-link">
            <i class="nav-icon fas fa-file-invoice-dollar"></i>
            <p>Transaksi Nasabah</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('stok.index') }}" class="nav-link">
            <i class="nav-icon fas fa-boxes"></i>
            <p>Stok Sampah</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('penjualans.index') }}" class="nav-link">
            <i class="nav-icon fas fa-shopping-cart"></i>
            <p>Penjualan</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('penarikan.index') }}" class="nav-link">
            <i class="nav-icon fas fa-hand-holding-usd"></i>
            <p>Penarikan</p>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="{{ route('laporan.saldo') }}" class="nav-link">
            <i class="nav-icon fas fa-wallet"></i>
            <p>Laporan Saldo</p>
          </a>
        </li>

        <li class="nav-divider"></li>

        <li class="nav-item">
          <a href="{{ route('edukasi.index') }}" class="nav-link">
            <i class="nav-icon fas fa-book"></i>
            <p>Edukasi</p>
          </a>
        </li>
        
          <li class="nav-divider"></li>

        <li class="nav-item">
          <a href="{{ route('role.index') }}" class="nav-link">
            <i class="nav-icon fas fa-user-shield"></i>
            <p>Role</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('petugas.index') }}" class="nav-link">
            <i class="nav-icon fas fa-user-tie"></i>
            <p>Petugas</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('sampah.index') }}" class="nav-link">
            <i class="nav-icon fas fa-recycle"></i>
            <p>Sampah</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('pengepul.index') }}" class="nav-link">
            <i class="nav-icon fas fa-truck-loading"></i>
            <p>Pengepul</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>
