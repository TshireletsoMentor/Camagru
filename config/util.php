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
?>