<?php
include_once 'config/connect.php';
include_once 'config/util.php';

if(isset($_POST['passwordResetBtn'])){

    
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

    <?php ?>
    <?php ?>
    <form action="" method="post">
        <table>
            <tr><td>Email:</td> <td><input type="email" value="" name="email" required></td></tr>
            <tr><td>New Password:</td> <td><input type="password" value="" name="new_password" required></td></tr>
            <tr><td>Confirm Password:</td> <td><input type="password" value="" name="confirm_password" required></td></tr>
            <tr><td></td><td><input style='float:right'type="submit" name="passwordResetBtn" value="Reset Password"></td></tr>
        </table>
    </form>
    <p><a href="index.php">Back</a></p>
</body>
</html>