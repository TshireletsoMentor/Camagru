<?php
    include_once "session.php";
    $id = $_SESSION['id'];
    $baseimage = $_POST['baseimage'];
    //var_dump($baseimage);

    if(!empty($baseimage)){
        $baseimage_name = "camera".$id.".png";
        $imagepath = "uploads/".$baseimage_name;
        $imgurl = str_replace("data:image/png;base64,", "", $baseimage);
        //$imgurl = str_replace(" ", "+", $imgurl);
        $imgdecode = base64_decode($imgurl);
        file_put_contents($imagepath, $imgdecode);
    }
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
            border:none;
            border-radius: 5px;
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
        .bar{
            border-radius: 5px;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: black;
            color: white;
            text-align: center;
            text-decoration: none;
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
        <div style="margin-left:auto;marign-right:auto;">
            <button id="capture" class="booth-capture-button">Take photo</button>
            <select class="bar" name="" id="">
                <option value="none">Normal</option>
                <option value="">Greyscale</option>
                <option value="">Sepia</option>
                <option value="">Invert</option>
                <option value="">Hue</option>
                <option value="">Blue</option>
                <option value="">Contrast</option>
                <option value="">Saturate</option>
            </select>
            <button id="save" class="booth-capture-button">Save</button>
        </div>
        <img id="image" style="width:100%;height:100%;"src="uploads/default.gif" alt="">
        

        <script  src="video.js"></script>
    </div>
</body>
<footer> &copy; Copyright tmentor <?php print date("Y")?></footer>
</html>
