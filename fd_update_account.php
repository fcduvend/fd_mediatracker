<?PHP
require '../database.php';
require 'session.php';

$fname = "";
$lname = "";

$fname_error = "";
$lname_error = "";
$password_error = "";
$newpassword_error = "";
$confirmnewpassword_error = "";


if(empty($_POST)) {
  $fname = $_SESSION['fname'];
  $lname = $_SESSION['lname'];
} else {

  $fname = $_POST['txtFname'];
  $lname = $_POST['txtLname'];

  $password = $_POST['txtOldPassword'];
  $newpassword = $_POST['txtNewPassword'];
  $confirmnewpassword = $_POST['txtConfirmNewPassword'];

  $noerrors = true;
  $updatepassword = true;

  if(!strcmp($fname, "")) {
    $fname_error = "First name cannot be blank";
    $noerrors = false;
  }

  if(!strcmp($lname, "")) {
    $lname_error = "Last name cannot be blank";
    $noerrors = false;
  }

  if(!strcmp($password, "") && !strcmp($newpassword, "") && !strcmp($confirmnewpassword, "")) {
    $updatepassword = false;
  } else {
    if(strcmp($newpassword, $confirmnewpassword)) {
      $newpassword_error = "Passwords do not match";
      $updatepassword = false;
    } else {
      $pdo = Database::connect();
      $sql = "SELECT password_hash FROM fd_users WHERE id = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($_SESSION['user_id']));
      $dbpassword_hash = $q->fetch()['password_hash'];
      Database::disconnect();

      $form_password_hash = md5($password);

      if(strcmp($dbpassword_hash, $form_password_hash)) {
        $password_error = "Invalid password";
        $updatepassword = false;
      }
    }
  }

  if($updatepassword && $noerrors) {
    $pdo = Database::connect();
    $sql = "UPDATE fd_users SET fname = ?, lname = ?, password_hash = ? WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($fname, $lname, md5($newpassword), $_SESSION['user_id']));
    Database::disconnect();

    $_SESSION['fname'] = $fname;
    $_SESSION['lname'] = $lname;
    $_SESSION['name'] = $fname . " " . $lname;
    $_SESSION['account_update_flag'] = true;
    header('location: fd_dashboard.php');
  } else if($noerrors) {
    $pdo = Database::connect();
    $sql = "UPDATE fd_users SET fname = ?, lname = ? WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($fname, $lname, $_SESSION['user_id']));
    Database::disconnect();

    $_SESSION['fname'] = $fname;
    $_SESSION['lname'] = $lname;
    $_SESSION['name'] = $fname . " " . $lname;
    $_SESSION['account_update_flag'] = true;
    header('location: fd_dashboard.php');
  }
}


require 'header.php';
?>

<body>

<div class="container">

<h1>Update Account</h1>

<form method="post" action="fd_update_account.php">
  
  <table class="table">
    
    <tr>
      <td>First Name:</td>
      <td><input type="text" name="txtFname" id="txtFname" value="<?PHP echo $fname;?>"/></td>
      <td><?PHP echo $fname_error;?></td>
    </tr>

    <tr>
      <td>Last Name:</td>
      <td><input type="text" name="txtLname" id="txtLname" value="<?PHP echo $lname;?>"/></td>
      <td><?PHP echo $lname_error;?></td>
    </tr>

  </table>

  <h3>Change Password</h3>
  <p>Password will not change if left blank</p>  

  <table class="table">

    <tr>
      <td>Old Password:</td>
      <td><input type="password" name="txtOldPassword" id="txtOldPassword"/></td>
      <td><?PHP echo $password_error;?></td>
    </tr>

    <tr>
      <td>New Password:</td>
      <td><input type="password" name="txtNewPassword" id="txtNewPassword"/></td>
      <td><?PHP echo $newpassword_error;?></td>
    </tr>

    <tr>
      <td>Confirm New Password:</td>
      <td><input type="password" name="txtConfirmNewPassword" id="txtConfirmNewPassword"/></td>
      <td class=""><?PHP echo $confirmnewpassword_error;?></td>
    </tr>

  </table>
  
  <input type="submit" value="Update Account" class="btn btn-primary"/>
  <a href="fd_dashboard.php" class="btn btn-danger">Cancel</a>

</form>

</div>

</html>
