<?php
    include_once "session.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Booth</title>
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
        .booth{
            width: 400px;
            background: #ccc;
            border: 10px solid #ddd;
            margin: auto;
        }
        .booth-capture-button{
            display: block;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: black;
            color: white;
            text-align: center;
            text-decoration: none;
        }
        #canvas{
            display:none;
        }
    </style>
</head>
<body>
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
    <div class="booth">
        <video id="video" width="400" height="300" autoplay></video>
        <canvas id="canvas" width="400" height="300"></canvas>
        <a href="#" id="capture" class="booth-capture-button">Take photo</a>
        <img id="image" style="width:100%;height:400px;"src="uploads/default.gif" alt="">
        <script  src="video.js"></script>
    </div>
</body>
<footer> &copy; Copyright tmentor <?php print date("Y")?></footer>
</html>