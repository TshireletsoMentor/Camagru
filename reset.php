<?php
include_once 'config/connect.php';
include_once 'config/util.php';
include_once 'session.php';
include_once 'index.php';

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
            $oldpassword = $_POST['old_password'];
    
            if ($password1 != $password2){
                $result = flashMessage("New password and Confirm password do not match.");
            }   
            else{
                try{
                    $query = "SELECT username, password FROM users WHERE username = :username";
    
                    $stmt = $DB_NAME->prepare($query);
                    $stmt->execute(array(':username' => $username));
                    $row = $stmt->fetch();

                    if ($stmt->rowCount() == 1 && password_verify($oldpassword, $row['password'])){
                        $hased_password = password_hash($password1, PASSWORD_DEFAULT);
    
                        $queryupdate = "UPDATE users SET `password` = :password WHERE `username` = :username";
    
                        $stmt = $DB_NAME->prepare($queryupdate);
    
                        $stmt->execute(array(':password' => $hased_password, ':username' => $username));
    
                        $result = flashMessage("Password reset was successful.", "Pass");
                    }
                    else
                        $result = flashMessage("Incorrect old password, try again.");
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
    if(isset($_POST['usernameResetBtn'])){
        ;
    }
    if(isset($_POST['emailResetBtn'])){
        ;
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

    <?php if(isset($resetPw)){?>
        <?PHP if(isset($result)) echo $result; ?>
        <?php if(!empty($form_errors))echo show_errors($form_errors);?>
    <form action="" method="post">
        <table>
            <tr><td>Old Password:</td> <td><input type="password" value="" name="old_password" placeholder="Old Password" required ></td></tr>
            <tr><td>New Password:</td> <td><input type="password" value="" name="new_password" placeholder="New Password" required  oninvalid="this.setCustomValidity('Enter New password of between 6-20 characters, containing at least one uppercase character and at least one number.')"
              oninput="this.setCustomValidity('')"></td></tr>
            <tr><td>Confirm Password:</td> <td><input type="password" value="" name="confirm_password" placeholder="Confirm Password" required ></td></tr>
            <tr><td></td><td><input style='float:right'type="submit" name="passwordResetBtn" value="Reset Password"></td></tr>
        </table>
    </form>
    <?php }?>
    <?php if(isset($resetUsr)){?>
        <?PHP if(isset($result)) echo $result; ?>
        <?php if(!empty($form_errors))echo show_errors($form_errors);?>
        <form action="" method="post">
        <table>
            <tr><td>Current username: <?php echo $_SESSION['username'];?></td><td></td></tr>
            <tr><td>New Username:</td> <td><input type="text" value="" name="new_username" placeholder="New Username" required  oninvalid="this.setCustomValidity('Username must be between 5-20 characters long and contain at least one number')"
              oninput="this.setCustomValidity('')"></td></tr>
            <tr><td></td><td><input style='float:right'type="submit" name="usernameResetBtn" value="Reset username"></td></tr>
        </table>
        </form>;
    <?php }?>
    <?php if(isset($resetEmail)){?>
        <?PHP if(isset($result)) echo $result; ?>
        <?php if(!empty($form_errors))echo show_errors($form_errors);?>
        <form action="" method="post">
        <table>
            <tr><td>Current email: <?php echo $email;?></td><td></td></tr>
            <tr><td>New Email:</td> <td><input type="email" value="" name="new_email" placeholder="New Email" required  oninvalid="this.setCustomValidity('Enter a new valid email.')"
              oninput="this.setCustomValidity('')"></td></tr>
            <tr><td></td><td><input style='float:right'type="submit" name="emailResetBtn" value="Reset email"></td></tr>
        </table>
        </form>;;
    <?php }?>

    <p><a href="index.php">Back</a></p>
</body>
</html>