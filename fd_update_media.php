<?PHP
require '../database.php';
require 'session.php';

$pdo = Database::connect();

$sql = "SELECT fd_media.*, fd_categories.category_name " .
       "FROM fd_media " .
       "INNER JOIN fd_categories ON fd_media.category_id = fd_categories.id " .
       "WHERE fd_media.id = ?";

$q = $pdo->prepare($sql);
$q->execute(array($_GET['showid']));
$data = $q->fetch();
$creator_id = $data['creator_id'];

if(strcmp($_SESSION['user_id'], $creator_id)) {
  header('location: fd_listmedia.php');
}

$name_error = "";
$description_error = "";

$id = $data['id'];
$category_id = $data['category_id'];
$name = $data['name'];
$description = $data['description'];
$logo = $data['logo'];
$premier = $data['premier'];


if(!empty($_POST)) {
  if($_FILES['userfile']['error'] == UPLOAD_ERR_OK &&
    $_FILES['userfile']['size'] > 0 &&
    $_FILES['userfile']['size'] <= 1000000 &&
    $_FILES['userfile']['type'] == "image/jpeg" &&
    !strcmp($_POST['name'], "")) {

    $filename = $_FILES['userfile']['name'];
    $tmpname = $_FILES['userfile']['tmp_name'];
    
    $content = file_get_contents($tmpname);

    $pdo = Database::connect();
    $sql = "SELECT id FROM fd_categories WHERE category_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($_POST['cboCategory']));
    $catid = $q->fetch()['id'];

    $sql = "UPDATE fd_media SET name = ?, category_id = ?, description = ?, logo = ?, premier = ? WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($_POST['txtName'], $catid, $_POST['txtDesc'], $content, $_POST['premier'], $id));

  } else {
    $pdo = Database::connect();
    $sql = "SELECT id FROM fd_categories WHERE category_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($_POST['cboCategory']));
    $catid = $q->fetch()['id'];

    $sql = "UPDATE fd_media SET name = ?, category_id = ?, description = ?, premier = ? WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($_POST['txtName'], $catid, $_POST['txtDesc'], $_POST['premier'], $id));
  }

  header('location: fd_showmedia.php?showid=' . $id);
}

function renderCategories() {
  $pdo = Database::connect();
  $sql = "SELECT * FROM fd_categories";
  $q = $pdo->prepare($sql);
  $q->execute();

  foreach($q->fetchAll() as $row) {
    echo "<option name=\"" . $row['id'] . "\">" . $row['category_name'] . "</option>";
  }
}
require 'header.php';
?>


<body>

<div class="container">

  <h1>Update Media</h1>

  <form method="post" action="fd_update_media.php?showid=<?PHP echo $_GET['showid'];?>" enctype="multipart/form-data">
    
    <table class="table">
    
      <tr>
        <td>Show Name:</td>
        <td>
          <input type="text" name="txtName" id="txtName" value="<?PHP echo $name;?>"/>
        </td>
        <td><?PHP echo $name_error;?></td>
      </tr>

      <tr>
        <td>Premier Date:</td>
        <td>
          <input type="date" name="premier" value="<?PHP echo $premier;?>"/>
        </td>
      </tr>

      <tr>
        <td>Category:</td>
        <td>
          <select name="cboCategory">
            <?PHP renderCategories();?>
          </select>
        </td>
      </tr>

      <tr>
        <td>Logo (.jpg):</td>
        <td>
          <input name="userfile" type="file" id="userfile" accept=".jpg"/>
        </td>
      </tr>

      <tr>
        <td>Description:</td>
        <td>
          <textarea name="txtDesc" id="txtDesc" style="width: 75%"><?PHP echo $description;?></textarea>
        </td>
      </tr>
      
    </table>

    <input type="submit" class="btn btn-primary" value="Update"/>
    <a href="fd_showmedia.php?showid=<?PHP echo $_GET['showid'];?>" class="btn btn-danger">Back</a>

  </form>

</div>

</body>

</html>
