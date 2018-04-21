<?PHP
require_once '../database.php';
require_once 'rating.php';
require 'session.php';

$_SESSION['back'] = "fd_listmedia.php";

function renderTable() {
  $pdo = Database::connect();
  $sql = "SELECT fd_media.logo, fd_media.name, fd_media.id, fd_categories.category_name " .
         "FROM fd_media " .
         "INNER JOIN fd_categories ON fd_media.category_id = fd_categories.id " .
         "ORDER BY fd_media.name";

  foreach($pdo->query($sql) as $row) {
    echo "<tr>";
    echo "<td><img style='height: 75px;' src='data:image/jpeg;base64," . base64_encode($row['logo']) . "'></td>";
    echo "<td><a href=\"fd_showmedia.php?showid=" . $row['id'] . "\">" . $row['name'] . "</a></td>";
    echo "<td>" . $row['category_name'] . "</td>";
    echo "<td>" . getRatingForShowId($row['id']) . "</td>";
    echo "</tr>";
  }

  Database::disconnect();
}

require 'header.php';
?>

<body>
  <div class="container">
    
  <a href="fd_dashboard.php"><?PHP echo $_SESSION['username'];?> Dashboard View</a>

    <h1>Media</h1>
    
    <a href="fd_createshow.php" class="btn btn-primary">Add Show</a>

    <table class="table">
      <tr>
        <th>Logo</th>
        <th>Name</th>
        <th>Category</th>
        <th>Rating</th>
      </tr>
      <?PHP renderTable(); ?>
    </table>
  </div>
  </body>
</html>
