<?PHP
require_once '../database.php';

//reset session
session_destroy();
$_SESSION = null;
session_start();

//validate username and password
if(isset($_POST['txtUser']) && isset($_POST['txtPassword'])) {
  $pdo = Database::connect();
  $sql = "SELECT * FROM fd_users WHERE username = ? LIMIT 1";
  $q = $pdo->prepare($sql);
  $q->execute(array($_POST['txtUser']));
  $data = $q->fetch();

  //username and password hashes are valid
  if(!strcmp($data['username'], $_POST['txtUser']) && !strcmp(md5($_POST['txtPassword']), $data['password_hash'])) {
    //set session
    $_SESSION['username'] = $data['username'];
    $_SESSION['user_id'] = $data['id'];
    $_SESSION['name'] = $data['fname'] . " " . $data['lname'];
    $_SESSION['fname'] = $data['fname'];
    $_SESSION['lname'] = $data['lname'];
    $_SESSION['is_admin'] = $data['is_admin'];

    //redirect to fd_dashboard
    header("location: fd_dashboard.php");
  }
}

?>

<!DOCTYPE html>
<html lang="en">

  <head>
      <meta charset="utf-8">
      <link   href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  </head>

  <nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand mb-0 h1" href="fd_dashboard.php">FMDB</a>
  </nav>

  <br/>

  <body>

    <div class="container">
    <h1>Login</h1>
    
    <form method="post" action="login.php">

      <table>
        
        <tr>
          <td>Username:</td>
          <td><input type="text" name="txtUser"/></td>
        </tr> 
        
        <tr>
          <td>Password:</td>
          <td><input type="password" name="txtPassword"/></td>
        </tr> 

      </table>
      
      <input type="submit" value="Login" class="btn btn-primary"/>
      <a href="register.php" class="btn btn-secondary">Register</a>
    </form>

    </div>
  </body>
</html>
