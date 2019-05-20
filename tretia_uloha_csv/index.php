<?php
session_start();
require_once('../lang.php');
require_once('../config.php');

if (!isset($_SESSION['logged'])) {
    $_SESSION['errorMsg'] = $language[$_SESSION["lang"]]['e0'];
    header('location: index.php');
}



?>


<!DOCTYPE HTML>
<html>
<head>
    <title>Finálne Zadanie</title>
    <meta charset="utf-8">

    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/button.css">
    <link rel="stylesheet" type="text/css" href="../css/table.css">

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
                        echo '<li><a href="../index.php">'.$language[$_SESSION["lang"]][6].'</a></li>';
                        //echo '<li><a href="logout.php">'.$language[$_SESSION["lang"]][1].'</a></li>';
                        echo '<li><a href="../state.php">'.$language[$_SESSION["lang"]][2].'</a></li>';
                        echo '<li><a href="../rating.php">'.$language[$_SESSION["lang"]][3].'</a></li>';
                        echo '<li><a href="../tretia_uloha_csv/index.php" class="active">Miro</a></li>';
                        if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
                            echo '<li><a href="../generating.php">'.$language[$_SESSION["lang"]][4].'</a></li>';
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
                echo '<li><a href="../langSwitch.php/?redirectedFrom=0"><img src="https://lipis.github.io/flag-icon-css/flags/4x3/sk.svg" height="24" width="32" ></a></li>';
            }
            else if ($_SESSION['lang'] == 0)
            {
                echo '<li><a href="../langSwitch.php/?redirectedFrom=0"><img src="https://lipis.github.io/flag-icon-css/flags/4x3/gb.svg" height="24" width="32"></a></li>';
            }
            ?>
        </ul>
    </nav>

    <nav class="main-nav2">
        <ul>

            <?php
            if (isset($_SESSION['logged'])) {
                echo '<li><a href="../logout.php" style="margin-top: 50px;">'.$language[$_SESSION["lang"]][1].'</a></li>';
                echo '<li><a href="../index.php">'.$_SESSION["loggedUserName"].'</a></li>';
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
</section>



<section id="main-content">

    <div id="tables">

<h1><?php echo $language[$_SESSION["lang"]][16];?></h1>
        <br>
<form method="POST" action='load.php' enctype="multipart/form-data">

    <?php echo $language[$_SESSION["lang"]][17];?>
    <br>
    <input type="text" name="char">
    <br>
    <?phpecho $language[$_SESSION["lang"]][18];?>
    <br>
    <input type="file" name="sel_file">
    <br>
    <br>
    <button type="submit" name="Submit" class="button pulse"><?php echo $language[$_SESSION["lang"]][19];?></button>
</form>

    </div>
</section>

<footer>
    Dizajn : Richard Borcin
</footer>

</body>
</html>