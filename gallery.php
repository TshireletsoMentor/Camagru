<?php
    include_once 'config/connect.php';
    include_once 'session.php';
    include_once 'config/util.php';
    
    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];
    }
    if(isset($_POST['upload_img']) && isset($id)){
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
    <title>Gallery</title>
    <style>
        body{
            margin: 50;
        }
        header{
            margin: .5vw;
            font-size: 50;
            text-align:center;
        }
        header div{
            align-content: center;
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
        a {
            text-decoration: none;
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
        <button type="submit" name="upload_img">Upload</button>
    </form>
    <br>
    <?php } ?>
    <hr style="border: dotted 2px;" />
    <header>
        
    <?php 
        try{
            $query = "SELECT * FROM gallery";
            $stmt = $DB_NAME->prepare($query);
            $stmt->execute();
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

        }
    ?>
    </header>
    </body>
</html>