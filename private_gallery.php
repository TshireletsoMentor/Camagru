<?php
    include_once 'config/connect.php';
    include_once 'session.php';
    include_once 'config/util.php';
    
    $id = $_SESSION['id'];
    if(isset($_POST['upload_img2']) && isset($id)){
        $file = $_FILES['file'];
        $fileName = $_FILES['file']['name'];
        $fileTmpName= $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        if(in_array($fileActualExt, $allowed)){
            if($fileError === 0){
                if($fileSize < 50000000){
                    $fileNameNew = uniqid('', true).".".$fileActualExt;
                    $fileDestination = 'uploads/'.$fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
                    
                    if(!empty($_POST['filetitle'])){
                        $filetitle = htmlentities($_POST['filetitle']);
                    }
                    else{
                        $filetitle = "Are words needed?";
                    }
                    $query = "INSERT INTO `gallery`(userid, title, name)
                            VALUES(:userid, :title, :name)";
                    $stmt = $DB_NAME->prepare($query);
                    $stmt->execute(array(':userid' => $id, ':title' => $filetitle, ':name' => $fileDestination));
                    $result = flashMessage("Upload successful", "Pass");                    

                }
                else
                $result = flashMessage("Uploaded file is too large, maximum file size: 50 mb.");
            }
            else
            $result =  flashMessage("Error uploading file, please try again.");
        }
        else{
            $result =  flashMessage("Only image files are allowed, these include: 'jpg', 'jpeg', 'png' and 'gif'.");
        }
       
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Private gallery</title>
    <style>
       body{
           margin: 50;
       }
       header{
           text-align: center;
           margin: .5vw;
           font-size: 50;
       }
       header div{
           flex: auto;
           display:inline-block;
           margin: 10px;
       }
       header div img{
           width: 250px;
           height: 200px;
           border: solid 2px black;
           margin: 2px;
       }
       ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #333;
        }
        li {
            float: left;
        }
        li a, .dropbtn {
            display: inline-block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        li a:hover, .dropdown:hover .dropbtn {
            background-color: grey;
        }
        li.dropdown {
            display: inline-block;
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
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }
        .dropdown-content a:hover {background-color: #f1f1f1;}

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body>
    <b><i><font size="30">Camagru</font></i></b><hr>
    <section>
        <ul>
        <li><a href="index.php">Home</a></li>
        <li class="dropdown">
            <?php if(isset($_SESSION['username'])){?>
            <a href="javascript:void(0)" class="dropbtn">Menu</a>
            <div class="dropdown-content">
            <a href="reset.php">Profile settings</a>
            <a href="private_gallery.php">Private Gallery</a>
            <a href="#">Camera</a>
            <a href="logout.php">Log out</a><?php }?>
            </div>
        </li>
    </ul>
    <h3>Private Gallery</h3><br>
    


    <?php
    if(!isset($_SESSION['id'])){
        redirecto("index");
    }else{
        $id = $_SESSION['id'];
    }?>
     <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="filetitle" placeholder="Image title...">
        <input type="file" name="file">
        <button type="submit" name="upload_img2">Upload</button>
    </form>
    <?php if(isset($result)){echo $result;} ?>
    <br>
    <hr style="border: dotted 2px;" />
    <header>
    <?php

        $query = "SELECT `name`, id FROM `gallery` WHERE userid = :userid ORDER BY id DESC";
        $stmt = $DB_NAME->prepare($query);
        $stmt->execute(array(':userid' => $id));
        $row = $stmt->fetch();
        while($row = $stmt->fetch()){
                echo "<div onclick=location.href='image.php?id=".$row['id']."'>";
                    echo "<img src='".$row['name']."?'>";
                echo "</div>";
        }
    ?>
    </header>
    </section>
    </body>
</html>