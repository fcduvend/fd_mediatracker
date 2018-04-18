<?PHP

session_start();
if(!isset($_SESSION['username'])) {
  $_SESSION = null;
  session_destroy();
  header("location: login.php");
}

?>
