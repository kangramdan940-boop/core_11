<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Jasa Emas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Jasa Emas - Admin</a>

        <form action="{{ route('admin.logout') }}" method="POST" class="ms-auto">
            @csrf
            <button class="btn btn-outline-light btn-sm" type="submit">
                Logout
            </button>
        </form>
    </div>
</nav>

<div class="container">
    <div class="alert alert-success">
        Selamat datang, <strong>{{ auth()->user()->name }}</strong> (role: {{ auth()->user()->role }})
    </div>
</div>
</body>
</html>
