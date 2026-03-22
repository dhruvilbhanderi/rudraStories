<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/admincss/dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Admin</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>

<body>
    <div class="dash">
        <div class="left">
            <div class="head">
                <h2>RUDRA</h2>
            </div>
            <div class="slide">
                <ul>
                    <li id="da2022" class="das das12"><i class="fa fa-tasks"></i>Dashboard</li>
                    <li id="da2023" class="das"><i class="fa fa-pencil-square"></i>Write New</li>
                    <li id="da2024" class="das"><i class="fa fa-edit"></i>Edit </li>
                    <li id="da2030" class="das"><i class="fa fa-edit"></i>Thoughts </li>
                    <li id="da2025" class="das"><i class="fa fa-book"></i>Books</li>
                    <li id="da_orders" class="das"><i class="fa fa-shopping-cart"></i>Orders</li>
                    <li id="da2026" class="das"><i class="fa fa-user"></i>Users</li>
                    <li id="da2027" class="das"><i class="fa fa-commenting-o"></i>Messages</li>
                    <li id="da2028" class="das"><i class="fa fa-comments"></i>Comments</li>
                    <li id="da2029" class="das" onclick="showLogoutDialog()">
                        <i class="fa fa-sign-out"></i>
                        <form method="POST" action="/admin/logout" style="display:none;" id="logoutForm">
                            @csrf
                        </form>
                        <span style="cursor:pointer;">Logout</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="right">
            <div class="mainhe">
                <div class="imgf">
                    <img src="images/aboutimg.jpeg" alt="">
                </div>
                
            </div>

            <!-- Modern Logout Modal -->
            <style>
            .logout-overlay {
                position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
                z-index: 9999; display: none; align-items: center; justify-content: center;
                opacity: 0; transition: opacity 0.3s ease;
            }
            .logout-modal {
                background: #ffffff; border-radius: 20px; padding: 32px; width: 90%; max-width: 400px;
                text-align: center; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                transform: scale(0.95) translateY(10px); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .logout-overlay.show { display: flex; opacity: 1; }
            .logout-overlay.show .logout-modal { transform: scale(1) translateY(0); }
            .lm-icon {
                width: 64px; height: 64px; background: #fee2e2; color: #ef4444; border-radius: 50%;
                display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 20px;
            }
            .lm-title { font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 8px; font-family: 'Inter', sans-serif; }
            .lm-desc { font-size: 15px; color: #64748b; margin-bottom: 30px; line-height: 1.5; font-family: 'Inter', sans-serif; }
            .lm-actions { display: flex; gap: 12px; }
            .lm-btn {
                flex: 1; padding: 12px; border-radius: 12px; font-size: 15px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; font-family: 'Inter', sans-serif;
            }
            .lm-cancel { background: #f1f5f9; color: #475569; }
            .lm-cancel:hover { background: #e2e8f0; color: #0f172a; }
            .lm-confirm { background: #ef4444; color: #ffffff; }
            .lm-confirm:hover { background: #dc2626; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }
            </style>

            <div class="logout-overlay" id="logoutOverlay">
                <div class="logout-modal">
                    <div class="lm-icon"><i class="fa fa-sign-out"></i></div>
                    <div class="lm-title">Secure Logout</div>
                    <div class="lm-desc">Are you sure you want to log out of the admin panel?</div>
                    <div class="lm-actions">
                        <button class="lm-btn lm-cancel" onclick="closeLogoutDialog()">Cancel</button>
                        <button class="lm-btn lm-confirm" onclick="document.getElementById('logoutForm').submit()">Yes, Log out</button>
                    </div>
                </div>
            </div>

            <script>
            function showLogoutDialog() {
                const overlay = document.getElementById('logoutOverlay');
                overlay.style.display = 'flex';
                setTimeout(() => overlay.classList.add('show'), 10);
            }
            function closeLogoutDialog() {
                const overlay = document.getElementById('logoutOverlay');
                overlay.classList.remove('show');
                setTimeout(() => overlay.style.display = 'none', 300);
            }
            </script>

            <div class="bdy" id="ch10ngcon">

                 
                @include('admin.dashboard')
                @include('admin.navFooter.footer')
