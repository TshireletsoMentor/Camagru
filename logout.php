<?php
    include_once "session.php";
    include_once 'util.php';

    session_destroy();
    redirecto("index");
?>