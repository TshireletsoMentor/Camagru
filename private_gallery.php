<?php
    include_once 'config/connect.php';
    include_once 'session.php';
    include_once 'config/util.php';
    
    if(!isset($_SESSION['id'])){
        redirecto("index");
    }
    else{
        $id = $_SESSION['id'];
    }
    if(isset($_POST['upload_img_gal']) && isset($id)){
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
                    echo flashMessage("Upload successful", "Pass");

                }
                else
                echo flashMessage("Uploaded file is too large, maximum file size: 50 mb.");
            }
            else
                echo flashMessage("Error uploading file, please try again.");
        }
        else{
            echo flashMessage("Only image files are allowed, these include: 'jpg', 'jpeg', 'png' and 'gif'.");
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
            margin: 0;
        }
        header{
            margin: .5vw;
            font-size: 0;
        }
        header div{
            flex: auto;
            display:inline;
            margin: 10px;

        }
        header div img{
            width: 250px;
            height: 200px;
            border: solid 2px black;
            margin: 2px;
        }
    </style>

</head>
<body>
    <h3>Gallery</h3><br>
    <?php
    if(isset($_SESSION['id'])){?>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="filetitle" placeholder="Image title...">
        <input type="file" name="file">
        <button type="submit" name="upload_img_gal">Upload</button>
    </form>
    <br>
    <hr style="border: dotted 2px;" />
    <header>
    <?php 
        $query = "SELECT name FROM `gallery` WHERE 'userid' = ':id' ORDER BY id DESC";
        $stmt = $DB_NAME->prepare($query);
        $stmt->execute(array(':id' => $id));
        $row = $stmt->fetch();
        var_dump($row);
        while($row = $stmt->fetch()){
                echo '<a href="#"><div><img src="'.$row['name'].'"></div></a>';
        }
    }
    ?>
    </header>
    </body>
</html>