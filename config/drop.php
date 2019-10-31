<?php
   include_once 'database.php';
   try {
      $conn = new PDO($DB_SERVER, $DB_USER, $DB_PASSWORD);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $query = "DROP DATABASE `$DB_NAME`";
      $conn->exec($query);
      echo "<p style='padding: 20px; color: green;'> Database deletion successful</P>";
   } 
   catch (PDOException $err) {
      echo "<p style='padding: 20px; color: red;'> Database deletion unsuccessfull".$err->getMessage().".</P>";
   }
?>