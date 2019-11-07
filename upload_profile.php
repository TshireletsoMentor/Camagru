<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile pict upload</title>
</head>
<?php
    include_once 'config/connect.php';
    include_once 'session.php';
    include_once 'config/util.php';

?>

<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        <button type="submit" name="submit">Upload image</button>
    </form>    
</body>
</html>