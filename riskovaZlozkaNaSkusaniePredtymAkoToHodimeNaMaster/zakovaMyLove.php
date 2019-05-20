<?php
session_start();
//['logged'] -> len prihlásený
//['admin'] -> ak je aj admin
//['lang'] -> jazyk, 0 -> SK, 1 -> EN

require_once('lang.php');
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang']=0;
}
$_SESSION['lang']=1;
unset($_SESSION['logged']);
$_SESSION['admin']=1;


?>


<!DOCTYPE HTML>
<html>
<head>
    <title>Finálne Zadanie</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="https://147.175.121.210:4430/finalWeb/css/style.css">
</head>
<body>
<header>
    <nav class="main-nav">
        <ul>
            <li>
                <ul>
                    <?php
                    if (!isset($_SESSION['logged'])) {
                        echo '<li><a class = "active" href="login.php">'.$language[$_SESSION["lang"]][0].'</a></li>';
                    } else {
                        echo '<li><a href="logout.php">'.$language[$_SESSION["lang"]][1].'</a></li>';
                        echo '<li><a href="state.php">'.$language[$_SESSION["lang"]][2].'</a></li>';
                        echo '<li><a href="rating.php">'.$language[$_SESSION["lang"]][3].'</a></li>';
                        if (isset($_SESSION['admin'])) {
                            echo '<li><a href="generating.php">'.$language[$_SESSION["lang"]][4].'</a></li>';
                        }
                    }
                    ?>
                </ul>
            </li>
        </ul>
    </nav>
</header>
<section id="video" class="home">
    <br>
    <br>
    <br>
    <br>
    <h1><b>Finálne Zadanie</b></h1>
    <h2>Webové technológie</h2>
</section>
<section id="main-content">
    <div class="text-intro">

    </div>
</section>

<footer>

    Dizajn : Richard Borcin

</footer>

</body>
</html>