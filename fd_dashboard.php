
<?PHP
include 'session.php';
require_once '../database.php';
require_once 'rating.php';

$user = $_SESSION['name'];
$_SESSION['back'] = "fd_dashboard.php";

function renderUserRatings() {
  $pdo = Database::connect();
  $sql = "SELECT fd_media.id, fd_media.logo, fd_media.name, fd_categories.category_name, fd_user_favorites.rating " .
         "FROM fd_media " .
         "INNER JOIN fd_user_favorites ON fd_user_favorites.media_id = fd_media.id ".
         "INNER JOIN fd_categories ON fd_categories.id = fd_media.id " .
         "WHERE fd_user_favorites.user_id = ?";

  $q = $pdo->prepare($sql);
  $q->execute(array($_SESSION['user_id']));

  foreach($q->fetchAll() as $row) {
    echo "<tr>";
    echo "<td><img style='height: 75px;' src='data:image/jpeg;base64," . base64_encode($row['logo']) . "'></td>";
    echo "<td><a href=\"fd_showmedia.php?showid=" . $row['id'] . "\">" . $row['name'] . "</a></td>";
    echo "<td>" . $row['category_name'] . "</td>";
    echo "<td>" . getStars($row['rating']) . "</td>";
    echo "<td>" . getRatingForShowId($row['id']) . "</td>";
    echo "</tr>";
  }
}

function displayAlerts() {
  if($_SESSION['account_update_flag'] == true) {
    echo '<br/><br/><div class="alert alert-success" role="alert">Successfully updated account information</div>';
    $_SESSION['account_update_flag'] = false;
  }
}

include 'header.php';
?>


  <body>

    <div class="container">
      
      <a href="logout.php" class="btn btn-danger">Logout</a>
      <a href="fd_update_account.php" class="btn btn-secondary">Update Account</a>

      <?PHP displayAlerts();?>
      
      <h3>Welcome <?PHP echo $user?></h3>
      
      <a href="fd_showmedia.php">View all shows</a>
      
      <h4>Shows you have rated</h4>
      <table class="table">
        <tr>
          <th>Logo</th>
          <th>Name</th>
          <th>Category</th>
          <th>Your Rating</th>
          <th>Average Rating</th>
        </tr>
        <?PHP renderUserRatings();?>
      </table>

    </div>

  </body>

</html>
