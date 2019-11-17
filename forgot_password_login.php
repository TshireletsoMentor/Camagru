
<?php
include_once 'config/connect.php';
include_once 'config/util.php';


if(!empty($_GET['token'])){
    
    $token_new = htmlentities($_GET['token']);
    $url = $_SERVER['HTTP_HOST'].str_replace("forgot_password_login.php", "", $_SERVER['REQUEST_URI']);

    $query = "SELECT * FROM `users` WHERE token = :token";    
    $stmt = $DB_NAME->prepare($query);    
    $stmt->execute(array(':token' => $token_new));
    $row = $stmt->fetch();
    $token = $row['token'];
    $email = $row['email'];
    $verified = $row['verified'];

    if($token == $token_new){
        if($verified == 'Y'){
            if(isset($_POST['forgotBtn'])){
                $password1 = htmlentities($_POST['password1']);
                $password2 = htmlentities($_POST['password2']);
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
                            $password1 = password_hash($password1, PASSWORD_DEFAULT);
                            $queryupdate2 = "UPDATE users SET `password` = :password WHERE `token` = :token";
                            $stmtupdate2 = $DB_NAME->prepare($queryupdate2);
                            $stmtupdate2->execute(array(':password' => $password1, ':token' => $token_new));
                            $success = flashMessage("Password reset was successful.", "Pass");
                        }
                        catch (PDOException $err){
                            $result = flashMessage("An error occured.".$err->getMessage());
                        }
                        if(empty($result)){
                            try{
                                $token_new = bin2hex(random_bytes(50));

                                $queryupdate = "UPDATE users SET `token` = :token WHERE `email` = :email";
                                $stmt = $DB_NAME->prepare($queryupdate);
                                $stmt->execute(array(':token' => $token_new, ':email' => $email));
                            }
                            catch(PDOException $err){
                                $result = flashMessage("An error occured.".$err->getMessage());
                            }
                        }
                    }
                    else{
                        $result = flashMessage("Error(s): ".count($form_errors)."<br>");
                    }
                }
            }
        }
        else{
            $result = flashMessage("You need to verify your email before you can reset your password, check your inbox");
            sendVerification($email, $token, $url);
        }
    }
    else
        $result = flashMessage("Reset link has expired.");
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
    <title>Forgot password</title>
    
</head>
<body>
    <h1><i>Camagru</i></h1><hr>
    <h3>Password reset</h3>

    <?PHP if(isset($result)) echo $result; ?>
    <?php if(!empty($form_errors)){echo show_errors($form_errors);}else{if(isset($success))echo $success;}?>
    <form action="" method="post">
    <table>
        <tr><td>New password:</td><td><input type="password" value="" name="password1" placeholder="New_password" required oninvalid="this.setCustomValidity('Enter username')"
        oninput="this.setCustomValidity('')"></td></tr>
        <tr><td>Re-type password:</td><td><input type="password" value="" name="password2" placeholder="Re-type assword" required></td></tr>
        <tr><td></td><td><input style="float: right;" type="submit" name="forgotBtn" value="reset"></td></tr>
    </table>
    </form>
    <br><a href='index.php'>back</a>
</body>
</html>