<?PHP 
include_once '../config/database.php';

if(isset($_POST['email']) && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])){
  $email = $_POST['email'];
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "INSERT INTO `users` (username, email, password, verified)`";
}
?>