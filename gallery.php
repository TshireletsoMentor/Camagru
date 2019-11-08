<?php



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gallery</title>
</head>
<body>
    <form action="upload_profile.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="filename" placeholder="File name...">
        <input type="text" name="filetitle" placeholder="Image title...">
        <input type="text" name="filedesc" placeholder="Image destination...">
        <input type="file" name="file">
        <button type="submit" name="submit_pro">Upload</button>
    </form>    
</body>
</html>