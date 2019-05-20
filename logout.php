<?php
session_start();
unset($_SESSION['logged']);
unset($_SESSION['admin']);
unset($_SESSION["loggedUserName"]);
$_SESSION['errorMsg'] = 'Logged Out';
header("location: index.php");
?>