<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ABZ test App')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Ensures the footer sticks at the bottom */
        .footer {
            margin-top: auto;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">ABZ test App</a>
            <div id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('positions') ? 'active' : '' }}" href="{{ url('/positions') }}">Positions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('users') ? 'active' : '' }}" href="{{ url('/users') }}">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('token') ? 'active' : '' }}" href="{{ url('/token') }}">Token</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <h1>
        <p class="text-center bg-dark p-3 text-white fw-bold">ABZ test App</p>
    </h1>

    <div class="container mt-4 flex-grow-1">
        @yield('content')
    </div>

    <footer class="footer text-center py-3 bg-light">
        <p>&copy; {{ date('Y') }} ABZ test App. All rights reserved.</p>
    </footer>

</body>
</html>
