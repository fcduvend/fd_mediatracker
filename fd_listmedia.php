
<?PHP
require_once '../database.php';
require_once 'rating.php';

function renderTable() {
  $pdo = Database::connect();
  $sql = "SELECT * FROM fd_media INNER JOIN fd_categories ON fd_media.category_id = fd_categories.id ORDER BY fd_media.name";

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

    <h1>Media</h1>

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
