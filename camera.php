<?php
    include_once "session.php";
    include_once 'logout_auto.php';
    include_once "config/util.php";
    include_once 'config/connect.php'; 

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
            height:100%;
            background: #ccc;
            border: 10px solid #ddd;
            margin: auto;
        }
        .booth-capture-button{
            align-content:center;
            border: 2px solid black;
            border-radius: 5px;
            margin: 10px;
            padding: 10px 20px;
            background-color: black;
            color: white;
            text-align: center;
            text-decoration: none;
        }
        input{
            background-color:black;
        }
        input[type=file]{
            margin-left:10px;
            padding: 2px 2px;
            border-radius: 5px;
            background-color:black;
            color:white;
        }
        button[type=submit]{
            text-align: center;
            padding: 4px 4px;
            border-radius: 5px;
            background-color:black;
            color:white;
        }

        #canvas{
            position:relative;
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
        .filters{
            display:inline;
            text-align:center;
        }
        .filters img{
            padding:3.5px;
            height:50px;
            width:52px;
            border: 2px solid black;
        }
        #canvas{
        }
        #canvasOverlay{
            position: absolute;
            border:2px solid blue;
            top: 55px;
        }
        .preview{
            display: flex;
            flex-wrap: wrap;
            padding: 0 4px;
            margin: 0px 0px 10px 0px;
        }
        .preview > div{
          flex: 15%;
          border: solid 2px black;
        }
        .preview > div img{
          vertical-align: middle;
          width: 100%;
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
                  <a href="reset.php">Update file</a>
                  <a href="logout.php">Logout</a>
                </div>
                <?php endif ?>
            </div> 
    </div>
    <div class="booth">
        <video id="video" width="400" height="300" autoplay></video>

        <div style="margin-left:auto;marign-right:auto;">
            <button id="capture" class="booth-capture-button">Take photo</button>
            <button id="clear" class="booth-capture-button">Clear</button>
            <?php echo '<input type="file" id="imageLoader" name="file">' ?>
            <?php
                  if(isset($result)){
                    echo $result;
                  }
            ?>
            <br>
            <button id="save" class="booth-capture-button">Save</button>
        </div>

        <canvas id="canvasOverlay" width="400" height="300" style="border:2px solid white;"></canvas>
        <canvas id="canvas" width="400" height="300" style="border:2px solid black;" ></canvas>
        <!-- <img id="upload" src="" alt="upload"> -->

        <div class="filters">
            <!-- <img id="upload" src="" alt="upload"> -->
            <img src="uploads/filters/heart.png" id="heart" alt="heart" width="100" height="100" onclick="addSticker(this.id)">
            <img src="uploads/filters/starwars.png" id="starwars" alt="starwars" width="100" height="100" onclick="addSticker(this.id)">
            <img src="uploads/filters/cat.png" id="cat" alt="cat" width="100" height="100" onclick="addSticker(this.id)">
            <img src="uploads/filters/ghost.png" id="ghost" alt="ghost" width="100" height="100" onclick="addSticker(this.id)">
        </div>
        <script  src="video.js"></script>
    </div>
    <div class="preview">
    <?php $query = "SELECT * FROM gallery ORDER BY id DESC LIMIT 5";
            $stmt = $DB_NAME->prepare($query);
            $stmt->execute();
            while($row = $stmt->fetch()){
                echo "<div>
                        <a href='image.php?id=".$row['id']."'>
                            <img src='".$row['name']."'>
                        </a>
                    </div>";
            }
            echo "<hr>";
    ?>
    </preview>
</body>
<footer> &copy; Copyright tmentor <?php print date("Y")?></footer>
</html>
