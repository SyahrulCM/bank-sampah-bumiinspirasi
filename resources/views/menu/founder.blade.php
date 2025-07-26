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
      <div class="info">
        <h5 class="d-block">Operasional</h5>
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

        <li class="nav-divider"></li>

        <li class="nav-item">
          <a href="{{ route('laporan.saldo') }}" class="nav-link">
            <i class="nav-icon fas fa-wallet"></i>
            <p>Laporan Saldo</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>
