<?php
    include_once 'config/connect.php';
    include_once 'session.php';
    include_once 'config/util.php';
        
    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];
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
    }
    else{
        redirecto("index");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
</head>
<body>
</body>
</html>