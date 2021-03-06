<?php
    include_once "session.php";
    include_once "config/connect.php";
    include_once "config/util.php";


    if(isset($_POST['loginBtn'])){
        $user = htmlentities($_POST['username']);
        $password = htmlentities($_POST['password']);

        $query = "SELECT * FROM `users` WHERE username = :username";
        $stmt = $DB_NAME->prepare($query);
        $stmt->execute(array(':username' => $user));

        while ($row = $stmt->fetch()){
            if(isset($_POST['rememberme'])){
                setcookie("username", $user, time()+(365*60*24*24));
                setcookie("password", $password, time()+(365*60*24*24));
            }

            $id = $row['id'];
            $hashed_password = $row['password'];
            $username = $row['username'];
            $verified = $row['verified'];
            $email = $row['email'];
            $pref = $row['preference'];

            if (password_verify($password, $hashed_password)){
                if ($verified == 'Y'){
                    $_SESSION['id'] = $id;
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    $_SESSION['preference'] = $pref;
                    $_SESSION['last_login_timestamp'] = time(); 
                    redirecto("index");}
                else{ 
                    $result = flashMessage("You need to verify your email, check your inbox");
                }
            }
            else{
                $result = flashMessage("Invalid username or password");
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
    <style>
        input{
            border: 1px solid #555;
            color:black;
        }
        input[type=submit] {
            outline: none;
            background-color: black;
            color: white;
        }
        input[type=text]{
            color:black;
        }
        input[type=text]:focus {
            outline: none;
            border: 3px solid #555;
        }
        input[type=password]:focus {
            outline: none;
            border: 3px solid #555;
        }
    </style>
</head>
<body>
    <h3>Login form</h3>
    <?php if(isset($result)) echo $result;?>
    <form action="" method="post">
        <table>
            <tr><td>Username:</td><td><input style="color:black" type="text" value="<?php if(isset($_COOKIE['username'])){echo $_COOKIE['username'];}?>" name="username" placeholder="Username" required oninvalid="this.setCustomValidity('Enter username')"
              oninput="this.setCustomValidity('')"></td></tr>
            <tr><td>Password:</td><td><input type="password" value="<?php if(isset($_COOKIE['password'])){echo $_COOKIE['password'];}?>" name="password" placeholder="Password" required oninvalid="this.setCustomValidity('Enter password')"
              oninput="this.setCustomValidity('')"></td></tr>
            <tr><td></td><td><input style="float: right;" type="submit" name="loginBtn" value="log in"></td></tr>
            <tr><td>Remember me </td><td><input type="checkbox" name="rememberme"></td></tr>
            <tr><td><a href="register.php">Sign up</a></td><td></td></tr>
        </table>
    </form>

</body>
</html>