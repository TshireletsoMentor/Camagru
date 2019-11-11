<?php
    include_once 'config/connect.php';
    include_once 'session.php';
    include_once 'config/util.php';
    
    $id = $_SESSION['id'];
    if(isset($_POST['upload_pro'])){
        $file = $_FILES['filepro'];
        $fileName = $_FILES['filepro']['name'];
        $fileTmpName= $_FILES['filepro']['tmp_name'];
        $fileSize = $_FILES['filepro']['size'];
        $fileError = $_FILES['filepro']['error'];
        $fileType = $_FILES['filepro']['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        if(in_array($fileActualExt, $allowed)){
            if($fileError === 0){
                if($fileSize < 5000000){
                    $fileNameNew = "profile".$id.".".$fileActualExt;
                    $fileDestination = 'uploads/'.$fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
                    
                    $query = "UPDATE pro_img SET status = 1 WHERE userid = :userid";
                    $stmt = $DB_NAME->prepare($query);
                    $stmt->execute(array(':userid' => $id));
                }
                else
                echo flashMessage("Uploaded file is too large, maximum file size: 5 mb.");
            }
            else
                echo flashMessage("Error uploading file, please try again.");
        }
        else{
            echo flashMessage("Only image files are allowed, these include: 'jpg', 'jpeg', 'png' and 'gif'.");
        }
    }
    if(isset($_POST['remove_img'])){
        $filename = "uploads/profile"."$id"."*";
        if(!empty(glob($filename))){
            $fileinfo = glob($filename);
            $fileext = explode(".", $fileinfo[0]);
            $fileactualext = $fileext[1];

            $file_del = "uploads/profile"."$id".".".$fileactualext;
            if(!unlink($file_del)){
                echo flashMessage("File was not deleted!");
            }
            else{
                echo flashMessage("File was deleted!", "Pass");

                $query = "UPDATE pro_img SET status = 0 WHERE userid = :userid";
                $stmt = $DB_NAME->prepare($query);
                $stmt->execute(array(':userid' => $id));
            }
        }
        else
            echo flashMessage("No file to delete!");
    }

    try{
        $query = "SELECT `userid`, `status` FROM `pro_img` WHERE userid = :userid";
        $stmt = $DB_NAME->prepare($query);
        $stmt->execute(array(':userid' => $_SESSION['id']));
        $row = $stmt->fetch();

        if($row['status'] == 1){
            $filename = "uploads/profile"."$id"."*";
            $fileinfo = glob($filename);
            $fileext = explode(".", $fileinfo[0]);
            $fileactualext = $fileext[1];
            $file_display = "uploads/profile"."$id".".".$fileactualext;
            
            echo "<img style='width:100px;height:100px;border-radius: 50%;border: solid 2px black' src='"."$file_display"."?'".mt_rand()."><br>";  
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
        <table>
        <tr><td><input type="file" name="filepro"></td><td><button type="submit" name="remove_img">Remove</button></td></tr>
        <tr><td><button type="submit" name="upload_pro">Upload image</button></td><td></td></tr>
        </table>
    </form>   
</body>
</html>