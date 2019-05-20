<?php
session_start();
//['logged'] -> len prihlásený
//['admin'] -> ak je aj admin
//['lang'] -> jazyk, 0 -> SK, 1 -> EN


require_once('lang.php');
require_once('config.php');
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang']=0;
}
//$_SESSION['lang']=1;
unset($_SESSION['logged']);
unset($_SESSION['admin']);

//require_once "config.php";

//$loginURL = $gClient->createAuthUrl();
$_SESSION['message'] = '';

/*$servername = "localhost";
$username = "prsp";
$password = "133625";
$dbName = "accounts";*/

$mysqli = new mysqli($dbUrl, $dbLogin, $dbPass, $dbName);
mysqli_set_charset($mysqli, 'utf8');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = '".$username."' AND password = '".$password."' LIMIT 1";


    $result = $mysqli->query($sql);

    if($result->num_rows == 1)
    {
        $row = mysqli_fetch_array($result);
        $id = $row['user_id'];

        $logins_sql = "INSERT INTO logins (user_id, login_type) "
            . "VALUES('$id', 'default')";

        if($mysqli->query($logins_sql) === TRUE){

            $_SESSION['message'] = "Vitaj $username.";

            $_SESSION['id'] = $id;
            header("location: welcome.php");

        }else mysqli_error($mysqli);
    }
    else{
        $_SESSION['message'] = "Nesprávne prihlasovacie údaje" . mysqli_error($mysqli);

    }

}

?>


<!DOCTYPE HTML>
<html>
<head>
    <title>Finálne Zadanie</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/button.css">
    <link rel="stylesheet" type="text/css" href="css/table.css">

</head>
<body>
<header>
    <nav class="main-nav">
        <ul>
            <li>
                <ul>
                    <?php
                    if (!isset($_SESSION['logged'])) {
                        echo '<li><a href="index.php">'.$language[$_SESSION["lang"]][6].'</a></li>';
                        echo '<li><a href="login.php"  class = "active">'.$language[$_SESSION["lang"]][0].'</a></li>';
                    } else {
                        echo '<li><a href="logout.php">'.$language[$_SESSION["lang"]][1].'</a></li>';
                        echo '<li><a href="state.php">'.$language[$_SESSION["lang"]][2].'</a></li>';
                        echo '<li><a href="rating.php">'.$language[$_SESSION["lang"]][3].'</a></li>';
                        if (isset($_SESSION['admin']) && $_SESSION['admin' == 1]) {
                            echo '<li><a href="generating.php">'.$language[$_SESSION["lang"]][4].'</a></li>';
                        }
                    }
                    ?>
                </ul>
            </li>
        </ul>
    </nav>

    <nav class="main-nav3">
        <ul>
            <?php

            if($_SESSION['lang'] == 1)
            {
                echo '<li><a href="langSwitch.php/?redirectedFrom=1"><img src="https://lipis.github.io/flag-icon-css/flags/4x3/sk.svg" height="24" width="32"></a></li>';
            }
            else if ($_SESSION['lang'] == 0)
            {
                echo '<li><a href="langSwitch.php/?redirectedFrom=1"><img src="https://lipis.github.io/flag-icon-css/flags/4x3/gb.svg" height="24" width="32"></a></li>';
            }
            ?>
        </ul>
    </nav>



</header>
<section id="video" class="home">
    <br>
    <br>
    <br>
    <br>
    <h1><b><?php echo $language[$_SESSION["lang"]][7] ?></b></h1>
    <h2><?php echo $language[$_SESSION["lang"]][8] ?></h2>
    <h1 style="font-size: 30px"><strong><?php echo $language[$_SESSION["lang"]][0] ?></strong></h1>
</section>


<div id="tables">

    <hr>
    <br>


    <div class="alert alert-error"><?= $_SESSION['message']?></div>

    <form action="adminLogin.php" method="post" class="form" enctype="multipart/form-data" autocomplete="off" >

        <input title="name" type="text" name="adminLogin" placeholder="Admin Username" required><br/>

        <input title = "pass" type="password" name="adminPass" placeholder="Admin Password" required><br/>


        <button type="submit" class="button pulse">Admin Login</button>

    </form>
    <br>

    <form action="LdapCallback.php" method="post" class="form" enctype="multipart/form-data" autocomplete="off" >

        <input title="name" type="text" name="isUid" placeholder="Username is.stuba.sk" required><br/>

        <input title = "pass" type="password" name="isPass" placeholder="Password is.stuba.sk" required><br/>

        <button type="submit" class="button pulse">Login</button>

    </form>

    <br>

    <footer>

        Dizajn : Richard Borcin

    </footer>

</body>
</html>