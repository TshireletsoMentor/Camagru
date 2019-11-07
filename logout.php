<?php
    include_once "session.php";
    include_once 'config/util.php';

    session_unset();
    session_destroy();
    redirecto("index");
?>