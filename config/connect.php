<?php
    include "database.php";
    try {
       $DB_NAME = new PDO($DB_SERVER_DB, $DB_USER, $DB_PASSWORD);
       $DB_NAME->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       //echo 'Connected to the database';
    } catch (PDOException $err) {
       // display error message
    }
?>