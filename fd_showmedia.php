<?PHP
require 'session.php';
include '../database.php';
require_once 'rating.php';

//if no get or post redirect to fd_listmedia.php
if(empty($_GET) && empty($_POST))
  header("location: fd_listmedia.php");

//get media info and category
$pdo = Database::connect();
$sql = "SELECT fd_media.*, fd_categories.category_name " .
       "FROM fd_media " .
       "INNER JOIN fd_categories ON fd_media.category_id = fd_categories.id " .
       "WHERE fd_media.id = ?";

$q = $pdo->prepare($sql);

//execute using either SESSION or GET
if(empty($_GET) && $_SESSION['showid'] != null && strcmp($_SESSION['showid'], ""))
{
  $q->execute(array($_SESSION['showid']));
  $_SESSION['showid'] = null;
}
else
  $q->execute(array($_GET['showid']));

$data = $q->fetch();

$showid = $data['id'];

//user rates show
if(isset($_POST['rating'])) {
  //check if the user has already rated the show
  $postpdo = Database::connect();
  $postsql = "SELECT * FROM fd_user_favorites WHERE user_id = ? AND media_id = ?";
  $postq = $postpdo->prepare($postsql);
  $postq->execute(array($_SESSION['user_id'], $showid));
  $postdata = $postq->fetch();

  //if they haven't, create a new record in fd_user_favorites
  if(empty($postdata)) {
    $postsql = "INSERT INTO fd_user_favorites (user_id, media_id, rating) VALUES (?, ?, ?)";
    $postq = $postpdo->prepare($postsql);
    $postq->execute(array($_SESSION['user_id'], $showid, $_POST['rating']));
  } else {
    //update rating if they've already rated the show
    $postsql = "UPDATE fd_user_favorites SET rating = ? WHERE user_id = ? AND media_id = ?";
    $postq = $postpdo->prepare($postsql);
    $postq->execute(array($_POST['rating'], $_SESSION['user_id'], $showid));
  }
}

$creatorid = $data['creator_id'];
$showname = $data['name'];
$premier = $data['premier'];
$description = $data['description'];
$category = $data['category_name'];
$logo = "<img style='height: 100px;' src='data:image/jpeg;base64," . base64_encode($data['logo']) . "'>";
$rating = getRatingForShowId($data['id']);

$_SESSION['creator_id'] = $creatorid;

if($showid != null && strcmp($showid, ""))
{
  $_SESSION['showid'] = $showid;
}

/*
 * If the current user is who originally added the show to the database, add a button so that they can update it
 */
function addUpdateButton() {
  if(!strcmp($_SESSION['user_id'], $_SESSION['creator_id'])) {
    echo '<br/><a href="fd_update_media.php?showid=' . $_SESSION['showid'] . '" class="btn btn-primary">Update</a><br/><br/>';
  }
}

require 'header.php';
?>

  <body>
    <div class="container">
      
      <?PHP addUpdateButton(); ?>    
      
      <h1><?PHP echo $showname;?></h1>
      
      <?PHP echo $logo;?>
      
      <h3>Average Rating</h3>
      <p><?PHP echo $rating;?></p>

      <h3>Rate This Show</h3>
      <form method="post" action="fd_showmedia.php">
      
        <input type="radio" name="rating" value="1"> 1</input>
        <input type="radio" name="rating" value="2"> 2</input>
        <input type="radio" name="rating" value="3"> 3</input>
        <input type="radio" name="rating" value="4"> 4</input>
        <input type="radio" name="rating" value="5"> 5</input>
        
        <input type="submit" value="Submit Rating" class="btn btn-secondary"/>

      </form>      

      <h3>Premier Date</h3>
      <p><?PHP echo $premier;?></p>
      <h3>Category</h3>
      <p><?PHP echo $category;?></p>
      <h3>Description</h3>
      <p><?PHP echo $description;?></p>

      <a href="<?PHP
        //either go back to fd_dashboard or fd_list_media
        if(isset($_SESSION['back']))
          echo $_SESSION['back'];
        else
          echo "fd_list_media.php";
      ?>" class="btn btn-primary">Back</a>

    </div>
  </body>
</html>
