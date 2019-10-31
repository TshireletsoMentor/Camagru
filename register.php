<?php
    include_once "config/connect.php";
    include_once "config/util.php";

    if(isset($_POST['signupBtn'])){

        $form_errors = array();

        $required_fields = array ('email', 'username', 'password');

        //$form_errors = array_merge($form_errors, check_spaces($required_fields));

        $fields_to_check_length = array ('username' => 5, 'password' => 6);

        $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

        $form_errors = array_merge($form_errors, check_email($_POST));

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(duplicate("users", "email", $email, $DB_NAME)){
            $result = flashMessage("Email address is already in use");
        }
        else if(duplicate("users", "username", $username, $DB_NAME)){
            $result = flashMessage("Username already exists");
        }
        else if (empty($form_errors)){
    
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
            try{
                $query = "INSERT INTO `users` (username, password, email, join_date)
                        VALUES (:username, :password, :email, now())";
        
                $stmt = $DB_NAME->prepare($query);
                $stmt->execute(array(':username' => $username, ':email' => $email, ':password' => $hashed_password));
        
                if ($stmt->rowCount() == 1){
                    $result = flashMessage('Registration successful', 'Pass');
                }
            }
            catch (PDOException $err){
                    $result = flashMessage('Registration unsuccessful '.$err->getMessage());
            }
        }
        else{
            $result = flashMessage("Error(s): ".count($form_errors)."<br>");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
</head>
<body>
    <h2>User Authentication System</h2><hr>
    <h3>Registration form</h3>

    <?PHP if(isset($result)) echo $result; ?>
    <?php if(!empty($form_errors))echo show_errors($form_errors);?>
        

    <form action="" method="post">
        <table>
            <tr><td>Email:</td><td><input type="email" value="" name="email" required></td></tr>
            <tr><td>Username:</td><td><input type="text" value="" name="username" required></td></tr>
            <tr><td>Password:</td><td><input type="password" value="" name="password" required oninvalid="this.setCustomValidity('Enter password of atleast six characters and contains at least one uppercase character')"
              oninput="this.setCustomValidity('')"></td></tr>
            <tr><td></td><td><input style="float: right;" type="submit" name="signupBtn" value="Sign up"></td></tr>
        </table>
    </form>
    <p><a href="index.php">Back</a></p>
</body>
</html>