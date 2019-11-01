<?php
    include_once "session.php";
    include_once 'config/util.php';

    session_destroy();
    redirecto("index");
?>