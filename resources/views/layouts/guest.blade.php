<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') &middot; {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { min-height: 100vh; display: flex; align-items: center; background: #eef2f7; }
        .auth-card { max-width: 420px; width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-card mx-auto">
            <div class="text-center mb-4">
                <i class="bi bi-droplet-half fs-1 text-primary"></i>
                <h3 class="fw-bold mt-2">{{ config('app.name') }}</h3>
            </div>
            <div class="card">
                <div class="card-body p-4">
                    @yield('content')
                </div>
            </div>
            <p class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-decoration-none text-muted">
                    <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                </a>
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
