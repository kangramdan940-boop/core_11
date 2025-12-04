<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Jasa Emas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CDN -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #0f172a;
            color: #e5e7eb;
        }
        .login-card {
            max-width: 420px;
            margin: 60px auto;
        }
        .brand-title {
            font-weight: 700;
            letter-spacing: .05em;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="login-card card shadow border-0">
        <div class="card-body p-4">
            <h4 class="brand-title text-center mb-1">Jasa Emas</h4>
            <p class="text-center text-muted mb-4">Login Admin</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li style="font-size: 0.85rem">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email Admin</label>
                    <input type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="password"
                           name="password"
                           required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>

                <button type="submit" class="btn btn-warning w-100">
                    Masuk sebagai Admin
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
