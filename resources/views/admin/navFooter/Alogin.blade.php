<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login - Rudra Stories</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
    body {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
    }
    .login-wrapper {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 24px;
      padding: 40px 50px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      text-align: center;
      position: relative;
      z-index: 10;
    }
    .login-wrapper h1 {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 8px;
      background: linear-gradient(135deg, #60a5fa, #a78bfa);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .login-wrapper p {
      color: #94a3b8;
      font-size: 15px;
      margin-bottom: 30px;
    }
    .form-group {
      margin-bottom: 20px;
      text-align: left;
    }
    .form-group input {
      width: 100%;
      padding: 14px 18px;
      background: rgba(0, 0, 0, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      color: #fff;
      font-size: 16px;
      outline: none;
      transition: all 0.3s;
    }
    .form-group input:focus {
      border-color: #60a5fa;
      background: rgba(0, 0, 0, 0.3);
      box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.1);
    }
    .form-group input::placeholder {
      color: #64748b;
    }
    .btn-login {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #3b82f6, #6366f1);
      color: #fff;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      margin-top: 10px;
    }
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px -10px rgba(59, 130, 246, 0.5);
    }
    .error-msg {
      background: rgba(239, 68, 68, 0.1);
      color: #f87171;
      padding: 12px;
      border-radius: 10px;
      border: 1px solid rgba(239, 68, 68, 0.2);
      margin-bottom: 24px;
      font-size: 14px;
      font-weight: 500;
    }
    footer {
      position: absolute;
      bottom: 20px;
      width: 100%;
      text-align: center;
      color: #64748b;
      font-size: 13px;
    }
    .heart { color: #ef4444; }

    /* Background decorators */
    .blob {
      position: absolute;
      filter: blur(80px);
      z-index: 1;
      opacity: 0.5;
    }
    .blob-1 {
      top: 10%;
      left: 10%;
      width: 300px;
      height: 300px;
      background: #3b82f6;
      border-radius: 50%;
    }
    .blob-2 {
      bottom: 10%;
      right: 10%;
      width: 400px;
      height: 400px;
      background: #8b5cf6;
      border-radius: 50%;
    }
  </style>
</head>
<body>
  <div class="blob blob-1"></div>
  <div class="blob blob-2"></div>

  <div class="login-wrapper">
    <h1>Rudra Stories</h1>
    <p>Sign in to Admin Console</p>

    @if (session('error'))
      <div class="error-msg">{{ session('error') }}</div>
    @endif

    <form method="POST" action="/admin">
      @csrf
      <div class="form-group">
        <input type="text" placeholder="Username" id="username" name="username" required autocomplete="off">
      </div>
      <div class="form-group">
        <input type="password" placeholder="Password" name="password" required>
      </div>
      <button type="submit" class="btn-login">Login to Dashboard</button>
    </form>
  </div>

  <footer>
    <p>Developed By <span class="heart">&hearts;</span> Sutex Team</p>
  </footer>
</body>
</html>
