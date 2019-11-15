<?php
    include_once 'config/connect.php';

    $imageid = htmlentities($_GET['id']);
    $url = $_SERVER['HTTP_HOST'].str_replace("like_error.php", "", $_SERVER['REQUEST_URI']);
    try{
        $query = "SELECT userid FROM gallery WHERE id = :id";
        $stmt = $DB_NAME->prepare($query);
        $stmt->execute(array(':id' => $imageid));
        $row = $stmt->fetch();
        $userid = $row['userid'];

        $query1 = "SELECT * FROM users WHERE id = :id";
        $stmt1 = $DB_NAME->prepare($query);
        $stmt1->execute(array(':id' => $userid));

        if(($row1 = $stmt1->fetch()) && $note_like = 1){
            $username = $row1['username'];
            $email = $row1['email'];
            $pref = $row1['preference'];

            if($pref == 'Y'){
                $query2 = "SELECT username FROM users WHERE id = :id";
                $stmt2 = $DB_NAME->prepare($query);
                $stmt2->execute(array(':id' => $_SESSION['id']));
                $row2 = $stmt2->fetch();
                $liker = $row2['username'];

                $subject = "<i>[Camagru]</i> - Password Reset";

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
                        '.$liker.' liked your image: '.$url.'image.php?'.$imageid.'<br>
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

?>