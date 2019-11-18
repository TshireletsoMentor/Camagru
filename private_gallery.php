<?php
    include_once 'config/connect.php';
    include_once 'session.php';
    include_once 'config/util.php';
    include_once 'logout_auto.php';
    
    $id = $_SESSION['id'];
    if(isset($_POST['upload_img2']) && isset($id)){
        $success = array();
        for($x=0; $x < count($_FILES['file']['name']); $x++){
            $file = $_FILES['file']['name'][$x];
            $fileName = $_FILES['file']['name'][$x];
            $fileTmpName= $_FILES['file']['tmp_name'][$x];
            $fileSize = $_FILES['file']['size'][$x];
            $fileError = $_FILES['file']['error'][$x];
            $fileType = $_FILES['file']['type'][$x];

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
                        $success [] = "Uplad successfull";                    

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
            position: relative;
            min-height: 100%;
            min-height: 100vh;
            padding-bottom:10px;
        }
        footer {
            position: absolute;
            right: 0;bottom:0;
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
        a {
            text-decoration: none;
        }
        input[type=text] {
            outline: none;
            border: 1px solid #555;
            color: white;
        }
        
        input[type=file] {
            outline: none;
            border: 1px solid #555;
        }
        button[type=submit] {
            outline: none;
            border: 1px solid #555;
            background:black;
            color: white;
        }
    </style>
</head>
<body>
    <b><i><font size="30">Camagru</font></i></b><hr>
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
    <h3>Private Gallery</h3><br>
    
    <?php
    if(!isset($_SESSION['id'])){
        redirecto("index");
    }else{
        $id = $_SESSION['id'];
        include_once 'upload.php';
        echo "<br><br>";
    }?>
     <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="filetitle" placeholder="Image title...">
        <input type="file" name="file[]" multiple>
        <button type="submit" name="upload_img2">Upload</button>
    </form>
    <?php if(isset($result)){echo $result;}
        if(isset($success) && !isset($result)){
            if(count($success) < 2){
                echo flashMessage("Upload successful", "Pass");
            }
            else{
                echo flashMessage("Uploads successful", "Pass");
        }
    }?>
    <br>
    <hr style="border: dotted 2px;" />
    <header>
    <?php
        try{
            $query = "SELECT * FROM `gallery` WHERE userid = :userid";
            $stmt = $DB_NAME->prepare($query);
            $stmt->execute(array(':userid' => $id));
            $number_of_results = $stmt->rowCount();
            if(!isset($_GET['page'])){
                $page = 1;
            }
            else{
                if(is_numeric($_GET['page'])){
                    $page = $_GET['page'];
                }
                else{
                    $page = 1;
                }
            }
            $result_per_page = 10;
            $number_of_pages = ceil($number_of_results/$result_per_page);
            $start_lmit_number = ($page - 1) * $result_per_page;
            $query = "SELECT * FROM gallery ORDER BY id DESC LIMIT $start_lmit_number, $result_per_page";
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
            for($page = 1; $page <= $number_of_pages; $page++){
                echo "<a href='gallery.php?page=".$page."'> ".$page." </a>";
            }
        }
        catch(PDOException $err){
            ;
        }
    ?>
    </header>
    </body>
    <footer> &copy; Copyright tmentor <?php print date("Y")?></footer>
</html>