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
    </style>
</head>
<body>
    <section>
    <div>
    <h3>Gallery</h3><br>
    <header>
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
    <a href="index.php">back</a>
    <hr style="border: dotted 2px;" />
    <div class='row'>
    <?php 
        $query = "SELECT name, id FROM `gallery` WHERE userid = :userid ORDER BY id DESC";
        $stmt = $DB_NAME->prepare($query);
        $stmt->execute(array(':userid' => $id));
        $row = $stmt->fetch();
        while($row = $stmt->fetch()){
                echo "<div onclick=location.href='image.php?id=".$row['id']."'>";
                    echo "<img src='".$row['name']."?'>";
                echo "</div>";
        }
    ?>
    </div>
    </header>
    </section>
    </body>
</html>