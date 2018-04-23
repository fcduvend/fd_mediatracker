<?PHP
require '../database.php';
require 'session.php';

//redirect back to dashboard if the user is not an admin
if($_SESSION['is_admin'] != 1)
  header("location: fd_dashboard.php");

/*
 * Render table of users
 */
function renderTable() {
  $pdo = Database::connect();
  $sql = "SELECT * FROM fd_users";
  $q = $pdo->prepare($sql);
  $q->execute();

  foreach($q->fetchAll() as $row) {
    echo "<tr>";
    echo "<td>" . $row['fname'] . "</td>";
    echo "<td>" . $row['lname'] . "</td>";
    echo "<td>" . $row['username'] . "</td>";
    echo "</tr>";
  }
}

require 'header.php';
?>

    <div class="container">
      <h1>All Users</h1>
      <table class="table">
        <tr>
          <th>First Name</th>
          <th>Last Name</th>
          <th>User Name</th>
        </tr>
        <?PHP renderTable();?> 
      </table>
    </div>
  </body>
</html>
