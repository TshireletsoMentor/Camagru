<?php
    include_once 'session.php';
    include_once 'config/connect.php';

    if(isset($_SESSION['id'])){
        $userid = $_SESSION['id'];
        $imageid = htmlentities($_GET['id']);
        try{
            $query = "SELECT * FROM likes WHERE imageid = :imageid AND userid = :userid";
            $stmt = $DB_NAME->prepare($query);
            $stmt->execute(array(':imageid' => $imageid, ':userid' => $userid));
            $row = $stmt->fetch();
            $note_like = 0;
            if($stmt->rowCount() == 1){
                $userid = $row['userid'];
                $like = $row['like'];
                if($like == 'Y'){
                    $query1 = "DELETE FROM likes WHERE imageid = :imageid AND userid = :userid";
                    $stmt1 = $DB_NAME->prepare($query1);
                    $stmt1->execute(array(':imageid' => $imageid, ':userid' => $userid));
                    $note_like = 0;
                }
            }
            else{
                $query2 = "INSERT INTO `likes` (userid, imageid, `like`) VALUES 
                        (:userid, :imageid, 'Y')";
                $stmt2 = $DB_NAME->prepare($query2);
                $stmt2->execute(array(':userid' => $userid, ':imageid' => $imageid));
                $note_like = 1;
                

                $url = $_SERVER['HTTP_HOST'].str_replace("like.php", "image.php", $_SERVER['REQUEST_URI']);
                try{
                    $query = "SELECT userid, title FROM gallery WHERE id = :id";
                    $stmt = $DB_NAME->prepare($query);
                    $stmt->execute(array(':id' => $imageid));
                    $row = $stmt->fetch();
                    $img_owner = $row['userid'];
                    $title = $row['title'];

                    $query1 = "SELECT * FROM users WHERE id = :id";
                    $stmt1 = $DB_NAME->prepare($query1);
                    $stmt1->execute(array(':id' => $img_owner));

                    $row1 = $stmt1->fetch();
                    if(($stmt->rowCount()== 1) && $note_like = 1){
                        $username = $row1['username'];
                        $email = $row1['email'];
                        $pref = $row1['preference'];

                        //echo $username;
                        if($pref == 'ON'){
                            $query2 = "SELECT username FROM users WHERE id = :id";
                            $stmt2 = $DB_NAME->prepare($query2);
                            $stmt2->execute(array(':id' => $userid));
                            $row2 = $stmt2->fetch();
                            $liker = $row2['username'];

                            //echo $liker;
                            
                            $subject = "<i>[Camagru]</i> - Notification";

                            $header = 'MIME-Version: 1.0'."\r\n";
                            $header .= 'Content-type: text/html; charset=UTF-8'."\r\n";
                            $header .= 'From: Camagru@DoNotReply.co.za'."\r\n";
                    
                            $message = '
                            <html>
                                <head>
                                    <title>'.$subject.'</title>
                                </head>
                                <body>
                                    Hey '.$username.'.<br>
                                    '.$liker.' liked your image: <a href="http://'.$url.'">"'.$title.'"</a> <br>
                                    If this email does not concern you, please ignore this email.
                                </body>
                            ';
                            mail($email, $subject, $message, $header);
                        }
                    }
                }
                catch(PDException $err){
                    echo "Error: ".$err->getMessage();
                } 
            }
        }
        catch(PDOException $err){
            echo "Error".$err->getMessage();
        }
    }
    header('location: image.php?id='.$imageid.'');
?>