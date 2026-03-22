<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Author Panel')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        :root {
            --bg: #0b1220;
            --panel: rgba(255, 255, 255, 0.06);
            --panel-border: rgba(255, 255, 255, 0.12);
            --card: rgba(255, 255, 255, 0.08);
            --card-border: rgba(255, 255, 255, 0.12);
            --text: #e5e7eb;
            --muted: rgba(229, 231, 235, 0.70);
            --accent: #e63946;
            --accent-2: #7c3aed;
            --shadow: 0 18px 50px rgba(0,0,0,0.35);
        }

        html, body { height: 100%; }
        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background:
                radial-gradient(1100px 700px at 10% 0%, rgba(124, 58, 237, 0.22), transparent 60%),
                radial-gradient(1100px 700px at 90% 10%, rgba(230, 57, 70, 0.18), transparent 55%),
                radial-gradient(900px 650px at 60% 100%, rgba(14, 165, 233, 0.12), transparent 60%),
                var(--bg);
            color: var(--text);
        }

        .ap-shell { display: flex; min-height: 100vh; }

        .ap-sidebar {
            width: 260px;
            padding: 18px;
            position: sticky;
            top: 0;
            height: 100vh;
            background: rgba(7, 10, 18, 0.70);
            border-right: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
        }

        .ap-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 12px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 12px;
        }
        .ap-badge {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(230,57,70,1), rgba(124,58,237,1));
            display: grid;
            place-items: center;
            font-weight: 900;
            color: white;
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        }
        .ap-brand h1 { font-size: 15px; margin: 0; font-weight: 900; }
        .ap-brand p { margin: 0; color: var(--muted); font-size: 12px; }

        .ap-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            margin: 6px 0;
            border-radius: 14px;
            text-decoration: none;
            color: rgba(229,231,235,0.88);
            border: 1px solid transparent;
        }
        .ap-nav a i { width: 18px; text-align: center; opacity: 0.95; }
        .ap-nav a:hover {
            background: rgba(255,255,255,0.06);
            border-color: rgba(255,255,255,0.10);
            color: white;
        }
        .ap-nav a.active {
            background: linear-gradient(135deg, rgba(230,57,70,0.18), rgba(124,58,237,0.14));
            border-color: rgba(230,57,70,0.25);
            color: white;
        }

        .ap-content { flex: 1; padding: 18px 18px 28px; }

        .ap-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 14px 16px;
            background: var(--panel);
            border: 1px solid var(--panel-border);
            border-radius: 18px;
            box-shadow: var(--shadow);
        }
        .ap-title { margin: 0; font-size: 16px; font-weight: 900; }
        .ap-subtitle { margin: 0; color: var(--muted); font-size: 12px; }

        .ap-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 14px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.10);
        }
        .ap-avatar {
            width: 34px;
            height: 34px;
            border-radius: 14px;
            background: rgba(255,255,255,0.10);
            display: grid;
            place-items: center;
            font-weight: 900;
        }

        .ap-main { margin-top: 16px; }

        .ap-card {
            background: var(--card);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            box-shadow: var(--shadow);
        }

        .btn-accent {
            background: linear-gradient(135deg, rgba(230,57,70,1), rgba(124,58,237,1));
            border: 0;
            color: white;
            font-weight: 800;
            border-radius: 14px;
            padding: 10px 14px;
        }
        .btn-accent:hover { filter: brightness(1.03); color: white; }

        .ap-table { color: rgba(229,231,235,0.92); }
        .ap-table thead th {
            border-top: 0;
            border-bottom: 1px solid rgba(255,255,255,0.10);
            color: rgba(229,231,235,0.70);
            font-size: 12px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }
        .ap-table td { border-top: 1px solid rgba(255,255,255,0.08); vertical-align: middle; }
        .ap-badge-pill {
            padding: 5px 10px;
            border-radius: 999px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.14);
            color: rgba(229,231,235,0.90);
            font-weight: 700;
            font-size: 12px;
            display: inline-block;
        }

        .form-control {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.16);
            color: var(--text);
            border-radius: 14px;
            padding: 11px 12px;
            height: auto;
        }
        .form-control:focus {
            background: rgba(255,255,255,0.08);
            color: var(--text);
            border-color: rgba(230,57,70,0.55);
            box-shadow: 0 0 0 0.2rem rgba(230,57,70,0.12);
        }

        @media (max-width: 991px) {
            .ap-sidebar { display: none; }
            .ap-content { padding: 14px; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="ap-shell">
        <aside class="ap-sidebar">
            <div class="ap-brand">
                <div class="ap-badge">R</div>
                <div>
                    <h1>Rudra Stories</h1>
                    <p>Author Panel</p>
                </div>
            </div>

            <nav class="ap-nav">
                <a href="{{ route('author.dashboard') }}" class="{{ request()->routeIs('author.dashboard') ? 'active' : '' }}">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
                <a href="{{ route('author.stories.index') }}" class="{{ request()->routeIs('author.stories.*') ? 'active' : '' }}">
                    <i class="fa fa-book"></i> My Stories
                </a>
                <a href="{{ route('author.stories.create') }}">
                    <i class="fa fa-pencil-square"></i> Write New Story
                </a>
                <a href="{{ route('author.profile') }}" class="{{ request()->routeIs('author.profile') ? 'active' : '' }}">
                    <i class="fa fa-user"></i> Profile
                </a>

                <a href="/" target="_blank" rel="noreferrer">
                    <i class="fa fa-external-link"></i> View Site
                </a>

                <a href="#" onclick="event.preventDefault(); document.getElementById('author-logout-form').submit();">
                    <i class="fa fa-sign-out"></i> Logout
                </a>
                <form id="author-logout-form" action="{{ route('author.logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </nav>
        </aside>

        <main class="ap-content">
            <div class="ap-topbar">
                <div>
                    <p class="ap-subtitle mb-1">@yield('subtitle', 'Welcome')</p>
                    <h2 class="ap-title">@yield('heading', 'Author Panel')</h2>
                </div>

                @php($author = Auth::guard('author')->user())
                <div class="ap-user">
                    @if (!empty($author->profile_pic))
                        <img src="/authorProfile/{{ $author->profile_pic }}" alt="Avatar" style="width:34px; height:34px; border-radius: 14px; object-fit: cover; border: 1px solid rgba(255,255,255,0.14);">
                    @else
                        <div class="ap-avatar">{{ strtoupper(substr($author->name ?? 'A', 0, 1)) }}</div>
                    @endif
                    <div style="line-height: 1.1;">
                        <div style="font-weight: 800; font-size: 13px;">{{ $author->name }}</div>
                        <div style="color: var(--muted); font-size: 12px;">{{ $author->email }}</div>
                    </div>
                </div>
            </div>

            <div class="ap-main">
                @if (session('success'))
                    <div class="alert alert-success" style="background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.28); color: #dcfce7; border-radius: 16px;">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger" style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.28); color: #fee2e2; border-radius: 16px;">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
