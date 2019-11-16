<?php 
	include_once ("session.php");
    include_once ("config/connect.php");
    
	if ($_GET['id']) {
		try {
			$img = htmlentities($_GET['id']);
			$sql = $DB_NAME->prepare("SELECT * FROM gallery WHERE id = :id");
			$sql->execute(array(':id' => $img));
			$row = $sql->fetch();
		} catch (PDOException $e) {
			echo "An error occurred: ".$e->getMessage();
        }
        
		if ($row['userid'] == $_SESSION['id']) {

			try {
				unlink("uploads/".$row['name']);
				$query = $DB_NAME->prepare("DELETE FROM gallery WHERE id = :img");
				$query->execute(array(':img' => $img));
				header("Location:index.php?delete=success");
			} catch (PDOException $e) {
				echo "An error occurred: ".$e->getMessage();
            }
            
		} else {
			header("Location: ".$_SERVER['HTTP_REFERER']);
		}
	} else {
			header("Location: ".$_SERVER['HTTP_REFERER']);
	}
?>