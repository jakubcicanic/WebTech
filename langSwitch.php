<?php
session_start();

echo $_SESSION['lang'];
echo $_GET['redirectedFrom'];
$redirectedFrom = $_GET['redirectedFrom'];
/*
 * 0 = index.php
 * 1 = login.php
 * 2 = state.php
 * 3 = rating.php
 * 4 = generating.php
 *
 * English = 1
 * Slovak = 0
 */

if($redirectedFrom == 0)
{
    if($_SESSION['lang'] === 0)
    {
        $_SESSION['lang'] = 1;

    }else if ($_SESSION['lang'] === 1)
    {
        $_SESSION['lang'] = 0;
    }
    header("location: ../index.php");
}
else if($redirectedFrom == 1)
{
    if($_SESSION['lang'] === 0)
    {
        $_SESSION['lang'] = 1;

    }else if ($_SESSION['lang'] === 1)
    {
        $_SESSION['lang'] = 0;
    }
    header("location: ../login.php");
}
else if($redirectedFrom == 2)
{
    if($_SESSION['lang'] === 0)
    {
        $_SESSION['lang'] = 1;

    }else if ($_SESSION['lang'] === 1)
    {
        $_SESSION['lang'] = 0;
    }
    header("location: ../state.php");
}
else if($redirectedFrom == 3)
{
    if($_SESSION['lang'] === 0)
    {
        $_SESSION['lang'] = 1;

    }else if ($_SESSION['lang'] === 1)
    {
        $_SESSION['lang'] = 0;
    }
    header("location: ../rating.php");
}
else if($redirectedFrom == 4)
{
    if($_SESSION['lang'] === 0)
    {
        $_SESSION['lang'] = 1;

    }else if ($_SESSION['lang'] === 1)
    {
        $_SESSION['lang'] = 0;
    }
    header("location: ../generating.php");
}





?>