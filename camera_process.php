<?php

// echo "this is the response";
    include_once "session.php";
    include_once "config/util.php";

    if(!isset($_SESSION['id'])){
        redirecto("index");
    }
    $id = $_SESSION['id'];
    $baseimage = $_POST['baseimage'];

    if(!empty($baseimage)){
        $baseimage_name = uniqid('', true)."camera".$id.".png";
        $imagepath = "uploads/".$baseimage_name;
        $imgurl = str_replace("data:image/png;base64,", "", $baseimage);
        $imgurl = str_replace(" ", "+", $imgurl);
        //var_dump($imgurl);
        $imgdecode = base64_decode($imgurl);
        file_put_contents($imagepath, $imgdecode);
    }

    try{
        ;
    }
    catch (PDOException $err){
        ;
    }
?>