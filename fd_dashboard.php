
<?PHP
include 'session.php';
require_once '../database.php';
require_once 'rating.php';

$user = $_SESSION['name'];
$is_admin = $_SESSION['is_admin'];
$_SESSION['back'] = "fd_dashboard.php";

/*
 * Get user's ratings from db.  Display shows that they've rated along with their rating
 */
function renderUserRatings() {
  $pdo = Database::connect();
  $sql = "SELECT fd_media.*, fd_categories.category_name, innerquery.* " .
         "FROM fd_media, " .
         "fd_categories, " .
         "(SELECT fd_user_favorites.media_id, fd_user_favorites.rating " .
         " FROM fd_user_favorites " .
         " WHERE fd_user_favorites.user_id = ?) AS innerquery " .
         "WHERE fd_media.id = innerquery.media_id AND fd_media.category_id = fd_categories.id";

  $q = $pdo->prepare($sql);
  $q->execute(array($_SESSION['user_id']));

  //render as rows
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

/*
 * Get all shows that the user has created
 */
function renderUserSubmissions() {
  $pdo = Database::connect();
  $sql = "SELECT fd_media.*, fd_categories.category_name " .
         "FROM fd_media " .
         "INNER JOIN fd_categories ON fd_categories.id = fd_media.category_id " .
         "WHERE fd_media.creator_id = ?";

  $q = $pdo->prepare($sql);
  $q->execute(array($_SESSION['user_id']));

  //render as rows
  foreach($q->fetchAll() as $row) {
    echo "<tr>";
    echo "<td><img style='height: 75px;' src='data:image/jpeg;base64," . base64_encode($row['logo']) . "'></td>";
    echo "<td><a href=\"fd_showmedia.php?showid=" . $row['id'] . "\">" . $row['name'] . "</a></td>";
    echo "<td>" . $row['category_name'] . "</td>";
    echo "<td>" . getRatingForShowId($row['id']) . "</td>";
    echo "</tr>";
  }
}

/*
 * Display alerts from SESSION
 */
function displayAlerts() {
  //show alert when the user successfully updates their account
  if($_SESSION['account_update_flag'] == true) {
    echo '<br/><br/><div class="alert alert-success" role="alert">Successfully updated account information</div>';

    //lower flag so that alert doesn't display when the page is reloaded
    $_SESSION['account_update_flag'] = false;
  }
}

include 'header.php';
?>

    <div class="container">

      <?PHP
      displayAlerts();
      
      //add link to view users page if admin
      if($is_admin == 1)
        echo '<a href="fd_list_users.php" class="btn btn-danger">Admin: View Users</a>';
      ?>
      
      <h3>Welcome <?PHP echo $user?></h3>
      
      <a href="fd_showmedia.php">View all shows</a>
      
      <!--SHOWS THE USER HAS RATED-->
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

      <!--SHOWS THE USER HAS ADDED-->
      <h4>Shows you have added</h4>
      <table class="table">
        <tr>
          <th>Logo</th>
          <th>Name</th>
          <th>Category</th>
          <th>Average Rating</th>
        </tr>
        <?PHP renderUserSubmissions();?>
      </table>
    </div>

  </body>

</html>
