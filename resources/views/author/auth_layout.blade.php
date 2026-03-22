<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Author')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        :root {
            --bg: #0b1220;
            --card: rgba(255, 255, 255, 0.08);
            --card-border: rgba(255, 255, 255, 0.16);
            --text: #e5e7eb;
            --muted: rgba(229, 231, 235, 0.7);
            --accent: #e63946;
            --accent-2: #7c3aed;
            --shadow: 0 18px 50px rgba(0,0,0,0.35);
        }

        html, body { height: 100%; }
        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            color: var(--text);
            background:
                radial-gradient(1100px 650px at 15% 15%, rgba(124, 58, 237, 0.35), transparent 60%),
                radial-gradient(1100px 650px at 85% 20%, rgba(230, 57, 70, 0.30), transparent 55%),
                radial-gradient(900px 600px at 60% 85%, rgba(14, 165, 233, 0.20), transparent 60%),
                var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 16px;
        }

        .auth-shell { width: 100%; max-width: 420px; }

        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 14px;
            letter-spacing: 0.2px;
        }
        .brand-badge {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(230,57,70,1), rgba(124,58,237,1));
            box-shadow: 0 10px 30px rgba(0,0,0,0.35);
            display: grid;
            place-items: center;
            font-weight: 800;
            color: white;
        }
        .brand h1 { font-size: 18px; margin: 0; font-weight: 800; }
        .brand p { margin: 0; font-size: 12px; color: var(--muted); }

        .auth-card {
            background: var(--card);
            border: 1px solid var(--card-border);
            backdrop-filter: blur(12px);
            border-radius: 18px;
            box-shadow: var(--shadow);
            padding: 22px;
        }

        .auth-title { font-size: 20px; font-weight: 800; margin: 4px 0 6px; }
        .auth-subtitle { font-size: 13px; color: var(--muted); margin: 0 0 16px; }

        label { font-size: 12px; color: var(--muted); font-weight: 600; }
        .form-control {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.16);
            color: var(--text);
            border-radius: 12px;
            padding: 12px 14px;
            height: auto;
        }
        .form-control:focus {
            background: rgba(255,255,255,0.08);
            color: var(--text);
            border-color: rgba(230,57,70,0.55);
            box-shadow: 0 0 0 0.2rem rgba(230,57,70,0.15);
        }

        .btn-accent {
            background: linear-gradient(135deg, rgba(230,57,70,1), rgba(124,58,237,1));
            border: 0;
            font-weight: 700;
            border-radius: 12px;
            padding: 12px 14px;
            box-shadow: 0 16px 35px rgba(0,0,0,0.30);
        }
        .btn-accent:hover { filter: brightness(1.03); }

        .help-links a { color: rgba(229,231,235,0.85); text-decoration: none; font-weight: 600; }
        .help-links a:hover { color: white; text-decoration: underline; }

        .field-wrap { position: relative; }
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 39px;
            color: rgba(229,231,235,0.75);
            cursor: pointer;
            padding: 6px;
            border-radius: 10px;
        }
        .toggle-password:hover { background: rgba(255,255,255,0.06); color: white; }
    </style>

    @stack('styles')
</head>
<body>
    <div class="auth-shell">
        <div class="brand">
            <div class="brand-badge">R</div>
            <div>
                <h1>Rudra Stories</h1>
                <p>Author Panel</p>
            </div>
        </div>

        <div class="auth-card">
            @yield('content')
        </div>

        <div class="text-center mt-3" style="color: rgba(229,231,235,0.65); font-size: 12px;">
            © {{ date('Y') }} Rudra Stories
        </div>
    </div>

    @stack('scripts')
</body>
</html>

