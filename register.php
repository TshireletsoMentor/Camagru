<?php
    include_once "config/connect.php";
    include_once "config/util.php";

    if(isset($_POST['signupBtn'])){
        $username = htmlentities($_POST['username']);
        $email = htmlentities($_POST['email']);
        $password = htmlentities($_POST['password']);
        $confirm_password = htmlentities($_POST['confirm_password']);
        $url = $_SERVER['HTTP_HOST'].str_replace("register.php", "", $_SERVER['REQUEST_URI']);
        
        $form_errors = array();

        $required_fields = array ('email', 'username', 'password');

        $form_errors = array_merge($form_errors, check_input($username, $password));

        //$fields_to_check_length = array ('username' => 5, 'password' => 6);

        //$form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

        $form_errors = array_merge($form_errors, check_email($_POST));

        if(duplicate("users", "email", $email, $DB_NAME)){
            
            $form_errors[] = "Email address is already in use";
        }
        if(duplicate("users", "username", $username, $DB_NAME)){
            
            $form_errors[] = "Username already exists.";
        }
        if ($password != $confirm_password){
           $form_errors[] = "Password and Confirm password do not match.";
        }  
        if (empty($form_errors)){
    
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $token = bin2hex(random_bytes(50));
        
            try{
                $query = "INSERT INTO `users` (username, password, email, token, join_date)
                        VALUES (:username, :password, :email, :token, now())";
        
                $stmt = $DB_NAME->prepare($query);
                $stmt->execute(array(':username' => $username, ':email' => $email, ':password' => $hashed_password, ':token' => $token));
        
                if ($stmt->rowCount() == 1){
                    $result = flashMessage('Registration successful', 'Pass');
                    $success = sendVerification($email, $token, $url);
                }
                $query1 = "SELECT id FROM `users` WHERE username = :username";
                $stmt1 = $DB_NAME->prepare($query1);
                $stmt1->execute(array(':username' => $username));
                $row = $stmt1->fetch();

                $query2 = "INSERT INTO `pro_img` (userid, status) VALUES (:userid, 0)";
                $stmt2 = $DB_NAME->prepare($query2);
                $stmt2->execute(array(':userid' => $row['id']));
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
    <style>
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #333;
        }
        li {
            float: left;
        }
        li a, .dropbtn {
            display: inline-block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            font-size:18px;
            text-decoration: none;
        }
        li a:hover, .dropdown:hover .dropbtn {
            background-color: grey;
        }
        li.dropdown {
            display: inline-block;
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
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }
        .dropdown-content a:hover {background-color: #f1f1f1;}

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body>
    <h1><i>Camagru</i><hr>
    <ul>
        <li><a href="index.php">Home</a></li>
    </ul>
    <h3>Registration form</h3>

    <?PHP if(isset($result)) echo $result; ?>
    <?php if(!empty($form_errors)){echo show_errors($form_errors);}else{if(isset($success))echo $success;}?>

    <form action="" method="post">
        <table>
            <tr><td>Email:</td><td><input type="email" value="" name="email" placeholder="Email" oninvalid="this.setCustomValidity('Enter a valid email address')"
              oninput="this.setCustomValidity('')"></td></tr>
            <tr><td>Username:</td><td><input type="text" value="" name="username" placeholder="Username" oninvalid="this.setCustomValidity('username must be between 5-20 characters long and contain at least one number')"
              oninput="this.setCustomValidity('')" required></td></tr>
            <tr><td>Password:</td><td><input type="password" value="" name="password" placeholder="Password" required oninvalid="this.setCustomValidity('password must be between 6-20 characters, containing at least one uppercase character and at least one number.')"
              oninput="this.setCustomValidity('')"></td></tr>
            <tr><td>Confirm Password:</td><td><input type="password" value="" name="confirm_password" placeholder="Password" required oninvalid="this.setCustomValidity('password must be between 6-20 characters, containing at least one uppercase character and at least one number.')"
              oninput="this.setCustomValidity('')"></td></tr>
            <tr><td></td><td><input style="float: right;" type="submit" name="signupBtn" value="Sign up"></td></tr>
        </table>
    </form>
</body>
</html>