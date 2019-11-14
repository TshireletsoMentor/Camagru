<?php
include_once 'config/connect.php';

if(strlen($com > 280)){
    $result = "Comments are limited to 280 characters";
}
else{
    $stmt->execute(array(':userid' => $id, ':imageid' => $imageid, ':comment' => $comments));

}

?>