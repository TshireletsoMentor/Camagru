<?php
    $DB_NAME = "camagru";
    $DB_SERVER = "mysql:host=localhost";
    $DB_SERVER_DB = "mysql:host=localhost;dbname=".$DB_NAME;
    $DB_USER = "root";
    $DB_PASSWORD = "tshireletso";
   try {
       $DB_NAME = new PDO($DB_SERVER_DB, $DB_USER, $DB_PASSWORD);
       $DB_NAME->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       //echo 'Connected to the database';
   } catch (PDOException $e) {
       // display error message
   }
?>