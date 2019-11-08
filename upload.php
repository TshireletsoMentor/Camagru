<?php
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
                    header("Location: index.php?upload_successful")
;                }
                else
                echo "Uploaded file is too large, maximum file size: 50 mb.";
            }
            else
                echo "Error uploading file, please try again.";
        }
        else{
            echo "Only image files allowed, these include: 'jpg', 'jpeg', 'png' and 'gif'.";
        }

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
        <button type="submit" name="submit">Upload image</button>
    </form>   
</body>
</html>