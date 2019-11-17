<?php
include_once 'config/connect.php';
include_once 'config/util.php';

if(isset($_POST['forgotBtn'])){
    $email = htmlentities($_POST['email']);
    $url = $_SERVER['HTTP_HOST'].str_replace("forgot_password2.php", "", $_SERVER['REQUEST_URI']);
    
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
                $token_new = bin2hex(random_bytes(50));

                $queryupdate = "UPDATE users SET `token` = :token WHERE `email` = :email";
                $stmt = $DB_NAME->prepare($queryupdate);
                $stmt->execute(array(':token' => $token_new, ':email' => $email));

                $success = sendReset2($email, $token_new, $url);
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
    <style>
            body {
        position: relative;
        min-height: 100%;
        min-height: 100vh;
        }
        footer {
        position: absolute;
        right: 0;bottom:0;
        }
        input{
            border: 1px solid #555;
        }
        input[type=submit] {
            outline: none;
            background-color: black;
            color: white;
        }
        input[type=text]:focus {
            outline: none;
            border: 3px solid #555;
        }
        input[type=email]:focus {
            outline: none;
            border: 3px solid #555;
        }
        input[type=password]:focus {
            outline: none;
            border: 3px solid #555;
        }
       .navbar {
        overflow: hidden;
        background-color: #333;
        }
        .navbar a {
        float: left;
        font-size: 16px;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        }
        .dropdown {
        float: left;
        overflow: hidden;
        }
        .dropdown .dropbtn {
        font-size: 16px;  
        border: none;
        outline: none;
        color: white;
        padding: 14px 16px;
        background-color: inherit;
        font-family: inherit;
        margin: 0;
        }
        .navbar a:hover, .dropdown:hover .dropbtn {
        background-color: grey;
        }
        .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        }
        .dropdown-content a {
        float: none;
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
        }
        .dropdown-content a:hover {
        background-color: #ddd;
        }
        .dropdown:hover .dropdown-content {
        display: block;
        }
    </style>
</head>
<body>
    <h1><i>Camagru</i><hr>
    <div class="navbar">
        <a href="index.php">Home</a>
            <div class="dropdown">
                <?php if (isset($_SESSION['id'])): ?>
                <button class="dropbtn">Menu 
                  <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                <a href="private_gallery.php">My Gallery</a>
                  <a href="reset.php">Update Profile</a>
                  <a href="logout.php">Logout</a>
                </div>
                <?php endif ?>
            </div> 
    </div>
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
</body>
<footer> &copy; Copyright tmentor <?php print date("Y")?></footer>
</html>