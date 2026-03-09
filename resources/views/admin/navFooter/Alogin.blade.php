<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Rudra Stories</title>
  <link href="https://fonts.googleapis.com/css?family=Assistant:400,700" rel="stylesheet">
  <link rel="stylesheet" href="css/admincss/lg.css">
</head>
<body>
<section class='login' id='login'>
  <div class='head'>
    <h1 class='company'>Rudra Stories</h1>
  </div>
  <p class='msg'>Welcome back</p>
  @if (session('error'))
    <p class='msg' style="color:#c1121f">{{ session('error') }}</p>
  @endif
  <div class='form'>
    <form method="POST" action="/admin">
      @csrf
      <input type="text" placeholder='Username' class='text' id='username' name="username" required><br>
      <input type="password" placeholder='Password' class='password' name="password" required><br>
      <button type="submit" class='btn-login' id='do-login'>Login</button>
    </form>
  </div>
</section>
<footer>
  <p>Develop By <span class='heart'>&hearts;</span> Devn Mishra </p>
</footer>
</body>
</html>
