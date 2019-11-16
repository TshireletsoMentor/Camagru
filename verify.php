<?php
include_once 'config/connect.php';
include_once 'config/util.php';


if(!empty($_GET['token'])){
    
    $token = htmlentities($_GET['token']);
        
    $query = "SELECT id, verified FROM `users` WHERE token='".$token."'";    
    $stmt = $DB_NAME->prepare($query);    
    $stmt->execute();

    $row = $stmt->fetch();

    if ($row > 0){
        if($row['verified'] == 'Y'){
            $result = flashMessage("Your account has already been verified.");
        }
        else{
            $query = "UPDATE users SET verified='Y' WHERE id = :id";
            $stmt = $DB_NAME->prepare($query);
            $stmt->execute(array('id' => $row['id']));
        
            $result = flashMessage("Your account has been verified.", "Pass");
        }
    }
    else{
        $result = flashMessage("The input url is invalid.");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify</title>
</head>
<body>
    <?php if(isset($result)) echo $result; 
    header("Refresh:3; url=index.php");
    ?>
    
</body>
</html>