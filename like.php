<?php
    include_once 'session.php';
    include_once 'config/connect.php';

    if(isset($_SESSION['id'])){
        $userid = $_SESSION['id'];
        $imageid = htmlentities($_GET['id']);
        try{
            $query = "SELECT * FROM likes WHERE id = :id AND userid = :userid";
            $stmt = $DB_NAME->prepare($query);
            $stmt->execute(array(':id' => $imageid, ':userid' => $userid));
            
            if($row = $stmt->fetch()){
                $userid = $row['userid'];
                $like = $row['like'];
                if($like == 'Y'){
                    $query1 = "DELETE FROM likes WHERE id = :id AND userid = :userid";
                    $stmt1 = $DB_NAME->prepare($query1);
                    $stmt1->execute(array(':id' => $imageid, ':userid' => $userid));
                    $note_like = 0;
                    require 'like_error.php';
                }
            }
            else{
                $query2 = "INSERT INTO likes (userid, imageid, like) VALUES 
                        (:userid, :imageid, 'Y')";
                $stmt2 = $DB_NAME->prepare($query2);
                $stmt2->execute(array(':userid' => $userid, 'imageid' => $imageid));
                $note_like = 1;
                require 'like_error.php';
            }
        }
        catch(PDOException $err){
            echo "Error".$err->getMessage();
        }
    }
?>