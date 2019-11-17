<?php
    include_once 'config/connect.php';
    include_once 'session.php';
    include_once 'config/util.php';


    function setComment($DB_NAME, $imageid, $userid){
        if(isset($_POST['CommentSubmit'])){
            $comment = htmlentities($_POST['comment']);
            try{
                $query = "INSERT INTO comments (userid, imageid, comment, date) VALUES
                            (:userid, :imageid, :comment, now())";
                $stmt = $DB_NAME->prepare($query);
                $stmt->execute(array(':userid' => $userid, ':imageid' => $imageid, ':comment' => $comment));
                echo flashMessage("Comment added", "Pass");

                $query1 = "SELECT userid, title FROM gallery WHERE id = :imageid";
                $stmt1 = $DB_NAME->prepare($query1);
                $stmt1->execute(array(':imageid' => $imageid));
                $row1 = $stmt1->fetch();
                $img_owner = $row1['userid'];
                $img_title = $row1['title'];
                $img_comment = $comment;

                $query2 = "SELECT username, preference, email FROM users WHERE id = :userid";
                $stmt2 = $DB_NAME->prepare($query2);
                $stmt2->execute(array(':userid' => $img_owner));
                $row2 = $stmt2->fetch();
                $img_owner_username = $row2['username'];
                $pref = $row2['preference'];
                $email = $row2['email'];
                $commenter = $_SESSION['username'];

                if($pref == 'ON'){
                    $url = $_SERVER['HTTP_HOST'].str_replace("image.php", "image.php", $_SERVER['REQUEST_URI']);
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
                                    Hey <b>'.$img_owner_username.'</b>.<br>
                                    '.$commenter.' commented your image: "'.$img_title.'".<br>
                                    comment:<br>
                                        "'.$img_comment.'"<br>
                                    Click the link to view all comments: <a href="http://'.$url.'">Gallery</a>.<br>
                                    If this email does not concern you, please ignore this email.
                                </body>
                            ';
                            mail($email, $subject, $message, $header);
                }
            }
            catch(PDOExpection $err){
                echo "Comment not added".$err->getMessage();
            }
        
        }
        
    }
    function getComment($DB_NAME, $imageid){
        $query = "SELECT users.username, users.id, comments.comment, comments.date FROM comments, users
                    WHERE users.id = comments.userid AND imageid = :imageid ORDER BY comments.id DESC";
        $stmt = $DB_NAME->prepare($query);
        $stmt->execute(array(':imageid' => $imageid));

        while($row = $stmt->fetch()){
                echo "<div class='comment-box'><p>";

                $query1 = "SELECT `status` FROM pro_img WHERE userid = :userid";
                $stmt1 = $DB_NAME->prepare($query1);
                $stmt1->execute(array(':userid' => $row['id']));
                $row1 = $stmt1->fetch();

                if($row1['status'] == 1){
                    $filename = "uploads/profile".$row['id']."*";
                    $fileinfo = glob($filename);
                    $fileext = explode(".", $fileinfo[0]);
                    $fileactualext = $fileext[1];
                    $file_display = "uploads/profile".$row['id'].".".$fileactualext;
                }
                else{
                    $file_display = "uploads/default.jpg";
                }

                echo "<img style='float:left;width:50px;height:50px;border-radius: 50%;border: solid 2px black' src='$file_display'><b>".$row['username']."</b><br>";
                echo "@".$row['date']."<br><br>";
                echo "<div class='small_box'>",nl2br($row['comment']),"</div>";
            echo "</p></div>";
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Image</title>
    <style>
 		body {
			background-color: #ddd;
		}
		textarea {
			width: 80%;
			height: 80px;
			background-color: #fff;
			resize: none;
            display:block;
            margin-left: auto;
            margin-right: auto;
		}
        .small_box{
            background-color:#ddd;
        }
		button {
			width: 100px;
			height: 30px;
			background-color: #282828;
			border: none;
			color: #fff;
			font-family: arial;
			font-weight: 400;
			cursor: pointer;
			margin-bottom: 60px;
            display:block;
            margin-left: auto;
            margin-right: auto;
		}

		.comment-box {
			width: 80%;
			padding: 10px;
			margin-bottom: 4px;
			background-color: #fff;
			border-radius: 4px;
            display:block;
            margin-left: auto;
            margin-right: auto;

		}

		.comment-box p {
			font-family: arial;
			font-size: 14px;
			line-height: 16px;
			color: #282828;
			font-weight: 100;
		}

		img	{
			height: 40%;
            width: 40%;
            display:block;
            margin-left: auto;
            margin-right: auto;
            margin-bottom:5px;
			
		}
        .like{
            text-align: center;
        }
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #333;
        }
        li {
            float: left;
        }
        li a, .dropbtn {
            display: inline-block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        li a:hover, .dropdown:hover .dropbtn {
            background-color: grey;
        }
        li.dropdown {
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }
        .dropdown-content a:hover {background-color: #f1f1f1;}

        .dropdown:hover .dropdown-content {
            display: block;
        }
        a {
            text-decoration: none;
            color:grey;
        }
        .a:link{
            color:white;
        }.a:visited{
            color:grey;
        }
        .delete{
            width: 60px;
            margin: auto;
            padding: 10px 20px;
            background-color: black;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
<h1><i>Camagru</i></h1><hr>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li class="dropdown">
            <?php if(isset($_SESSION['username'])){?>
            <a href="javascript:void(0)" class="dropbtn">Menu</a>
            <div class="dropdown-content">
            <a href="reset.php">Profile settings</a>
            <a href="private_gallery.php">My Gallery</a>
            <a href="camera.php">Photo Booth</a>
            <a href="logout.php">Log out</a><?php }?>
            </div>
        </li>
    </ul> <br>  
    <?php 
        if(!isset($_SESSION['id'])){
            $imageid = htmlentities($_GET['id']);
            $query = "SELECT name, title FROM gallery WHERE id = :id";
            $stmt = $DB_NAME->prepare($query);
            $stmt->execute(array(':id' => $imageid));
            $row = $stmt->fetch();

            $query2 = "SELECT `like` FROM likes WHERE imageid = :imageid";
            $stmt2 = $DB_NAME->prepare($query2);
            $stmt2->execute(array(':imageid' => $imageid));
            $like = 0;
            while($row2 = $stmt2->fetch()){
                if($row2['like'] == 'Y')
                    $like++;
            }
            echo "<img src='".$row['name']."'><br>";
            echo '<div class="like">'.$row['title'].'<br><br>'.$like.'&#x1f44d</div>';
        }
        else{
            $userid = $_SESSION['id']; 
            $username = $_SESSION['username'];   
            $imageid = htmlentities($_GET['id']);

            $query = "SELECT name, userid, title FROM gallery WHERE id = :id";
            $stmt = $DB_NAME->prepare($query);
            $stmt->execute(array(':id' => $imageid));
            $row = $stmt->fetch();

            $query2 = "SELECT `like` FROM likes WHERE imageid = :imageid";
            $stmt2 = $DB_NAME->prepare($query2);
            $stmt2->execute(array(':imageid' => $imageid));
            $like = 0;
            while($row2 = $stmt2->fetch()){
                if($row2['like'] == 'Y')
                    $like++;
            }
            if(!empty($row['name'])){
            echo "  <div class='like'><img src='".$row['name']."'><br>";
            echo $row['title'];}
            else{redirecto('index');}
            if($row['userid'] == $_SESSION['id']){
            echo '  <br><br><div class="delete"><a href="delete.php?id='.$imageid.'">DELETE</a></div><br><br>';}
            echo '  <a href="like.php?id='.$imageid.'">'.$like.'&#x1f44d</a>';
            echo    '<form action="'.setComment($DB_NAME, $imageid, $userid).'" method="post">
                        <input type="hidden" name="username" value="'.$username.'">
                        <textarea name="comment" placeholder="Enter comment..."></textarea><br>
                        <button type="submit" name="CommentSubmit">Comment</button>
                    </form></div>';
        }
        getComment($DB_NAME, $imageid);
    ?>

</body>
</html>