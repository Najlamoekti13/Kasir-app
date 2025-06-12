<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            color: #111827;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .logo {
            width: 100px;
            margin: 0 auto 1rem;
            display: block;
        }

        h1 {
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
        }

        button {
            width: 100%;
            background-color: #2563eb;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #1e40af;
        }

        .links {
            margin-top: 1rem;
            text-align: center;
            font-size: 0.875rem;
        }

        .links a {
            color: #2563eb;
            text-decoration: none;
            margin: 0 0.5rem;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('images/Noah_Logo.svg') }}" alt="Logo" class="logo">

        <h1>Masuk ke Noah Vet Care</h1>

        @if (session('status'))
            <div style="color: green; text-align:center; margin-bottom: 1rem;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="email" name="email" placeholder="Email" required autofocus>
            <input type="password" name="password" placeholder="Kata Sandi" required>

            <button type="submit">Login</button>
        </form>

        <div class="links">
            <a href="{{ route('password.request') }}">Lupa Password?</a> |
            <a href="{{ route('register') }}">Daftar Akun Baru</a>
        </div>
    </div>
</body>
</html>
