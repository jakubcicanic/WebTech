<?php
session_start();
//['logged'] -> len prihlásený
//['admin'] -> ak je aj admin
//['lang'] -> jazyk, 0 -> SK, 1 -> EN

require_once('lang.php');
require_once('config.php');
require_once('functions/createDatabase.php');

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang']=0;
}

setupDatabase($dbUrl, $dbLogin, $dbPass, $dbName);

?>


<!DOCTYPE HTML>
<html>
<head>
    <title>Finálne Zadanie</title>
    <meta charset="utf-8">

    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/btn.css">
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
                        echo '<li><a href="index.php" class = "active">'.$language[$_SESSION["lang"]][6].'</a></li>';
                        echo '<li><a href="login.php">'.$language[$_SESSION["lang"]][0].'</a></li>';
                    } else {
                        echo '<li><a href="index.php" class = "active">'.$language[$_SESSION["lang"]][6].'</a></li>';
                        //echo '<li><a href="logout.php">'.$language[$_SESSION["lang"]][1].'</a></li>';
                        echo '<li><a href="state.php">'.$language[$_SESSION["lang"]][2].'</a></li>';
                        echo '<li><a href="rating.php">'.$language[$_SESSION["lang"]][3].'</a></li>';
                        echo '<li><a href="tretia_uloha_csv/index.php">Miro</a></li>';
                        if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
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
                echo '<li><a href="langSwitch.php/?redirectedFrom=0"><img src="https://lipis.github.io/flag-icon-css/flags/4x3/sk.svg" height="24" width="32" ></a></li>';
            }
            else if ($_SESSION['lang'] == 0)
            {
                echo '<li><a href="langSwitch.php/?redirectedFrom=0"><img src="https://lipis.github.io/flag-icon-css/flags/4x3/gb.svg" height="24" width="32"></a></li>';
            }
            ?>
        </ul>
    </nav>

    <nav class="main-nav2">
        <ul>

            <?php
            if (isset($_SESSION['logged'])) {
           echo '<li><a href="logout.php" style="margin-top: 50px;">'.$language[$_SESSION["lang"]][1].'</a></li>';
                echo '<li><a href="index.php">'.$_SESSION["loggedUserName"].'</a></li>';
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
    <h2><?php echo $language[$_SESSION["lang"]][8] ?>
        <h1 style="font-size: 30px"><strong><?php echo $language[$_SESSION["lang"]][6] ?></strong></h1>
    </h2>
</section>
<section id="main-content">

    <div id="error" style="position:absolute" >
        <?php

        if (isset($_SESSION['errorMsg'])) {
            //echo 'skrr';
            echo $_SESSION['errorMsg'];
            unset($_SESSION['errorMsg']);
        }
        ?>
    </div>

    <div class="text-intro">

    </div>
</section>

<footer>
    Dizajn : Richard Borcin
</footer>

</body>
</html>