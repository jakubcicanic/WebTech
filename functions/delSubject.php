<?php 
require_once('../config.php');
session_start();
if (($_SESSION['admin'] === null) AND ($_SESSION['logged'] === null)) {
    header("location: ../index.php");
}  

$predmet =  $_POST['predmet'];
$rocnik =  $_POST['rocnik'];

$conn = new mysqli($dbUrl, $dbLogin, $dbPass, $dbName);
$sql = "DELETE FROM Predmety WHERE Predmet = '" .$predmet. "' AND Rocnik = '" . $rocnik."' ";

$conn->query($sql);
header("location: ../state.php");
?>