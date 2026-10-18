<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel HTTP Request & Response Demo')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-canvas: #090d16;
            --bg-card: rgba(22, 29, 47, 0.85);
            --bg-card-border: rgba(255, 255, 255, 0.08);
            --bg-input: #0f172a;
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --primary-light: rgba(99, 102, 241, 0.15);
            --accent-cyan: #06b6d4;
            --accent-emerald: #10b981;
            --accent-rose: #f43f5e;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --text-sub: #cbd5e1;
            --font-main: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --radius-md: 12px;
            --radius-lg: 18px;
            --shadow-subtle: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
            --shadow-glow: 0 0 40px -10px rgba(99, 102, 241, 0.25);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--bg-canvas);
            background-image: 
                radial-gradient(circle at 15% 20%, rgba(99, 102, 241, 0.12) 0%, transparent 40%),
                radial-gradient(circle at 85% 80%, rgba(6, 182, 212, 0.10) 0%, transparent 45%);
            background-attachment: fixed;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 2.5rem;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--bg-card-border);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-main);
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: -0.02em;
        }

        .brand-badge {
            background: linear-gradient(135deg, var(--primary), var(--accent-cyan));
            color: #ffffff;
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .nav-link {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--text-main);
            background: var(--primary-light);
        }

        .container {
            width: 100%;
            max-width: 1080px;
            margin: 2.5rem auto;
            padding: 0 1.5rem;
            flex: 1;
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--bg-card-border);
            border-radius: var(--radius-lg);
            padding: 2.5rem;
            box-shadow: var(--shadow-subtle), var(--shadow-glow);
            backdrop-filter: blur(16px);
        }

        .page-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, #ffffff 30%, var(--text-sub) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .page-header p {
            color: var(--text-muted);
            margin-top: 0.5rem;
            font-size: 0.95rem;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.75rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.92rem;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
        }

        .alert-danger {
            background: rgba(244, 63, 94, 0.12);
            border: 1px solid rgba(244, 63, 94, 0.3);
            color: #fb7185;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-sub);
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.85rem 1.15rem;
            background: var(--bg-input);
            border: 1px solid var(--bg-card-border);
            border-radius: var(--radius-md);
            color: var(--text-main);
            font-family: inherit;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
        }

        .form-checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 0.9rem;
            color: var(--text-sub);
            cursor: pointer;
            user-select: none;
        }

        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 0.85rem 1.75rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.6);
        }

        .btn-danger {
            background: rgba(244, 63, 94, 0.15);
            border: 1px solid rgba(244, 63, 94, 0.4);
            color: #fb7185;
        }

        .btn-danger:hover {
            background: var(--accent-rose);
            color: #ffffff;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--bg-card-border);
            color: var(--text-sub);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-main);
        }

        .btn-block {
            width: 100%;
        }

        .pedagogy-box {
            background: rgba(99, 102, 241, 0.08);
            border: 1px dashed rgba(99, 102, 241, 0.35);
            border-radius: var(--radius-md);
            padding: 1.25rem 1.5rem;
            margin-top: 1.75rem;
            font-size: 0.88rem;
        }

        .pedagogy-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            color: #a5b4fc;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .badge-code {
            font-family: var(--font-mono);
            background: rgba(255, 255, 255, 0.08);
            padding: 0.2rem 0.45rem;
            border-radius: 4px;
            font-size: 0.82rem;
            color: #38bdf8;
        }

        .footer {
            text-align: center;
            padding: 2rem;
            font-size: 0.85rem;
            color: var(--text-muted);
            border-top: 1px solid var(--bg-card-border);
            margin-top: auto;
        }
    </style>
</head>
<body>

    <header class="navbar">
        <a href="{{ route('login') }}" class="brand-logo">
            <span class="brand-badge">Request & Response</span>
            <span>Laravel MVC Educator</span>
        </a>
        <nav class="nav-links">
            @auth
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">Login</a>
                <a href="{{ route('signup') }}" class="nav-link {{ request()->routeIs('signup') ? 'active' : '' }}">Sign Up</a>
            @endauth
        </nav>
    </header>

    <main class="container">
        @if (session('status'))
            <div class="alert alert-success">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div style="flex: 1;">
                    <strong>Please review the following errors:</strong>
                    <ul style="margin-left: 1.25rem; margin-top: 0.35rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer">
        Al-Ryan University &bull; Advanced Web Applications &bull; Fourth-Year Curriculum &bull; <code>Illuminate\Http\Request</code> & <code>Response</code> Demo
    </footer>

</body>
</html>
