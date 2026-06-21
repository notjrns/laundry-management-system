<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') &middot; {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background: #f4f6f9; }
        .sidebar {
            width: 250px; min-height: 100vh; background: #1e293b; color: #cbd5e1;
            position: fixed; top: 0; left: 0; overflow-y: auto;
        }
        .sidebar .brand { color: #fff; font-weight: 700; font-size: 1.15rem; }
        .sidebar a { color: #cbd5e1; text-decoration: none; display: flex; align-items: center;
            gap: .65rem; padding: .65rem 1.25rem; border-radius: .4rem; margin: .15rem .6rem; }
        .sidebar a:hover { background: #334155; color: #fff; }
        .sidebar a.active { background: #2563eb; color: #fff; }
        .content { margin-left: 250px; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; }
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        @media (max-width: 768px) {
            .sidebar { position: static; width: 100%; min-height: auto; }
            .content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="sidebar py-3">
        <div class="px-3 mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-droplet-half fs-4 text-info"></i>
            <span class="brand">{{ config('app.name') }}</span>
        </div>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="{{ route('transaksi.create') }}" class="{{ request()->routeIs('transaksi.create') ? 'active' : '' }}"><i class="bi bi-plus-circle"></i> Tambah Transaksi</a>
        <a href="{{ route('transaksi.index') }}" class="{{ request()->routeIs('transaksi.index','transaksi.show','transaksi.edit') ? 'active' : '' }}"><i class="bi bi-receipt"></i> Data Transaksi</a>
        <a href="{{ route('rak.index') }}" class="{{ request()->routeIs('rak.*','kolom.*') ? 'active' : '' }}"><i class="bi bi-grid-3x3-gap"></i> Rak</a>
        <a href="{{ route('layanan.index') }}" class="{{ request()->routeIs('layanan.*') ? 'active' : '' }}"><i class="bi bi-tags"></i> Atur Layanan</a>
        <a href="{{ route('karyawan.index') }}" class="{{ request()->routeIs('karyawan.*') ? 'active' : '' }}"><i class="bi bi-people"></i> Data Karyawan</a>
        <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}"><i class="bi bi-file-earmark-bar-graph"></i> Laporan</a>
    </nav>

    <div class="content">
        <div class="topbar d-flex justify-content-between align-items-center px-4 py-2">
            <h5 class="mb-0">@yield('title', 'Dashboard')</h5>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-dark dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5"></i>
                    <span>{{ auth()->user()->name ?? 'Admin' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <main class="p-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
