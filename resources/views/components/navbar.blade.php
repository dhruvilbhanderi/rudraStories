@props([
    'css' => [],
    'nav' => [],
    'desc' => '',
    'key' => '',
    // Logged-in user profile image filename (optional)
    'data' => null,
])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="{{ $desc }}">
    <meta name="keywords" content="{{ $key }}">
    <meta name="author" content="Sutex Team">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="icon" href="{{ asset('/webicons/weblogo.png') }}" type="image/png" sizes="25x25">

    @foreach ($css as $item)
        <link rel="stylesheet" href="{{ asset('css/' . $item . '.css') }}">
    @endforeach

    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    @foreach ($nav as $ite)
        <script src="{{ asset('js/' . $ite . '.js') }}"></script>
    @endforeach
    <title>Rudra Stories</title>

    <script>
        function loader() {
            if (document.readyState == "complete") {
                document.querySelector("body").style.visibility = "visible";
                document.getElementById("loader").style.visibility = "hidden";
            } else {
                document.getElementById("loader").style.display = "visible";
                document.querySelector("body").style.visibility = "hidden";
            }
        };

        window.setInterval(function() {
            var time = document.getElementById('time');
            if(time) {
                const d = new Date();
                let hour = d.getHours();
                let min = d.getMinutes();
                let sec = d.getSeconds();
                let fr = "AM";
                if (hour >= 12) {
                    fr = "PM";
                    if(hour > 12) hour = hour - 12;
                }
                if(hour == 0) hour = 12;
                if(hour < 10) hour = '0' + hour;
                if (min < 10) min = '0' + min;
                if (sec < 10) sec = '0' + sec;
                time.innerHTML = hour + ":" + min + ":" + sec + " " + fr;
            }
        }, 1000);
    </script>
    <style>
        .loader {
            position: fixed; width: 100%; height: 100vh;
            z-index: 9999; display: flex; justify-content: center;
            align-items: center; background: #ffffff;
        }

        /* Layout Adjustments depending on Login State */
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; padding: 0; }

        .main-content-wrapper {
            transition: all 0.3s ease;
        }

        /* Variables for Premium Look */
        :root {
            --primary: #1d3557;
            --secondary: #457b9d;
            --accent: #e63946;
            --light-bg: #f8fafc;
            --white: #ffffff;
            --text-dark: #0f172a;
            --text-light: #64748b;
        }
    </style>
</head>

<body onload="loader()">

    <div class="loader" id="loader">
        <div class="ldr">
            <i class="fa fa-spinner fa-spin" style="font-size:60px;color:var(--accent);"></i>
        </div>
    </div>

    @if (session()->has(['usnm', 'loginstat']))
        <!-- ================= LOGGED IN USER SIDEBAR LAYOUT ================= -->
        <style>
            .premium-topbar {
                position: fixed; top: 0; left: 260px; right: 0; height: 70px;
                background: rgba(255,255,255,0.9); backdrop-filter: blur(10px);
                border-bottom: 1px solid #e2e8f0; display: flex;
                justify-content: flex-end; align-items: center; padding: 0 24px; z-index: 100;
            }
            .premium-topbar .right-actions { display: flex; align-items: center; gap: 16px; }

            .premium-sidebar {
                position: fixed; top: 0; left: 0; bottom: 0; width: 260px;
                background: linear-gradient(180deg, var(--primary) 0%, #0f172a 100%);
                color: var(--white); z-index: 101; display: flex; flex-direction: column;
                box-shadow: 4px 0 15px rgba(0,0,0,0.05); overflow-y: auto;
            }
            .premium-sidebar .sidebar-logo {
                padding: 24px; display: flex; align-items: center; gap: 12px;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            .premium-sidebar .sidebar-logo img { width: 40px; height: 40px; }
            .premium-sidebar .sidebar-logo span { font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 700; color: #fff;}

            .premium-sidebar .nav-links { padding: 24px 16px; display: flex; flex-direction: column; gap: 8px; flex: 1; }
            .premium-sidebar .nav-links a {
                display: flex; align-items: center; gap: 12px; padding: 12px 16px;
                text-decoration: none; color: #cbd5e1; border-radius: 12px; font-weight: 500;
                transition: all 0.2s;
            }
            .premium-sidebar .nav-links a:hover, .premium-sidebar .nav-links a.active {
                background: rgba(255,255,255,0.1); color: var(--white); transform: translateX(4px);
            }
            .premium-sidebar .nav-links a i { font-size: 18px; width: 24px; text-align: center; }

            .main-content-wrapper { margin-left: 260px; padding-top: 70px; min-height: 100vh; }

            .proflu img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; }
            .logout-btn { background: #ef4444; color: #fff; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: 0.2s; }
            .logout-btn:hover { background: #dc2626; }

            @media (max-width: 900px) {
                .premium-sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
                .main-content-wrapper { margin-left: 0; }
                .premium-topbar { left: 0; justify-content: space-between; }
                .mobile-toggle { display: block; font-size: 24px; color: var(--primary); background:none; border:none; cursor:pointer;}
            }
            @media (min-width: 901px) { .mobile-toggle { display: none; } }
        </style>

        <div class="premium-sidebar" id="sidebar">
            <div class="sidebar-logo">
                <img src="{{ asset('webicons/weblogo.png') }}" alt="Logo">
                <span>Rudra Stories</span>
            </div>
            <div class="nav-links">
                <a href="/"><i class="fa fa-home"></i> Home</a>
                <a href="/all_stories"><i class="fa fa-book"></i> Stories</a>
                <a href="/category"><i class="fa fa-th-large"></i> Categories</a>
                <a href="/books"><i class="fa fa-shopping-bag"></i> Buy Books</a>
                <a href="/cart"><i class="fa fa-shopping-cart"></i> Cart</a>
                <a href="/my-orders"><i class="fa fa-list-alt"></i> My Orders</a>
                <a href="/my-library"><i class="fa fa-bookmark"></i> My Library</a>
                <a href="/books/resale"><i class="fa fa-recycle"></i> Resale Market</a>
                <a href="/support-chat"><i class="fa fa-comments"></i> Support Chat</a>
                <a href="/about_me"><i class="fa fa-info-circle"></i> About Us</a>
            </div>
        </div>

        <div class="premium-topbar">
            <button class="mobile-toggle" onclick="document.getElementById('sidebar').style.transform = 'translateX(0)';"><i class="fa fa-bars"></i></button>
            <div class="right-actions">
                <div id="time" style="color: var(--text-light); font-weight: 500; font-size: 14px; margin-right: 12px;"></div>
                <a class="proflu" href="/profile">
                    @if ($data != null)
                        <img src="{{ asset('userProfile/' . $data) }}" alt="Profile">
                    @else
                        <img src="{{ asset('webicons/signupuser.png') }}" alt="Profile">
                    @endif
                </a>
                <a class="logout-btn" href="#" onclick="showUserLogoutDialog(event)">Logout</a>
            </div>
        </div>

        <!-- Modern Logout Modal -->
        <style>
        .user-logout-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
            z-index: 9999; display: none; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.3s ease;
        }
        .user-logout-modal {
            background: #ffffff; border-radius: 20px; padding: 32px; width: 90%; max-width: 400px;
            text-align: center; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: scale(0.95) translateY(10px); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .user-logout-overlay.show { display: flex; opacity: 1; }
        .user-logout-overlay.show .user-logout-modal { transform: scale(1) translateY(0); }
        .ulm-icon {
            width: 64px; height: 64px; background: #fee2e2; color: #ef4444; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 20px;
        }
        .ulm-title { font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 8px; font-family: 'Inter', sans-serif; }
        .ulm-desc { font-size: 15px; color: #64748b; margin-bottom: 30px; line-height: 1.5; font-family: 'Inter', sans-serif; }
        .ulm-actions { display: flex; gap: 12px; }
        .ulm-btn {
            flex: 1; padding: 12px; border-radius: 12px; font-size: 15px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; font-family: 'Inter', sans-serif;
            text-decoration: none;
        }
        .ulm-cancel { background: #f1f5f9; color: #475569; }
        .ulm-cancel:hover { background: #e2e8f0; color: #0f172a; }
        .ulm-confirm { background: #ef4444; color: #ffffff; display: flex; align-items: center; justify-content: center; }
        .ulm-confirm:hover { background: #dc2626; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); color: #fff; }
        </style>

        <div class="user-logout-overlay" id="userLogoutOverlay">
            <div class="user-logout-modal">
                <div class="ulm-icon"><i class="fa fa-sign-out"></i></div>
                <div class="ulm-title">Secure Logout</div>
                <div class="ulm-desc">Are you sure you want to log out of your account?</div>
                <div class="ulm-actions">
                    <button class="ulm-btn ulm-cancel" onclick="closeUserLogoutDialog(); event.stopPropagation();">Cancel</button>
                    <a class="ulm-btn ulm-confirm" href="/Log_Out">Yes, Log out</a>
                </div>
            </div>
        </div>

        <script>
        function showUserLogoutDialog(e) {
            if (e) e.preventDefault();
            const overlay = document.getElementById('userLogoutOverlay');
            overlay.style.display = 'flex';
            setTimeout(() => overlay.classList.add('show'), 10);
        }
        function closeUserLogoutDialog() {
            const overlay = document.getElementById('userLogoutOverlay');
            overlay.classList.remove('show');
            setTimeout(() => overlay.style.display = 'none', 300);
        }
        </script>

        <!-- Wrap main content so it sits beside sidebar -->
        <div class="main-content-wrapper" onclick="if(window.innerWidth<=900) { document.getElementById('sidebar').style.transform='translateX(-100%)'; }">

    @else
        <!-- ================= GUEST HORIZONTAL TOP NAVBAR LAYOUT ================= -->
        <style>
            .guest-navbar {
                display: flex; align-items: center; justify-content: space-between;
                padding: 0 40px; height: 80px; background: rgba(255,255,255,0.95);
                backdrop-filter: blur(10px); box-shadow: 0 4px 20px rgba(0,0,0,0.05);
                position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            }
            .guest-navbar .logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
            .guest-navbar .logo img { width: 45px; height: 45px; }
            .guest-navbar .logo span { font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 700; color: var(--primary); }

            .guest-navbar .nav-links { display: flex; gap: 32px; align-items: center; }
            .guest-navbar .nav-links a {
                text-decoration: none; color: var(--text-dark); font-weight: 600; font-size: 15px;
                position: relative; padding: 6px 0; transition: color 0.2s;
            }
            .guest-navbar .nav-links a:hover { color: var(--secondary); }
            .guest-navbar .nav-links a::after {
                content: ''; position: absolute; bottom: 0; left: 0; width: 0%; height: 2px;
                background: var(--secondary); transition: width 0.3s;
            }
            .guest-navbar .nav-links a:hover::after { width: 100%; }

            .guest-navbar .actions { display: flex; gap: 16px; align-items: center; }
            .btn-outline {
                border: 2px solid var(--primary); color: var(--primary); padding: 8px 20px;
                border-radius: 20px; font-weight: 600; text-decoration: none; transition: 0.2s;
            }
            .btn-outline:hover { background: var(--primary); color: #fff; }

            .btn-fill {
                background: var(--primary); color: #fff; padding: 10px 24px;
                border-radius: 20px; font-weight: 600; text-decoration: none; transition: 0.2s;
            }
            .btn-fill:hover { background: #0f172a; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(15,23,42,0.2); }

            .main-content-wrapper { padding-top: 80px; min-height: 100vh; }

            .mobile-menu-btn { display: none; font-size: 24px; color: var(--primary); background:none; border:none; cursor:pointer;}

            @media (max-width: 900px) {
                .guest-navbar .nav-links { display: none; }
                .guest-navbar .actions { display: none; }
                .mobile-menu-btn { display: block; }
                .guest-navbar { padding: 0 20px; }
            }
        </style>

        <div class="guest-navbar">
            <a href="/" class="logo">
                <img src="{{ asset('webicons/weblogo.png') }}" alt="Logo">
                <span>Rudra Stories</span>
            </a>
            <div class="nav-links">
                <a href="/">Home</a>
                <a href="/all_stories">Stories</a>
                <a href="/category">Categories</a>
                <a href="/books">Books</a>
                <a href="/about_me">About Us</a>
            </div>
            <div class="actions">
                <a href="/Log_In" class="btn-outline">Login</a>
                <a href="/Sign_Up" class="btn-fill">Sign Up</a>
            </div>
            <button class="mobile-menu-btn"><i class="fa fa-bars"></i></button>
        </div>

        <div class="main-content-wrapper">
    @endif
