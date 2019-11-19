<?php 
    include_once 'logout.php';
    include_once 'session.php';

    if((time() - $_SESSION['last_login_timestamp']) > 60*15){
        redirecto("logout");
    }else
        $_SESSION['last_login_timestamp'] = time();
?>