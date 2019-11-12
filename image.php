<?php
    include_once 'config/connect.php';
    include_once 'session.php';

    $imageid = htmlentities($_GET['id']);
    try{
        $query = "SELECT * FROM `gallery` WHERE id = :imageid";
        $stmt = $DB_NAME->prepare($query);
        $stmt->execute(array(':imageid' => $imageid));
        if($stmt->rowCount() == 1){
            $row = $stmt->fetch();
            $image = $row['name'];
            $title = $row['title'];
            echo "<div class='container'><img src='".$image."'>
            <h3><i>$title</i></h3></div>";
            
        }
        echo "<div class='comment'><p>Comments</p>
        <table><tr><td>
        <form method='' type='POST'>
        <textarea style='margin-left:10px' cols='50' rows='5' placeholder='Enter text here...'></textarea>
        <td></tr>
        <tr><td>
        <p><button style='float:right' type='submit' name='comment'>Comment</button></p>
        <td></tr>
        </form>
        </div>";

    }
    catch (PDOException $err){
        echo "Error: ".$err->getMessage();
    }


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Image</title>
    <style>
        .container{
            margin:auto;
            width:600px;
            Height:600px;
            padding: 1px;
        }
        .container img{
            width:100%;
            height:90%;
            border: 2px solid black;
        }
        .comment{
            margin:auto;
            width:600px;
            Height:100%;
            padding: 1px;
            border: 2px solid black;
        }
    </style>
</head>
<body>
    <?php 
        if(!isset($_SESSION['id'])){
            redirecto("index");
        }
    ?>
</body>
</html>