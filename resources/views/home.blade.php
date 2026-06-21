<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang &middot; {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #2563eb 0%, #1e3a8a 100%); color: #fff;
        }
        .hero { max-width: 620px; text-align: center; padding: 2rem; }
        .hero .icon { font-size: 4.5rem; }
        .btn-light { font-weight: 600; }
    </style>
</head>
<body>
    <div class="hero">
        <div class="icon mb-3"><i class="bi bi-droplet-half"></i></div>
        <h1 class="fw-bold mb-3">Selamat Datang di {{ config('app.name') }}</h1>
        <p class="fs-5 mb-4 opacity-75">
            Sistem manajemen laundry untuk mencatat transaksi, mengatur rak, mengelola
            data karyawan, dan membuat laporan dengan mudah.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg px-4">
                    <i class="bi bi-speedometer2"></i> Masuk Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">
                    <i class="bi bi-person-plus"></i> Register
                </a>
            @endauth
        </div>
        <p class="mt-5 small opacity-50">&copy; {{ date('Y') }} {{ config('app.name') }}. Khusus Admin.</p>
    </div>
</body>
</html>
