<?PHP 
include "database.php";
echo "<h2>Camagru setup</h2>";
try {
    $conn = new PDO($DB_SERVER, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "CREATE DATABASE `".$DB_NAME."`";
    $conn->exec($query);
    echo "<p style='padding: 20px; color:green;'> Database created\n</p>";
}
catch (PDOException $err) {
    echo "<p style='padding:20px; color:red;'> Database not created\n".$err->getMessage()."</p>";
}

try {
    $conn = new PDO($DB_SERVER_DB, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "CREATE TABLE `users` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(25) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `email` VARCHAR(100) NOT NULL UNIQUE,
        `verified` VARCHAR(1) NOT NULL DEFAULT 'N',
        `token` VARCHAR(100) NOT NULL,
        `preference` VARCHAR(3) NOT NULL DEFAULT 'ON',
        `join_date` TIMESTAMP
        )";
        $conn->exec($query);
        echo "<p style='padding: 20px; color:green;'> Table: users, created\n</p>";
    } 
    catch (PDOException $err) {
        echo "<p style='padding:20px; color:red;'> Table: users, not created\n".$err->getMessage()."</p>";
}

try{
    $conn = new PDO($DB_SERVER_DB, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "CREATE TABLE `pro_img` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `userid` INT(11) NOT NULL,
        `status` INT(11) NOT NULL
        )";
        $conn->exec($query);
        echo "<p style='padding: 20px; color:green;'> Table: pro_img, created\n</p>";
}
catch (PDOException $err) {
    echo "<p style='padding:20px; color:red;'> Table: pro_img not created\n".$err->getMessage()."</p>";
}

try{
    $conn = new PDO($DB_SERVER_DB, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "CREATE TABLE `gallery` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `userid` INT(11) NOT NULL,
        `title` VARCHAR(255) NOT NULL,
        `name`  VARCHAR(255) NOT NULL
        )";
        $conn->exec($query);
        echo "<p style='padding: 20px; color:green;'> Table: gallery, created\n</p>";
}
catch (PDOException $err) {
    echo "<p style='padding:20px; color:red;'> Table: gallery not created\n".$err->getMessage()."</p>";
}
?>