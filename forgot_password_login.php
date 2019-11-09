<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot password</title>
</head>
<?php
include_once 'config/connect.php';
include_once 'config/util.php';


if(!empty($_GET['token'])){
    
    $hased_password = htmlentities($_GET['token']);
        
    $query = "SELECT * FROM `users` WHERE";    
    $stmt = $DB_NAME->prepare($query);    
    $stmt->execute();

    while($row = $stmt->fetch()){
        $pass = $row['password'];
        if (password_verify($pass, $hashed_password)){
            $username = $row['username'];
            ?>
            <body>
                <form action="" method="post">
                    <table>
                        <tr><td>New password:</td><td><input type="password" value="" name="password1" placeholder="New_password" required oninvalid="this.setCustomValidity('Enter username')"
                        oninput="this.setCustomValidity('')"></td></tr>
                        <tr><td>Re-type password:</td><td><input type="password" value="" name="password2" placeholder="Re-type assword" required></td></tr>
                        <tr><td></td><td><input style="float: right;" type="submit" name="forgotBtn" value="submit"></td></tr>
                    </table>
                    </form>
            </body>

            <?php
        }
    }
    if(isset($_POST['forgotBtn'])){
        $form_errors = array();
        if(isset($password1)){
            if(!isset($password2)){
                $form_errors[] = "For password reset, both password fields are required";
            }
            if ($password1 != $password2){
                $form_errors[] = "Password and Re-type password do not match.";
            }
        }
        if(!empty($password1) && !empty($password2)){
            $form_errors = array_merge($form_errors, check_pass($password1));
            if(empty($form_errors)){ 
                try{
                    $password = password_hash($password1, PASSWORD_DEFAULT);
                    $queryupdate2 = "UPDATE users SET `password` = :password WHERE `username` = :username";
                    $stmtupdate2 = $DB_NAME->prepare($queryupdate2);
                    $stmtupdate2->execute(array(':password' => $password, ':username' => $username));
                    $form_success[] = "Password reset was successful.";
                }
                catch (PDOException $err){
                    $result = flashMessage("An error occured.".$err->getMessage());
                }
            }
            else{
                $result = flashMessage("Error(s): ".count($form_errors)."<br>");
            }
        }
    }
}
else{
    redirecto("index");
}
?>

</html>