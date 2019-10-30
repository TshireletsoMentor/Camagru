<?php
    /*function check_spaces($required_fields_array){
        
        $form_errors = array();

        foreach($required_fields_array as $name_of_field){
                if(strstr($name_of_field[0], ' ') != NULL){
                    $form_errors[] = $name_of_field ." must not contain spaces.";
                }
            }
        return $form_errors;
    }*/

    function check_min_length($required_to_check_length){
        
        $form_errors = array();

        foreach($required_to_check_length as $name_of_field => $minimum_length_required){
            if(strlen(trim($_POST[$name_of_field])) < $minimum_length_required){
                $form_errors[] = $name_of_field ." is too short, must be atleast {$minimum_length_required} characters long.";
            }
        }
        return $form_errors;
    }

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
?>