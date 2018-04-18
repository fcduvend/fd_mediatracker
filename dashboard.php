
<?PHP
include 'session.php';
require_once '../database.php';

echo $_SESSION['username'];

include 'header.php';
?>


  <body>

    <a href="logout.php" class="btn btn-danger">Logout</a>

  </body>

</html>
