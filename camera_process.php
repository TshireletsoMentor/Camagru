<?php

// echo "this is the response";
    include_once "session.php";
    include_once "config/util.php";
    include_once 'logout_auto.php';

    if(!isset($_SESSION['id'])){
        redirecto("index");
    }
    $id = $_SESSION['id'];
    
    if(isset($_POST['baseimage']) && isset($_POST['stickerURL'])){
        $baseimage = $_POST['baseimage'];
        $stickerurl = $_POST['stickerURL'];
        $baseimage_name = uniqid('', true)."camera".$id.".png";
        $imagepath = "uploads/".$baseimage_name;
        $imgurl = str_replace("data:image/png;base64,", "", $baseimage);
        $imgurl = str_replace(" ", "+", $imgurl);
        $imgdecode = base64_decode($imgurl);
        $stickerurl = str_replace("data:image/png;base64,", "", $stickerurl);
        $stickerurl = str_replace(" ", "+", $stickerurl);
        $stickerdecode = base64_decode($stickerurl);
        $image = imagecreatefromstring($imgdecode);
        $sticker = imagecreatefromstring($stickerdecode);
        imagecopy($image, $sticker, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagepng($image, '/goinfre/tmentor/Desktop/Mamp/apache2/htdocs/Camagru/uploads/'.$baseimage_name);
    }
 
    try{
        ;
    }
    catch (PDOException $err){
        ;
    }
?>