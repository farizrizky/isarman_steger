
<ul class="nav nav-secondary">
    <li class="nav-item">
        <a href="/dashboard">
            <i class="fas fa-home"></i>
            <p>Dashboard</p>
        </a>
    </li>
    @if(HUser::userHasOneOrMorePermission(['item', 'stock', 'purchase', 'repair', 'price_list']))
    <li class="nav-section">
        <span class="sidebar-mini-icon">
          <i class="fa fa-ellipsis-h"></i>
        </span>
        <h4 class="text-section">Master</h4>
    </li>
    @endif
    @if(HUser::userHasPermission(['stock']))
    <li class="nav-item">
        <a data-bs-toggle="collapse" href="#scaffolding">
            <i class="fas fa-layer-group"></i>
            <p>Stok</p>
            <span class="caret"></span>
        </a>
        <div class="collapse" id="scaffolding">
            <ul class="nav nav-collapse">
                <li>
                    <a href="/scaffolding/stok">
                        <span class="sub-item">Seluruh Stok</span>
                    </a>
                </li>
                <li>
                    <a href="/scaffolding/stok/tersewa">
                        <span class="sub-item">Stok Tersewa</span>
                    </a>
                </li>
                <li>
                    <a href="/scaffolding/stok/hilang">
                        <span class="sub-item">Stok Hilang</span>
                    </a>
                </li>
                <li>
                    <a href="/scaffolding/stok/rusak">
                        <span class="sub-item">Stok Rusak</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    @endif
    @if(HUser::userHasPermission(['item']))
    <li class="nav-item">
        <a href="/scaffolding/item">
            <i class="far fa-list-alt"></i>
            <p>Item & Set</p>
        </a>
    </li>
    @endif
    @if(HUser::userHasPermission(['purchase']))
    <li class="nav-item">
        <a href="/scaffolding/pembelian">
            <i class="far fa-plus-square"></i>
            <p>Pembelian</p>
        </a>
    </li>
    @endif
    @if(HUser::userHasPermission(['repair']))
    <li class="nav-item">
        <a href="/scaffolding/perbaikan">
            <i class="fas fa-wrench"></i>
            <p>Perbaikan</p>
        </a>
    </li>
    @endif
    @if(HUser::userHasPermission(['price_list']))
    <li class="nav-item">
        <a href="/pdf/daftar-harga">
            <i class="fas fa-print"></i>
            <p>Cetak Daftar Harga</p>
        </a>
    </li>
    @endif
    @if(HUser::userHasPermission(['rent']))
    <li class="nav-section">
        <span class="sidebar-mini-icon">
          <i class="fa fa-ellipsis-h"></i>
        </span>
        <h4 class="text-section">Penyewaan</h4>
    </li>
    <li class="nav-item">
        <a href="/sewa/draft/input">
            <i class="fas fa-edit"></i>
            <p>Buat Draft Penyewaan</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="/sewa/draft">
            <i class="fas fa-list-ul"></i>
            <p>Draft Penyewaan</p>
            <span class="badge badge-danger">{{ HData::getRentTotal('Draft') }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="/sewa/penyewaan/berjalan">
            <i class="fas fa-building"></i>
            <p>Penyewaan Berjalan</p>
             <span class="badge badge-danger">{{ HData::getRentTotal('Berjalan') }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="/sewa/penyewaan/selesai">
            <i class="fas fa-calendar-check"></i>
            <p>Penyewaan Selesai</p>
             <span class="badge badge-danger">{{ HData::getRentTotal('Selesai') }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="/sewa/penyewa">
            <i class="fas fa-users"></i>
            <p>Penyewa</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="/sewa/pembukuan-sewa">
            <i class="fas fa-book"></i>
            <p>Buku Besar Penyewaan</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="/sewa/grafik">
            <i class="fas fa-chart-line"></i>
            <p>Grafik Penyewaan</p>
        </a>
    </li>
    @endif
    @if(HUser::userHasPermission(['finance']))
    <li class="nav-section">
        <span class="sidebar-mini-icon">
          <i class="fa fa-ellipsis-h"></i>
        </span>
        <h4 class="text-section">Keuangan</h4>
    </li>
    <li class="nav-item">
        <a href="/keuangan/pengeluaran">
            <i class="fas fa-hand-holding-usd"></i>
            <p>Pengeluaran</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="/keuangan/arus-kas">
            <i class="fas fa-balance-scale"></i>
            <p>Buku Besar Arus Kas</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="/keuangan/arus-kas/pemasukan">
            <i class="fas fa-plus-square"></i>
            <p>Buku Besar Pemasukan</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="/keuangan/arus-kas/pengeluaran">
            <i class="fas fa-minus-square"></i>
            <p>Buku Besar Pengeluaran</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="/keuangan/grafik">
            <i class="fas fa-chart-line"></i>
            <p>Grafik Keuangan</p>
        </a>
    </li>
    @endif
    @if(HUser::userHasPermission(['user']))
     <li class="nav-section">
        <span class="sidebar-mini-icon">
          <i class="fa fa-ellipsis-h"></i>
        </span>
        <h4 class="text-section">User</h4>
    </li>
    <li class="nav-item">
        <a data-bs-toggle="collapse" href="#user">
            <i class="fas fa-users"></i>
            <p>User</p>
            <span class="caret"></span>
        </a>
        <div class="collapse" id="user">
            <ul class="nav nav-collapse">
                <li>
                    <a href="/user">
                        <span class="sub-item">Daftar User</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    @endif
</ul>
