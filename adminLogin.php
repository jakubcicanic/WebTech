<?php
session_start();
$adminUName = 'admin';
$adminPass = 'admin';
$_POST['adminLogin'];
$_POST['adminPass'];

if($_POST['adminLogin'] == 'admin' && $_POST['adminPass'] === 'admin')
{
    $_SESSION['admin'] = 1;
    $_SESSION['logged'] = 'admin';
    $_SESSION['errorMsg'] = 'Admin Logged In';
}
else
    {
    $_SESSION['admin'] = 0;
        $_SESSION['errorMsg'] = 'Bad Credentials';
}

header("location: index.php");