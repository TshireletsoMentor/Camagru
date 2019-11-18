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
    <style>
        body{
            position: relative;
            min-height: 100%;
            min-height: 100vh;
            padding-bottom:10px;
        }
        footer {
            position: absolute;
            right: 0;bottom:0;
        }
        .navbar {
        overflow: hidden;
        background-color: #333;
        }
        .navbar a {
        float: left;
        font-size: 16px;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        }
        .dropdown {
        float: left;
        overflow: hidden;
        }
        .dropdown .dropbtn {
        font-size: 16px;  
        border: none;
        outline: none;
        color: white;
        padding: 14px 16px;
        background-color: inherit;
        font-family: inherit;
        margin: 0;
        }
        .navbar a:hover, .dropdown:hover .dropbtn {
        background-color: grey;
        }
        .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        }
        .dropdown-content a {
        float: none;
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
        }
        .dropdown-content a:hover {
        background-color: #ddd;
        }
        .dropdown:hover .dropdown-content {
        display: block;
        }
    </style>
</head>
<body>
    <b><i><font size="30">Camagru</font></i></b>
    <?php if(!isset($_SESSION['username'])):?>
    <img style='width:100px;height:100px;border-radius: 50%;border: solid 2px black' src='uploads/default.gif'></p>
    <?php endif ?>
    <hr>
    <div class="navbar">
        <a href="index.php">Home</a>
            <div class="dropdown">
                <?php if (isset($_SESSION['id'])): ?>
                <button class="dropbtn">Menu 
                  <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                  <a href="private_gallery.php">My Gallery</a>
                  <a href="camera.php">Photo Booth</a>
                  <a href="reset.php">Update Profile</a>
                  <a href="logout.php">Logout</a>
                </div>
                <?php endif ?>
            </div> 
    </div>
    <?php if(!isset($_SESSION['username'])): ?>
    <p>You are currently not signed in. <hr>
        <?php include_once 'login.php'; ?><br>
        <a href="forgot_password2.php">Forgot password?</a></p>
    <?php else: ?><br>
    <?php   include_once 'upload.php';
            include_once 'logout_auto.php';?>
    <p><?php if(isset($_SESSION['username'])) echo "<h1><b>".$_SESSION['username']."</b></h1>"?><br>
    <!-- <a href="reset.php">User settings</a>
    <br><a href="logout.php">Log out</a>-->
    </p>
    <?php endif?>
    <hr>
    <?php include 'gallery.php'; ?>
</body>
</html>