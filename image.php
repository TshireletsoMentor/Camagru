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
            }
            catch(PDOExpection $err){
                echo "Comment not added".$err->getMessage();
            }
        }
        
    }
    function getComment($DB_NAME, $imageid){
        $query = "SELECT users.username, comments.comment, comments.date FROM comments, users
                    WHERE users.id = comments.userid AND imageid = :imageid ORDER BY comments.id DESC";
        $stmt = $DB_NAME->prepare($query);
        $stmt->execute(array(':imageid' => $imageid));
        
        while($row = $stmt->fetch()){
            echo "<div class='comment-box'><p>";
                echo "<b>".$row['username']."</b><br>";
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
			height: 50%;
            width: 50%;
            display:block;
            margin-left: auto;
            margin-right: auto;
            margin-bottom:5px;
			
		}
        .like{
            height: 50px;
            width: 50px;
            margin-right:100px;
            display:inline;
        }
    </style>
</head>
<body>
    <?php 
        if(!isset($_SESSION['id'])){
            $imageid = htmlentities($_GET['id']);
            $query = "SELECT name FROM gallery WHERE id = :id";
            $stmt = $DB_NAME->prepare($query);
            $stmt->execute(array(':id' => $imageid));
            $row = $stmt->fetch();
            echo "  <div class='container'>
                        <img src='".$row['name']."'>";
        }
        else{
            $userid = $_SESSION['id']; 
            $username = $_SESSION['username'];   
            $imageid = htmlentities($_GET['id']);

            $query = "SELECT name FROM gallery WHERE id = :id";
            $stmt = $DB_NAME->prepare($query);
            $stmt->execute(array(':id' => $imageid));
            $row = $stmt->fetch();

            $query2 = "SELECT like, userid FROM gallery WHERE id = :id";
            $stmt2 = $DB_NAME->prepare($query2);
            $stmt2->execute(array(':id' => $imageid));
            $like = 0;
            while($row2 = $stmt2->fetch()){
                if($row2['like'] == 'Y')
                    $like++;
            }

            echo "  <img src='".$row['name']."'>";
            echo '  <a href="like.php?id='.$imageid.'">"'.$like.'"</a>';
            echo    '<form action="'.setComment($DB_NAME, $imageid, $userid).'" method="post">
                        <input type="hidden" name="username" value="'.$username.'">
                        <textarea name="comment" placeholder="Enter comment..."></textarea><br>
                        <button type="submit" name="CommentSubmit">Comment</button>
                    </form>';
        }
        getComment($DB_NAME, $imageid);
    ?>

</body>
</html>