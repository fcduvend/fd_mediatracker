<?PHP
include '../database.php';
require_once 'rating.php';

if(empty($_GET))
  header("location: fd_listmedia.php");

$pdo = Database::connect();
$sql = "SELECT * FROM fd_media INNER JOIN fd_categories ON fd_media.category_id = fd_categories.id WHERE fd_media.id = ?";
$q = $pdo->prepare($sql);
$q->execute(array($_GET['showid']));
$data = $q->fetch();

$showname = $data['name'];
$premier = $data['premier'];
$description = $data['description'];
$category = $data['category_name'];
$logo = "<img style='height: 100px;' src='data:image/jpeg;base64," . base64_encode($data['logo']) . "'>";
$rating = getRatingForShowId($data['id']);

require 'header.php';
?>

  <body>
    <div class="container">
      
      <h1><?PHP echo $showname;?></h1>
      
      <?PHP echo $logo;?>
      
      <h3>Average Rating</h3>
      <p><?PHP echo $rating;?></p>
      <h3>Premier Date</h3>
      <p><?PHP echo $premier;?></p>
      <h3>Category</h3>
      <p><?PHP echo $category;?></p>
      <h3>Description</h3>
      <p><?PHP echo $description;?></p>

      <a href="fd_listmedia.php" class="btn btn-primary">Back</a>

    </div>
  </body>
</html>
