<?PHP
require_once '../database.php';

function getStars($numstars) {
  if($numstars > 5 || $numstars < 0)
    $numstars = 0;

  $whitestars = 5 - $numstars;
  $stars = "";
  for(;$numstars > 0; $numstars--)
    $stars .= "&#x2605";

  for(;$whitestars > 0; $whitestars--)
    $stars .= "&#x2606";

  return $stars;
}

function getRatingForShowId($showid) {
  $pdo = Database::connect();
  $sql = "SELECT * FROM fd_user_favorites WHERE fd_user_favorites.media_id = ?";
  $q = $pdo->prepare($sql);
  $q->execute(array($showid));

  $ct = 0;
  $rating = 0;
  foreach($q->fetchAll() as $row) {
    $rating = ((int)$row['rating'] + ($ct * $rating)) / (++$ct);
  }

  return $ct > 0 ? getStars((int)$rating) : getStars(5);
}

?>
