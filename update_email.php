<?php
include_once 'config/connect.php';
include_once 'config/util.php';


if(!empty($_GET['token']) && !empty($_GET['email'])){
    
    $token = htmlentities($_GET['token']);
    $newemail = htmlentities($_GET['email']);

    $query = "SELECT id, verified FROM `users` WHERE token=:$token";    
    $stmt = $DB_NAME->prepare($query);    
    $stmt->execute(array(':token' => $token));

    $row = $stmt->fetch();
    $id = $row['id'];

    if ($row == 1){
        if($row['verified'] == 'Y'){

            $query = "UPDATE users SET email=:email WHERE id = :id";
            $stmt = $DB_NAME->prepare($query);
            $stmt->execute(array('id' => $id, ':email' => $newemail));
        
            try{
                $token_new = bin2hex(random_bytes(50));

                $queryupdate = "UPDATE users SET `token` = :token WHERE id = :id";
                $stmt = $DB_NAME->prepare($queryupdate);
                $stmt->execute(array(':token' => $token_new, 'id' => $id));
                $result = flashMessage("Your email address has been changed.", "Pass");

            }
            catch (PDOException $err){
                $result = flashMessage("An error occured.".$err->getMessage());
            }
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