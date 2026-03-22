<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Login</title>
</head>
<body>

        <div class="conta">
            <div class="center">
                <div class="head">
                    <h2>Login</h2>
                </div>
                <div class="frm">
                    <form action="" method="post">
                        <p id="err00"></p>
                        <input type="text" name="id1020" id="id1020" placeholder="Username">
                        <div style="position: relative; margin-top: 10px; margin-bottom: 10px; border-radius: 4px; overflow: hidden; display: flex; align-items: center; flex: 1; width: 100%;">
                            <input type="password" name="pass20" id="pass20" placeholder="Password" style="width: 100%; box-sizing: border-box; padding-right: 40px;">
                            <i class="fa fa-eye" id="togglePassword" style="position: absolute; right: 10px; cursor: pointer; color: #555; z-index: 10;"></i>
                        </div>
                        <input type="submit" name="sub452d" id="sub452d">
                    </form>
                </div>
            </div>
        </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('pass20');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>