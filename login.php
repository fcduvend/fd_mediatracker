<?PHP
require_once '../database.php';

session_destroy();
$_SESSION = null;
session_start();
if(isset($_POST['txtUser']) && isset($_POST['txtPassword'])) {
  $pdo = Database::connect();
  $sql = "SELECT * FROM fd_users WHERE username = ? LIMIT 1";
  $q = $pdo->prepare($sql);
  $q->execute(array($_POST['txtUser']));
  $data = $q->fetch();
  echo md5($_POST['txtPassword']);
  if(!strcmp($data['username'], $_POST['txtUser']) && !strcmp(md5($_POST['txtPassword']), $data['password_hash'])) {
    $_SESSION['username'] = $data['username'];
    $_SESSION['user_id'] = $data['id'];
    $_SESSION['name'] = $data['fname'] . " " . $data['lname'];
    $_SESSION['fname'] = $data['fname'];
    $_SESSION['lname'] = $data['lname'];

    header("location: fd_dashboard.php");
  }
}


include 'header.php';
?>


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

    </form>

    <a href="register.php" class="btn btn-secondary">Register</a>
    </div>

  </body>

</html>
