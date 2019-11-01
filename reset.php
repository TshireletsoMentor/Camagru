<?php
include_once 'config/connect.php';
include_once 'config/util.php';
include_once 'session.php';

if(!isset($_SESSION['username'])){
    redirecto("index");
}
else{
    if(isset($_POST['passwordResetBtn'])){
    
        $form_errors = array();
    
        $required_fields = array ('email', 'new_password', 'confirm_password');
    
        $form_errors = array_merge($form_errors, check_input($_SESSION['username'], $_POST['new_password']));
    
        //$fields_to_check_length = array ('new_password' => 6, 'confirm_password' => 6);
    
        //$form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));
    
        $form_errors = array_merge($form_errors, check_email($_POST));
        
        if(empty($form_errors)){
            $username = $_SESSION['username'];
            $password1 = $_POST['new_password'];
            $password2 = $_POST['confirm_password'];
    
            if ($password1 != $password2){
                $result = flashMessage("New password and Confirm password do not match.");
            }   
            else{
                try{
                    $query = "SELECT username FROM users WHERE username = :username";
    
                    $stmt = $DB_NAME->prepare($query);
                    $stmt->execute(array(':username' => $username));


                    if ($stmt->rowCount() == 1){
                        $hased_password = password_hash($password1, PASSWORD_DEFAULT);
    
                        $queryupdate = "UPDATE users SET `password` = :password WHERE `username` = :username";
    
                        $stmt = $DB_NAME->prepare($queryupdate);
    
                        $stmt->execute(array(':password' => $hased_password, ':username' => $username));
    
                        $result = flashMessage("Password reset was successful.", "Pass");
                    }
                    else
                        $result = flashMessage("Incorrect email address, try again.");
                }
                catch (PDOException $err){
                    $result = flashMessage("An error occured.".$err->getMessage());
                }
            }
        }
        else{
            $result = flashMessage("Error(s): ".count($form_errors)."<br>");
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password Reset Page</title>
</head>
<body>
    <h2>User Authentication System</h2><hr>
    
    <h3>Password Reset Form</h3>

    <?PHP if(isset($result)) echo $result; ?>
    <?php if(!empty($form_errors))echo show_errors($form_errors);?>
    <form action="" method="post">
        <table>
            <tr><td>New Password:</td> <td><input type="password" value="" name="new_password" placeholder="New Password" required  oninvalid="this.setCustomValidity('Enter New password of atleast six characters')"
              oninput="this.setCustomValidity('')"></td></tr>
            <tr><td>Confirm Password:</td> <td><input type="password" value="" name="confirm_password" placeholder="Confirm Password" required ></td></tr>
            <tr><td></td><td><input style='float:right'type="submit" name="passwordResetBtn" value="Reset Password"></td></tr>
        </table>
    </form>
    <p><a href="index.php">Back</a></p>
</body>
</html>