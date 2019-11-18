<?php
    include_once 'config/connect.php';
    include_once 'session.php';
    include_once 'config/util.php';
    
    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];
    }
    if(isset($_POST['upload_img']) && isset($id)){
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
            $error = 0;
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
                        //echo flashMessage("Upload successful", "Pass");
                        $success [] = "Uplad successfull";

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
        if(count($success) < 2){
           echo flashMessage("Upload successful", "Pass");
        }
        else{
            echo flashMessage("Uploads successful", "Pass");
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
        footer {
            position: absolute;
            right: 0;bottom:0;
        }
    </style>

</head>
<body>
    <h3>Public Gallery</h3><br>
    <?php
    if(isset($_SESSION['id'])){?>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="filetitle" placeholder="Image title...">
        <input type="file" name="file[]" multiple>
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
            $result_per_page = 15;
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
                echo "<a href='index.php?page=".$page."'> ".$page." </a>";
            }
        }
        catch(PDOException $err){

        }
    ?>
    </header>
    </body>
    <footer> &copy; Copyright tmentor <?php print date("Y")?></footer>
</html>