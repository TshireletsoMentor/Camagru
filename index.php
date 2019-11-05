<?php
    include_once "session.php";
    include_once "config/connect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>index</title>
</head>
<body>
    <h2> User Authentication system </h2><hr>

    <?php if(!isset($_SESSION['username'])): ?>
    <p>You are currently not signed in <a href="login.php">login</a>.
        Not a member yet? <a href="register.php">Sign up</a>.<br>
        <a href="forgotpassword.php">Forgot password?</a></p>
    <?php else: ?>
    <p>You are logged in as <?php if(isset($_SESSION['username'])) echo $_SESSION['username']?><hr><a href="reset.php">Change Password?</a><br><a href="logout.php">logout</a></p>
    <?php endif ?>

</body>
</html>