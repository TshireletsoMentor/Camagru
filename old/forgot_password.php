<?php
include_once 'config/connect.php';
include_once 'config/util.php';

if(isset($_POST['forgotBtn'])){
    $email = htmlentities($_POST['email']);
    $url = $_SERVER['HTTP_HOST'].str_replace("register.php", "", $_SERVER['REQUEST_URI']);
    
    $query = "SELECT * FROM `users` WHERE email = :email";
    $stmt = $DB_NAME->prepare($query);
    $stmt->execute(array(':email' => $email));

    if ($stmt->rowCount() == 1){
        $row = $stmt->fetch();
        $email = $row['email'];
        $verified = $row['verified'];
        $token = $row['token'];

        if ($verified == 'Y'){
            try{
                $pass = bin2hex(random_bytes(6));
                $hased_password = password_hash($pass, PASSWORD_DEFAULT);

                $queryupdate = "UPDATE users SET `password` = :password WHERE `email` = :email";
                $stmt = $DB_NAME->prepare($queryupdate);
                $stmt->execute(array(':password' => $hased_password, ':email' => $email));

                $success = sendReset($email, $pass);
            }
            catch (PDOException $err){
                $result = flashMessage("An error occured.".$err->getMessage());
            }
        }
        else{ 
            $result = flashMessage("You need to verify your email before you can reset your password, check your inbox");
            sendVerification($email, $token, $url);
            }
        }
    else{
        $result = flashMessage("Email address not found");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgotpassword</title>
</head>
<body>
    <h2> User Authentication system </h2><hr>
    <h3>Forgot password</h3>

    <?PHP if(isset($result)) echo $result; ?>
    <?php if(isset($success))echo $success;?>
    <form action="" method="post">
        <table>
            <tr><td>Email:</td><td><input type="email" value="" name="email" placeholder="Email" oninvalid="this.setCustomValidity('Enter a valid email address')"
              oninput="this.setCustomValidity('')"></td></tr>
            <tr><td></td><td><input style="float: right;" type="submit" name="forgotBtn" value="submit"></td></tr>
        </table>
    </form>
    <p><a href="index.php">Back</a></p>
</body>
</html>