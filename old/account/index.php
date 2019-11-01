<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>index</title>
    <link rel="stylesheet" href="../style/index.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster&display=swap" rel="stylesheet">
    <style>
      .camagru{
        font-family: 'Lobster', cursive;
        font-size: 60px;
        text-align: center;
}
    </style>
</head>
<body >
    <div class="middle_block">
      <div class="sign_up" style="background:rgb(232, 236, 243);">
        <form action="register.php" method="post">
          <table>
            <tr><td class="camagru">Camagru</td></tr>
            <tr><td> <input type="email" placeholder="Email" value="" name="email" required /> </td></tr>
            <tr><td> <input type="text" placeholder="Username" value="" name="username" required oninvalid="this.setCustomValidity('Enter username here, of atleast five characters')"
              oninput="this.setCustomValidity('')"/> </td></tr>
            <tr><td> <input type="password"  placeholder="Password" value="" name="password" required oninvalid="this.setCustomValidity('Enter password that contains atleast one uppercase character')"
              oninput="this.setCustomValidity('')" /> </td></tr>
            <tr><td > <input type="submit"  value="sign up"> </td></tr>
            <tr><td style="text-align:center;">Have an account? <a href="login.php">Log in</a></td></tr>
          </table>
        </form>
      </div>
    </div>
</body>
</html>