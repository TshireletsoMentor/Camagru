<?PHP 
include_once '../config/database.php';

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
    $result = "Registration unsuccessful";;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>index</title>
    <link rel="stylesheet" href="../style/index.css">
</head>
<body >
    <div class="middle_block">
      <div class="sign_up" style="background:rgb(157, 187, 243);">
        <form action="register.php" method="post">
          <table>
            <tr><td class="camagru">Camagru</td></tr>
            <tr><td> <input type="email" placeholder="Email" value="" name="email"> </td></tr>
            <tr><td> <input type="text" placeholder="Username" value="" name="username"> </td></tr>
            <tr><td> <input type="password"  placeholder="Password" value="" name="password"> </td></tr>
            <tr><td > <input type="submit"  value="sign up"> </td></tr>
            <tr><td style="text-align:center;">Have an account? <a href="login.php">Log in</a></td></tr>
          </table>
        </form>
      </div>
    </div>
</body>
</html>