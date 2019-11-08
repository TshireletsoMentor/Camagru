<?php
    include_once "session.php";
    include_once "config/connect.php";
    include_once "config/util.php";
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
    <h2> <i>Camagru</i></h2><hr>

    <?php if(!isset($_SESSION['username'])): ?>
    <p>You are currently not signed in. <hr>
        <?php include_once 'login.php'; ?><br>
        <a href="forgotpassword.php">Forgot password?</a></p>
    <?php else: ?>
    <?php include_once 'upload_profile.php';?>
    <p>You are logged in as: <?php if(isset($_SESSION['username'])) echo "<b>".$_SESSION['username']."</b>"."."?><br><a href="reset.php">User settings</a>
    <br><a href="logout.php">Log out</a>
    </p>
    <?php endif ?>
</body>
</html>