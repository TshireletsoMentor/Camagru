<?php
    function check_input($username, $password){
        
        $form_errors = array();

        if(preg_match('/\\s/', $username)){
            $form_errors[] = "username must not contain spaces.";
        }
        if(preg_match('/\\s/', $password)){
            $form_errors[] = "password must not contain spaces.";
        }
        if(!preg_match('/^(?=.*\d)[a-zA-Z\d]{5,20}$/', $username)){
            $form_errors[] = "username must be between 5-20 characters long<br> and contain at least one number.";
        }
        if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,20}$/', $password)){
            $form_errors[] = "password must be between 6-20 characters, <br>containing at least one uppercase character and at least one number.";
        }
        return $form_errors;
    }

    function check_username($username){
        
        $form_errors = array();

        if(preg_match('/\\s/', $username)){
            $form_errors[] = "username must not contain spaces.";
        }
        if(!preg_match('/^(?=.*\d)[a-zA-Z\d]{5,20}$/', $username)){
            $form_errors[] = "username must be between 5-20 characters long<br> and contain at least one number.";
        }
        return $form_errors;
    }

    function check_pass($password){
        
        $form_errors = array();

        if(preg_match('/\\s/', $password)){
            $form_errors[] = "password must not contain spaces.";
        }
        if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,20}$/', $password)){
            $form_errors[] = "password must be between 6-20 characters, <br>containing at least one uppercase character and at least one number.";
        }
        return $form_errors;
    }

    /*function check_min_length($required_to_check_length){
        
        $form_errors = array();

        foreach($required_to_check_length as $name_of_field => $minimum_length_required){
            if(strlen(trim($_POST[$name_of_field])) < $minimum_length_required){
                $form_errors[] = $name_of_field ." is too short, must be atleast {$minimum_length_required} characters long.";
            }
        }
        return $form_errors;
    }*/

    function check_email($data){
        
        $form_errors = array();
        $key = 'email';

        if(array_key_exists($key, $data)){
            if($_POST[$key]!= null){
                $key = filter_var($key, FILTER_SANITIZE_EMAIL);

                if (filter_var($_POST[$key], FILTER_VALIDATE_EMAIL) === false){
                    $form_errors[] = $key . " is not a valid email address.";
                }
            }
        }
        return $form_errors;
    }

    function show_errors($form_errors_array){
        $errors = "<p><ul style='color:red;list-style-type:circle;'>";
        
        foreach($form_errors_array as $the_error){
            $errors .= "<li>{$the_error}</li>";
        }
        $errors .= "</ul></p>";
        return $errors;
    }

    function show_success($form_success_array){
        $success= "<p><ul style='color:green;list-style-type:circle;'>";
        
        foreach($form_success_array as $the_success){
            $success .= "<li>{$the_success}</li>";
        }
        $success .= "</ul></p>";
        return $success;
    }

    function flashMessage($message, $passOrfail = "Fail"){
        if($passOrfail === "Pass"){
            $data = "<p style='padding:10px; border: 1px solid gray; color: green;'>{$message}</p>";
        }
        else{
            $data = "<p style='padding:10px; border: 1px solid gray; color: red;'>{$message}</p>";
        }
        return $data;
    }

    function redirecto($page){
        header("location: {$page}.php");
    }

    //best function ever
    function duplicate($table, $column_name, $value, $DB_NAME){
        try{
            $query = "SELECT username FROM ".$table." WHERE ".$column_name." = :".$column_name;
            $stmt = $DB_NAME->prepare($query);
            $stmt->execute(array(":".$column_name => $value));

            if ($row = $stmt->fetch()){
                return true;
            }
            return false;
        }
        catch (PDOException $err){

        }
    }

    function sendVerification($email, $token, $url){

        $subject = "<i>[Camagru]</i> - Email Verification";

        $header = 'MIME-Version: 1.0'."\r\n";
        $header .= 'Content-type: text/html; charset=UTF-8'."\r\n";
        $header .= 'From: Camagru@DoNotReply.co.za'."\r\n";

        $message = '
        <html>
            <head>
                <title>'.$subject.'</title>
            </head>
            <body>
                Thanks for signing up to camagru.<br>
                To finalise the sign up process, please click the link: <br>
                <a href="http://'.$url.'verify.php?token='.$token.'">Verify my email</a><br>
                Alternatively, if the link does not work, paste the url:<br> http://'.$url.'/verify.php?token='.$token.'<br>
                If this email does not concern you, please ignore this email.
            </body>
        ';

        $retval = mail($email, $subject, $message, $header);
        if ($retval == true){
            $success = "<ul><li style='color:green;'>Verification mail has been sent to ".$email."</li></ul>";
        }
        else{
            echo "Error";
        }
        return($success);
    }

    function sendUpdatesEmail($email, $oldusername, $form_changes){
        
        $subject = "<i>[Camagru]</i> - Profile updates";

        $header = 'MIME-Version: 1.0'."\r\n";
        $header .= 'Content-type: text/html; charset=UTF-8'."\r\n";
        $header .= 'From: Camagru@DoNotReply.co.za'."\r\n";

        $message = '
        <html>
            <head>
                <title>'.$subject.'</title>
            </head>
            <body>
                
                Hi there '.$oldusername.'.<br>
                Changes to you profile have been made, these include: <br><br>'.implode("<br>", $form_changes).'<br><br>

                If this email does not concern you, please ignore this email.
            </body>
        ';

        mail($email, $subject, $message, $header);
    }

    function sendReset($email, $pass){
        $subject = "<i>[Camagru]</i> - Password Reset";

        $header = 'MIME-Version: 1.0'."\r\n";
        $header .= 'Content-type: text/html; charset=UTF-8'."\r\n";
        $header .= 'From: Camagru@DoNotReply.co.za'."\r\n";

        $message = '
        <html>
            <head>
                <title>'.$subject.'</title>
            </head>
            <body>
                Your password has been reset. Your new password is: '.$pass.'.<br>
                Please log on to <i>Camagru</i> and change this password.<br>
                If this email does not concern you, please ignore this email.
            </body>
        ';

        $retval = mail($email, $subject, $message, $header);
        if ($retval == true){
            $success = "<ul><li style='color:green;'>Password reset mail has been sent to ".$email."</li></ul>";
        }
        else{
            echo "Error";
        }
        return($success);
    }

    function sendReset2($email, $hash_token, $url){
        $subject = "<i>[Camagru]</i> - Password Reset";

        $header = 'MIME-Version: 1.0'."\r\n";
        $header .= 'Content-type: text/html; charset=UTF-8'."\r\n";
        $header .= 'From: Camagru@DoNotReply.co.za'."\r\n";

        $message = '
        <html>
            <head>
                <title>'.$subject.'</title>
            </head>
            <body>
                Your password has been reset. To create a new password please click the link: <br>
                <a href="http://'.$url.'forgot_password_login.php?token='.$hash_token.'">Change password</a><br>
                Alternatively, if the link does not work, paste the url:<br> http://'.$url.'forgot_password_login.php?token='.$hash_token.'<br>
                If this email does not concern you, please ignore this email.

            </body>
        ';

        $retval = mail($email, $subject, $message, $header);
        if ($retval == true){
            $success = "<ul><li style='color:green;'>Password reset mail has been sent to ".$email."</li></ul>";
        }
        else{
            echo "Error";
        }
        return($success);
    }

    function sendReset($email, $pass){
        $subject = "<i>[Camagru]</i> - Password Reset";

        $header = 'MIME-Version: 1.0'."\r\n";
        $header .= 'Content-type: text/html; charset=UTF-8'."\r\n";
        $header .= 'From: Camagru@DoNotReply.co.za'."\r\n";

        $message = '
        <html>
            <head>
                <title>'.$subject.'</title>
            </head>
            <body>
                Your password has been reset. Your new password is: '.$pass.'.<br>
                Please log on to <i>Camagru</i> and change this password.<br>
                If this email does not concern you, please ignore this email.
            </body>
        ';

        $retval = mail($email, $subject, $message, $header);
        if ($retval == true){
            $success = "<ul><li style='color:green;'>Password reset mail has been sent to ".$email."</li></ul>";
        }
        else{
            echo "Error";
        }
        return($success);
    }

    function sendEmailReset($email, $token, $url){
        $subject = "<i>[Camagru]</i> - Email Reset";

        $header = 'MIME-Version: 1.0'."\r\n";
        $header .= 'Content-type: text/html; charset=UTF-8'."\r\n";
        $header .= 'From: Camagru@DoNotReply.co.za'."\r\n";

        $message = '
        <html>
            <head>
                <title>'.$subject.'</title>
            </head>
            <body>
                You have indicated that you want to reset your email address. To change to your new email click the link: <br>
                <a href="http://'.$url.'update_email.php?token='.$token.'&email='.$email.'">Change email address</a><br>
                Alternatively, if the link does not work, paste the url:<br> http://'.$url.'update_email.php?token='.$token.'&email='.$email.'<br>
                If this email does not concern you, please ignore this email.

            </body>
        ';

        mail($email, $subject, $message, $header);
    }
?>