<?PHP 
include_once '../config/setup.php';

if(isset($_POST['email']) && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])){
  $email = $_POST['email'];
  $username = $_POST['username'];
  $password = $_POST['password'];

  try{
    $sql = "INSERT INTO `users` (username, email, password, verified)
    VALUES (:username, :email, :password, :verified)";

    $stmt = $conn->prepare($sql);
    $stmt->execute(array(':username' => $username, ':email' => $email, ':password' => $password));
  
    if($stmt->rowCount() == 1){
      $result = "Registration successful";
    }
  }
  catch (PDOException $err){
    $result = "Registration unsuccessful";
  }
}
?>