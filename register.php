<?php
    include_once "config/connect.php";

    if(isset($_POST['signupBtn'])){

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
        try{
            $query = "INSERT INTO `users` (username, password, email, join_date)
                    VALUES (:username, :password, :email, now())";
    
            $stmt = $DB_NAME->prepare($query);
            $stmt->execute(array(':username' => $username, ':email' => $email, ':password' => $hashed_password));
    
            if ($stmt->rowCount() == 1){
                $result = "<p style='padding: 20px; color: green;'> Registration successful </P>";
            }
        }
        catch (PDOException $err){
                $result = "<p style='padding: 20px; color: red;'> Registration unsuccessful:".$err->getMessage()." </P>";
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

    <?PHP
    if(isset($result)) echo $result;
    ?>
    <form action="" method="post">
        <table>
            <tr><td>Email:</td><td><input type="email" value="" name="email" required></td></tr>
            <tr><td>Username:</td><td><input type="text" value="" name="username" required></td></tr>
            <tr><td>Password:</td><td><input type="password" value="" name="password" required></td></tr>
            <tr><td></td><td><input style="float: right;" type="submit" name="signupBtn" value="Sign up"></td></tr>
        </table>
    </form>
    <p><a href="index.php">Back</a></p>
</body>
</html>