<html>
<?php

require_once('config.php');
require_once('functions/createDatabase.php');
$conn = connectToDatabase($dbUrl, $dbLogin, $dbPass, $dbName);

$query = "SELECT DISTINCT tim FROM Tim WHERE predmet ='".$_POST["tim"]."' ORDER by tim ASC";

$result=$conn->query($query);

?>

<option>Select Team</option>
<?php
while($rs=$result->fetch_assoc()){
  ?>

<option value="<?php echo $rs['tim']; ?>" ><?php echo $rs['tim'] ?></option>
<?php
}


?>

</html>
