<?php
    include_once 'config/connect.php';
    include_once 'session.php';
    
    $id = $_SESSION['id'];
    $fileDestination;
    if(isset($_POST['submit'])){
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
                if($fileSize < 50000){
                    $fileNameNew = uniqid('', true).".".$fileActualExt;
                    $fileDestination = 'uploads/'.$fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
                    
                    $query = "UPDATE pro_img SET status = 1 WHERE userid = :userid";
                    $stmt = $DB_NAME->prepare($query);
                    $stmt->execute(array(':userid' => $id));
                    //header("Location: index.php?upload_successful");
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
    if(isset($_POST['remove_img'])){
        $query = "UPDATE pro_img SET status = 0 WHERE userid = :userid";
        $stmt = $DB_NAME->prepare($query);
        $stmt->execute(array(':userid' => $id));
    }

    try{
        $query = "SELECT `userid`, `status` FROM `pro_img` WHERE userid = :userid";
        $stmt = $DB_NAME->prepare($query);
        $stmt->execute(array(':userid' => $_SESSION['id']));
        $row = $stmt->fetch();
        if($row['status'] == 1){
                echo "<img style='width:100px;height:100px;border-radius: 50%;border: solid 2px black' src='".$fileDestination."'>"."<br>";  
        }
        else{
            echo "<img style='width:100px;height:100px;border-radius: 50%;border: solid 2px black' src='uploads/default.jpg'>"."<br>";
        }
    }
    catch (PDOException $err){
        echo "Error".$err->getMessage();
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        <button type="submit" name="remove_img">Remove</button>
        <br><button type="submit" name="submit">Upload image</button>
    </form>   
</body>
</html>