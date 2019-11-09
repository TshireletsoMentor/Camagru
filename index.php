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
    <h1><i>Camagru</i>
    <?php if(!isset($_SESSION['username'])){?>
    <img style='width:100px;height:100px;border-radius: 50%;border: solid 2px black' src='uploads/default.gif'></h1>
    <?php } ?>
    <hr>

    <?php if(!isset($_SESSION['username'])): ?>
    <p>You are currently not signed in. <hr>
        <?php include_once 'login.php'; ?><br>
        <a href="forgot_password2.php">Forgot password?</a></p>
    <?php else: ?>
    <?php include_once 'upload_profile.php';?>
    <p><?php if(isset($_SESSION['username'])) echo "<h3><b>".$_SESSION['username']."</b></h3>"?><br><a href="reset.php">User settings</a>
    <br><a href="logout.php">Log out</a>
    </p>
    <?php endif ?>
    <hr>
    <?php include_once 'gallery.php'; ?>
</body>
</html>