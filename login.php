<?php
    include_once "session.php";
    include_once "config/connect.php";
    include_once "config/util.php";

    if(isset($_POST['loginBtn'])){
        $user = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT * FROM `users` WHERE username = :username";
        $stmt = $DB_NAME->prepare($query);
        $stmt->execute(array(':username' => $user));

        while ($row = $stmt->fetch()){
            $id = $row['id'];
            $hashed_password = $row['password'];
            $username = $row['username'];

            if (password_verify($password, $hashed_password)){
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                header("location: index.php");
            }
            else{
                $result = "<p style='padding:20px; color:red'>Invalid username or password </p>";
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
    <title>Login</title>
</head>
<body>
    <h2>User Authentication System</h2><hr>
    <h3>Login form</h3>
    <?php if(isset($result)) echo $result;?>
    <form action="" method="post">
        <table>
            <tr><td>Username:</td><td><input type="text" value="" name="username" required></td></tr>
            <tr><td>Password:</td><td><input type="password" value="" name="password" required></td></tr>
            <tr><td></td><td><input style="float: right;" type="submit" name="loginBtn" value="log in"></td></tr>
        </table>
    </form>
</body>
</html>