<?PHP
require_once '../database.php';
require 'session.php';

$txtNameError = "";
$logoError = "";

if(isset($_POST) &&
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

  $sql = "INSERT INTO fd_media (name, category_id, description, logo, premier) VALUES (?, ?, ?, ?, ?)";
  $q = $pdo->prepare($sql);
  $q->execute(array($_POST['txtName'], $catid, $_POST['txtDesc'], $content, $_POST['premier']));

  header('location: fd_listmedia.php');
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

<h1>Add Show</h1>


<form method="post" action="fd_createshow.php" enctype="multipart/form-data">

  <table class="table">
    <tr>
      <td>Show Name:</td>
      <td>
        <input type="text" name="txtName" id="txtName"/>
      </td>
    </tr>

    <tr>
      <td>Premier Date:</td>
      <td>
        <input type="date" name="premier"/>
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
        <textarea name="txtDesc" id="txtDesc" style="width: 75%"></textarea>
      </td>
    </tr>

  <table>

  <input class="btn btn-primary" type="submit" value="Add Show"/>

</form>

<br/>
<br/>

<a href="fd_listmedia.php" class="btn btn-secondary">Back</a>

</div>

</body>

</html>
