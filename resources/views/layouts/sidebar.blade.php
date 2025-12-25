<style>
.sidebar {
    width: 240px;
    height: 100vh;
    background: linear-gradient(180deg, #1E3A8A, #2563EB);
    color: #fff;
    position: fixed;
    left: 0;
    top: 0;
    padding: 1.5rem 1rem;
    display: flex;
    flex-direction: column;
    box-shadow: 2px 0 10px rgba(0,0,0,0.15);
    font-family: 'Segoe UI', sans-serif;
}

/* BRAND  */
.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.18);
}

/* Logo Dinsos */
.sidebar-logo {
    width: 44px;
    height: 44px;
    object-fit: contain;
    background-color: #ffffff;
    border-radius: 12px;
    padding: 4px;
}

.sidebar h2 {
    font-size: 1.05rem;
    font-weight: 600;
    margin: 0;
    text-align: left;
    color: #F9FAFB;
    letter-spacing: 0.3px;
}


.sidebar a {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #E5E7EB;
    text-decoration: none;
    padding: 10px 14px;
    border-radius: 8px;
    margin-bottom: 6px;
    transition: all 0.25s ease;
    font-size: 0.95rem;
}

.sidebar a i {
    font-size: 1rem;
    width: 20px;
    text-align: center;
    opacity: 0.9;
}

.sidebar a:hover {
    background-color: rgba(255,255,255,0.1);
    color: #FFFFFF;
    transform: translateX(4px);
}

.sidebar > a.active {
    background-color: #3B82F6;
    color: #FFFFFF;
    font-weight: 600;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}

.sidebar .submenu .submenu-items a.active {
    background-color: #2563EB;    
    color: #FFFFFF;
    font-weight: 600;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    border-radius: 10px;
}

.sidebar > a.logout {
    margin-top: auto;
    background-color: rgba(255, 255, 255, 0.1);
    border-top: 1px solid rgba(255,255,255,0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        position: relative;
        width: 100%;
        height: auto;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        padding: 1rem;
    }

    .sidebar a {
        margin: 4px;
        font-size: 0.9rem;
        padding: 8px 12px;
    }

    .sidebar h2 {
        display: none;
    }
}

.sidebar details.submenu { margin-bottom: 6px; }
.sidebar details.submenu > summary {
    list-style: none;
    cursor: pointer;
    display: flex; align-items: center; gap: 12px;
    color: #E5E7EB; padding: 10px 14px; border-radius: 8px;
    transition: all .25s ease; font-size: .95rem;
}
.sidebar details.submenu > summary::-webkit-details-marker { display: none; }
.sidebar details.submenu > summary:hover {
    background-color: rgba(255,255,255,0.1); color: #FFFFFF; transform: translateX(4px);
}

.sidebar details.submenu > summary.active {
    background-color: #3B82F6; color: #FFFFFF; font-weight: 600;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}

.sidebar .submenu .submenu-items { display: flex; flex-direction: column; margin-top: 6px; margin-left: 8px; }
.sidebar .submenu .submenu-items a { padding: 8px 14px; padding-left: 36px; border-radius: 8px; font-size: 0.92rem; }
.sidebar .submenu .submenu-items a i { width: 20px; text-align: center; opacity: .9; }

.sidebar details.submenu > summary .chev { margin-left: auto; opacity: .85; transition: transform .2s ease; }
.sidebar details.submenu[open] > summary .chev { transform: rotate(90deg); }

</style>

<div class="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('images/logo_dinsos2.png') }}" alt="Logo Dinas Sosial" class="sidebar-logo">
        <h2>Layanan kedaruratan</h2>
    </div>

    {{-- ==== Menu untuk Admin ==== --}}
    @if (session('role') === 'Admin')
        <a href="{{ route('dashboard.admin') }}" class="{{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i> Dashboard
        </a>
        <a href="{{ url('/manajemen-pengguna') }}" class="{{ request()->is('manajemen-pengguna*') ? 'active' : '' }}">
            <i class="fa-solid fa-users-gear"></i> Manajemen Pengguna
        </a>
        <a href="{{ route('penerima.index') }}" class="{{ request()->routeIs('penerima.index') ? 'active' : '' }}">
            <i class="fa-solid fa-box-archive"></i> Manajemen Data
        </a>

        {{-- ===== Sistem Pakar ===== --}}
        <details class="submenu" @if(request()->routeIs('sp.*')) open @endif>
        <summary class="{{ request()->routeIs('sp.*') ? 'active' : '' }}">
            <i class="fa-solid fa-brain"></i> Sistem Pakar
            <i class="fa-solid fa-chevron-right chev"></i>
        </summary>
        <div class="submenu-items">
            <a href="{{ route('sp.syarat.index') }}" class="{{ request()->routeIs('sp.syarat.*') ? 'active' : '' }}">
            <i class="fa-solid fa-list-check"></i> Data Syarat
            </a>
            <a href="{{ route('sp.aturan.index') }}" class="{{ request()->routeIs('sp.aturan.*') ? 'active' : '' }}">
            <i class="fa-solid fa-diagram-project"></i> Data Aturan
            </a>
            <a href="{{ route('sp.kelayakan.index') }}" class="{{ request()->routeIs('sp.kelayakan.*') ? 'active' : '' }}">
            <i class="fa-solid fa-square-check"></i> Cek Kelayakan
            </a>
        </div>
        </details>
        


        <a href="{{ url('/peta-distribusi') }}" class="{{ request()->is('peta-distribusi*') ? 'active' : '' }}">
            <i class="fa-solid fa-map-location-dot"></i> Peta Distribusi
        </a>
        <a href="{{ url('/laporan') }}" class="{{ request()->is('laporan*') ? 'active' : '' }}">
            <i class="fa-solid fa-file-lines"></i> Laporan
        </a>
        <a href="{{ route('logout') }}" class="logout">
            <i class="fa-solid fa-right-from-bracket"></i> Keluar
        </a>


    {{-- ==== Menu untuk Staf ==== --}}
    @elseif (session('role') === 'Staf')
        <a href="{{ route('dashboard.staf') }}" class="{{ request()->routeIs('dashboard.staf') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i> Dashboard
        </a>
        <a href="{{ route('penerima.index') }}" class="{{ request()->is('data-bansos*') ? 'active' : '' }}">
            <i class="fa-solid fa-box-archive"></i> Manajemen Data
        </a>

        <a href="{{ route('sp.kelayakan.index') }}" class="{{ request()->routeIs('sp.kelayakan.*') ? 'active' : '' }}">
            <i class="fa-solid fa-square-check"></i> Cek Kelayakan
        </a>

        <a href="{{ url('/peta-distribusi') }}" class="{{ request()->is('peta-distribusi*') ? 'active' : '' }}">
            <i class="fa-solid fa-map-location-dot"></i> Peta Distribusi
        </a>
        <a href="{{ url('/laporan') }}" class="{{ request()->is('laporan*') ? 'active' : '' }}">
            <i class="fa-solid fa-file-lines"></i> Laporan
        </a>
        <a href="{{ route('logout') }}" class="logout">
            <i class="fa-solid fa-right-from-bracket"></i> Keluar
        </a>

    {{-- ==== Menu untuk Pimpinan ==== --}}
    @elseif (session('role') === 'Pimpinan')
        <a href="{{ route('dashboard.pimpinan') }}" class="{{ request()->routeIs('dashboard.pimpinan') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i> Dashboard
        </a>
        <a href="{{ route('penerima.index') }}" class="{{ request()->is('data-bansos*') ? 'active' : '' }}">
            <i class="fa-solid fa-box-archive"></i> Manajemen Data
        </a>

        <a href="{{ route('sp.kelayakan.index') }}" class="{{ request()->routeIs('sp.kelayakan.*') ? 'active' : '' }}">
            <i class="fa-solid fa-square-check"></i> Cek Kelayakan
        </a>

        <a href="{{ url('/peta-distribusi') }}" class="{{ request()->is('peta-distribusi*') ? 'active' : '' }}">
            <i class="fa-solid fa-map-location-dot"></i> Peta Distribusi
        </a>
        <a href="{{ url('/laporan') }}" class="{{ request()->is('laporan*') ? 'active' : '' }}">
            <i class="fa-solid fa-file-lines"></i> Laporan
        </a>
        <a href="{{ route('logout') }}" class="logout">
            <i class="fa-solid fa-right-from-bracket"></i> Keluar
        </a>
    @endif
</div>
