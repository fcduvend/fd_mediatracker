<?PHP

require_once '../database.php';

$fname = "";
$lname = "";
$username = "";
$password = "";

$fname_error = "";
$lname_error = "";
$username_error = "";
$password_error = "";

//validate first name
if(isset($_POST['txtFname'])) {
  $fname = $_POST['txtFname'];
  if(!strcmp($fname, ""))
    $fname_error = "First name cannot be blank";
} 

//validate last name
if(isset($_POST['txtLname'])) {
  $lname = $_POST['txtLname'];
  if(!strcmp($lname, ""))
    $lname_error = "Last name cannot be blank";
}

//validate username
if(isset($_POST['txtUsername'])) {
  $username = $_POST['txtUsername'];
  if(!strcmp($username, ""))
    $username_error = "Username cannot be blank";

  //check if username is already taken
  $pdo = Database::connect();
  $sql = "SELECT * FROM fd_users WHERE username = ? LIMIT 1";
  $q = $pdo->prepare($sql);
  $q->execute(array($username));
  $data = $q->fetch();

  if(isset($data['username']))
    $username_error = $username . " is already taken";

  Database::disconnect();
}

//validate password
if(isset($_POST['txtPassword'])) {
  $password = $_POST['txtPassword'];
  if(!strcmp($password, ""))
    $password_error = "Password cannot be blank";
}

//insert into database
if(!empty($_POST)) {
  if(!strcmp($fname_error, "") && !strcmp($lname_error, "") && !strcmp($username_error, "") && !strcmp($password_error, "")) {
    $pdo = Database::connect();
    $sql = "INSERT INTO fd_users (fname, lname, username, password_hash) VALUES (?, ?, ?, ?)";
    $q = $pdo->prepare($sql);

    $q->execute(array($fname, $lname, $username, md5($password)));
    Database::disconnect();
    header("location: login.php");
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
    <h1>Register</h1>
    
    <form method="post" action"register.php">
      
      <table>
        
        <tr>
          <td>First Name:</td>
          <td><input type="text" name="txtFname" value="<?PHP echo $fname;?>"/></td>
          <td><?PHP echo $fname_error;?></td>
        </tr>
        
        <tr>
          <td>Last Name:</td>
          <td><input type="text" name="txtLname" value="<?PHP echo $lname;?>"/></td>
          <td><?PHP echo $lname_error;?></td>
        </tr>
        
        <tr>
          <td>Username:</td>
          <td><input type="text" name="txtUsername" value="<?PHP echo $username;?>"/></td>
          <td><?PHP echo $username_error;?></td>
        </tr>
        
        <tr>
          <td>Password:</td>
          <td><input type="password" name="txtPassword"/></td>
          <td><?PHP echo $password_error;?></td>
        </tr>
      </table>

      <input type="submit" class="btn btn-primary" value="Register"/>
      <a href="login.php" class="btn btn-secondary">Back</a>

    </form>
    </div>

  </body>
</html>
