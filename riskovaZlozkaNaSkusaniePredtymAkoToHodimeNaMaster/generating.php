<?php
session_start();
require_once('lang.php');
require_once('config.php');
require_once('functions/createDatabase.php');
if (!isset($_SESSION['admin'])) {
    $_SESSION['errorMsg'] = $language[$_SESSION["lang"]]['e0'];
    header('location: index.php');}


    $conn = connectToDatabase($dbUrl, $dbLogin, $dbPass, $dbName);
    $teamsSql = 'SELECT DISTINCT tim FROM `Tim`  ORDER by tim ASC';

    $result = $conn->query($teamsSql);


if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $addPointsSql = 'UPDATE timy_body SET body= '.$_GET['teamPoints'].' WHERE tim= '.$_SESSION['currentTeam']." AND predmet = '".$_SESSION['currentSubject']."'";


    if ($conn->query($addPointsSql) === TRUE) {

        $_SESSION['errorMsg'] = $language[$_SESSION["lang"]][52].$_GET['teamPoints'].$language[$_SESSION["lang"]][53].$_SESSION['currentTeam'];

    } else {
        //$_SESSION['errorMsg'] = mysqli_error($conn);
    }
    //header("location: generating.php");
}else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['adminAccept'])) {
        $accepted = "UPDATE timy_body SET  adminSuhlas = TRUE WHERE tim = " . $_SESSION['currentTeam'] . " AND predmet = '" . $_SESSION['currentSubject'] . "'";
        if ($conn->query($accepted) === TRUE) {
            $_SESSION['errorMsg'] = $language[$_SESSION["lang"]][54] . $_SESSION['currentTeam'];
            unset($_POST['adminAccept']);
        } else $_SESSION['errorMsg'] = mysqli_error($conn);

    } else if (isset($_POST['adminDecline'])) {
        $accepted = "UPDATE timy_body SET  adminSuhlas = FALSE WHERE tim = " . $_SESSION['currentTeam'] . " AND predmet = '" . $_SESSION['currentSubject'] . "'";
        if ($conn->query($accepted) === TRUE) {
            $_SESSION['errorMsg'] = $language[$_SESSION["lang"]][55] . $_SESSION['currentTeam'];
            unset($_POST['adminDecline']);
        } else $_SESSION['errorMsg'] = mysqli_error($conn);


    }
}

$mysqli = new mysqli($dbUrl, $dbLogin, $dbPass, $dbName);
mysqli_set_charset($mysqli, 'utf8');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$predmet = "Webiki";
$query1 = "SELECT count(*) FROM `Tim` where predmet = '" . $_SESSION['currentSubject'] . "'";
$query2 = "SELECT count(*) FROM `Tim` where Suhlas = 1 and predmet = '" . $_SESSION['currentSubject'] . "'";
$query3 = "SELECT count(*) FROM `Tim` where Suhlas = 0 and predmet = '" . $_SESSION['currentSubject'] . "'";
$query4 = "SELECT count(*) FROM `Tim` WHERE Suhlas IS NULL and predmet = '" .  $_SESSION['currentSubject']  . "'";

$query11 = "SELECT COUNT(*) FROM timy_body where predmet = '" .  $_SESSION['currentSubject']  . "'";
$query12 = "SELECT count(*) FROM `timy_body` WHERE adminSuhlas = 1 OR adminSuhlas = 0 and predmet = '" .  $_SESSION['currentSubject']  . "'";
$query13 = "SELECT COUNT(*) FROM `timy_body` WHERE body IS NULL and predmet = '" .  $_SESSION['currentSubject']  . "'";
$query14 = "SELECT count(DISTINCT tim) FROM Tim WHERE Suhlas IS NULL and predmet = '" .  $_SESSION['currentSubject']  . "'";

$result1 = mysqli_query($mysqli,$query1);
$result2 = mysqli_query($mysqli,$query2);
$result3 = mysqli_query($mysqli,$query3);
$result4 = mysqli_query($mysqli,$query4);

$result11 = mysqli_query($mysqli,$query11);
$result12 = mysqli_query($mysqli,$query12);
$result13 = mysqli_query($mysqli,$query13);
$result14 = mysqli_query($mysqli,$query14);
while($row11 = mysqli_fetch_array($result11))
{
    $pocet2 = $row11["COUNT(*)"];
}
while($row = mysqli_fetch_array($result1))
{
    $pocet = $row["count(*)"];
}




?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Finálne Zadanie</title>
    <meta charset="utf-8" >
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/button.css">
    <link rel="stylesheet" type="text/css" href="css/table.css">

    <title>Kolacik</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart()
        {
            var data = google.visualization.arrayToDataTable([
                ['Popis', 'Pocet'],
                <?php
                //toto treba opravit a tahat udaje y $row, ktore potrebujem...to co taham je blbost

                while($row2 = mysqli_fetch_array($result2))
                {
                    echo "['Suhlasi', ".$row2["count(*)"]."],";
                }
                while($row3 = mysqli_fetch_array($result3))
                {
                    echo "['Nesuhlasi', ".$row3["count(*)"]."],";
                }
                while($row4 = mysqli_fetch_array($result4))
                {
                    echo "['Nevyjadreny', ".$row4["count(*)"]."]";
                }
                ?>
            ]);
            var options = {
                title: "Počet ľudí v predmete <?php echo $pocet; ?>",
                is3D:true
                //pieHole: 0.4
            };
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart2);
        function drawChart2()
        {
            var data = google.visualization.arrayToDataTable([
                ['Popis', 'Pocet'],
                <?php
                //toto treba opravit a tahat udaje y $row, ktore potrebujem...to co taham je blbost
                while($row12 = mysqli_fetch_array($result12))
                {
                    echo "['Uzavrete timy', ".$row12["count(*)"]."],";
                }
                while($row13 = mysqli_fetch_array($result13))
                {
                    echo "['Treba sa vyjadrit', ".$row13["COUNT(*)"]."],";
                }
                while($row14 = mysqli_fetch_array($result14))
                {
                    echo "['Tymi s nevyjadrenymi študentami', ".$row14["count(DISTINCT tim)"]."],";
                }
                ?>
            ]);
            var options = {
                title: "Počet tímov <?php echo $pocet2; ?>",
                is3D:true
                //pieHole: 0.4
            };
            var chart = new google.visualization.PieChart(document.getElementById('piechart2'));
            chart.draw(data, options);
        }
    </script>

</head>
<body >


<header>
    <nav class="main-nav">
        <ul>
            <li>
                <ul>
                    <?php
                    if (!isset($_SESSION['logged'])) {
                        echo '<li><a href="index.php" >'.$language[$_SESSION["lang"]][6].'</a></li>';
                        echo '<li><a href="login.php">'.$language[$_SESSION["lang"]][0].'</a></li>';
                    } else {
                        echo '<li><a href="index.php">'.$language[$_SESSION["lang"]][6].'</a></li>';
                       // echo '<li><a href="logout.php">'.$language[$_SESSION["lang"]][1].'</a></li>';
                        echo '<li><a href="state.php">'.$language[$_SESSION["lang"]][2].'</a></li>';
                        echo '<li><a href="rating.php" >'.$language[$_SESSION["lang"]][3].'</a></li>';
                        if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
                            echo '<li><a href="generating.php" class = "active">'.$language[$_SESSION["lang"]][4].'</a></li>';
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
                echo '<li><a href="langSwitch.php/?redirectedFrom=4"><img src="https://lipis.github.io/flag-icon-css/flags/4x3/sk.svg" height="24" width="32" ></a></li>';
            }
            else if ($_SESSION['lang'] == 0)
            {
                echo '<li><a href="langSwitch.php/?redirectedFrom=4"><img src="https://lipis.github.io/flag-icon-css/flags/4x3/gb.svg" height="24" width="32"></a></li>';
            }
            ?>
        </ul>
    </nav>


    <nav class="main-nav2">
        <ul>
            <?php
            if (isset($_SESSION['logged'])) {
                echo '<li><a href="logout.php">'.$language[$_SESSION["lang"]][1].'</a></li>';
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
    <h2><?php echo $language[$_SESSION["lang"]][8] ?></h2>
    <h1 style="font-size: 30px"><strong><?php echo $language[$_SESSION["lang"]][4] ?></strong></h1>
</section>

<section id="main-content">
<div id="tables" class = "table">


    <?php


    if (isset($_SESSION['errorMsg'])) {
        echo '<br>';
        echo $_SESSION['errorMsg'];
        echo '<br>';
        unset($_SESSION['errorMsg']);
    }


/*
$teamNumbers = array();

   if ($result->num_rows > 0) {
// output data of each row
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row['tim'] . "</td>";
            echo "<tr>";

            array_unshift($teamNumbers, $row['tim']);
        }
    }
    echo "</table>";
*/
?>



            <?php

    $subjectSql = "SELECT DISTINCT predmet FROM Tim";
    $teamsSql = "SELECT DISTINCT tim FROM `Tim` WHERE predmet = '".$_SESSION['currentSubject']."'  ORDER by tim ASC";
    $query = $conn->query($teamsSql); // Run your query
    $subjectResult = $conn->query($subjectSql); // Run your query

    echo '<form action="" method="post">';
    echo '<h3 style="font-size: 30px"><b>'.$language[$_SESSION["lang"]][56].'</b></h3>';
    echo '<select name="Subject">'; // Open your drop down box

    // Loop through the query results, outputing the options
    while ($row = mysqli_fetch_array($subjectResult)) {
        echo '<option value="'.$row['predmet'].'">'.$row['predmet'].'</option>';
        echo $row['predmet'];
    }
    echo '</select>';

    echo '&nbsp&nbsp&nbsp';
  // echo '<button type="submit" class="fas fa-arrow-right button pulse"><i class="fas fa-arrow-right" ></i></button>';
   //echo '</form>';

    $currentSubject = $_POST['Subject'];
    $_SESSION['currentSubject'] = $currentSubject;




    echo '<form action="" method="post">';
     echo '<h3 style="font-size: 30px"><b>'.$language[$_SESSION["lang"]][57].'</b></h3>';
    echo '<select name="Team">'; // Open your drop down box
    // Loop through the query results, outputing the options
    while ($row = mysqli_fetch_array($query)) {
        echo '<option value="'.$row['tim'].'">'.$row['tim'].'</option>';
        echo $row['tim'];
    }

    echo '</select>';
    echo '&nbsp&nbsp&nbsp';
    echo '<button type="submit" class="fas fa-arrow-right button pulse"><i class="fas fa-arrow-right" ></i></button>';
    echo '</form>';

    $teamValue = $_POST['Team'];

    $_SESSION['currentTeam'] = $teamValue;
    echo $_SESSION['currentSubject'];
    echo $_SESSION['currentTeam'];

    // echo  $teamValue;

echo '<br><br><hr>';

        if($i < 1)
        {
            echo '<h3 style="font-size: 30px">'.$language[$_SESSION["lang"]][61] . $teamValue . '</h3>';


            $teamSqlDraw = 'SELECT DISTINCT body FROM timy_body where tim = '.$_SESSION['currentTeam']." AND predmet = '".$_SESSION['currentSubject']."'";
            $draw = $conn->query($teamSqlDraw);

            while ($qValues=mysqli_fetch_array($draw))
                if (is_null($qValues['body'])) {
                    echo '<form action="" method="get">';
                    echo '<h1>'.$language[$_SESSION["lang"]][33].'</h1>';
                    echo'<input title="name" type="number" name="teamPoints" placeholder="Team Points" required><br/>';
                    echo '<button type="submit" name="points" class="button pulse">'.$language[$_SESSION["lang"]][34].'</button>';
                    echo '<br>';
                    echo'</form>';
                }else  echo "<h1 style=\"font-size: 25px\">".$qValues['body'].$language[$_SESSION["lang"]][33]."</h1>";




            echo '  <table  class="container" >
                <tr>
                    <th>'.$language[$_SESSION["lang"]][35].'</th>
                    <th>'.$language[$_SESSION["lang"]][36].'</th>
                    <th>'.$language[$_SESSION["lang"]][33].'</th>
                    <th>'.$language[$_SESSION["lang"]][37].'</th>
                 
                <tr>';
        }
        $teamTableSql = 'SELECT meno, email, body, Suhlas FROM `Tim` WHERE  tim = '.$teamValue." AND predmet = '".$currentSubject."'";
        $result = $conn->query($teamTableSql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['meno'] . "</td>";
            if (is_null($row['body'])) {
                echo "<td>".$language[$_SESSION["lang"]][49]."</td>";
            } else if (!is_null($row['body'])) {
                echo "<td>" . $row['body'] . "</td>";
            }
            if (is_null($row['Suhlas'])) {
                echo "<td>".$language[$_SESSION["lang"]][49]."</td>";
            } else if (!is_null($row['Suhlas'])) {
                if ($row['Suhlas'] == 1) echo "<td><i style='font-size:25px; color:green' class=\"fas fa-user-check\"></i></td>";
                else if ($row['Suhlas'] == 0) echo "<td><i style='font-size:25px; color:red'  class=\"fas fa-user-times\"></i></td>";
            }
            echo "<tr>";
        }

    }
echo '</table>';

    $sqlTeamCount = "SELECT COUNT(ID_STUDENT) as pocet FROM Tim where tim =".$_SESSION['currentTeam']." and predmet = '".$_SESSION['currentSubject']."'";
    $countResult = $conn->query($sqlTeamCount);
    $count=mysqli_fetch_array($countResult);
    $teamCount = $count['pocet'];

    $sqlCount = "SELECT COUNT(Suhlas) as pocet FROM Tim where tim = ".$_SESSION['currentTeam']." and predmet = '".$_SESSION['currentSubject']."' and Suhlas is not NULL";
    $suhlasResult = $conn->query($sqlCount);
    $countSuhlas=mysqli_fetch_array($suhlasResult);
    $teamSuhlas = $countSuhlas['pocet'];

    $sqlAdminAccept = "SELECT adminSuhlas, body FROM timy_body where tim = ".$_SESSION['currentTeam']." and predmet = '".$_SESSION['currentSubject']."'";
    $adminResult = $conn->query($sqlAdminAccept);
    $adminSuhlas=mysqli_fetch_array($adminResult);
    $adminRes = $adminSuhlas['adminSuhlas'];
    $teamPoints = $adminSuhlas['body'];


    if($teamCount == $teamSuhlas && is_null($adminRes) && $teamCount != 0) {
        echo'<br>';
        echo '<table class="container" style="width:200px;">';
        echo "<th><form method='post' action='generating.php'><input type = 'hidden' name = 'adminAccept' value = '" . $_SESSION['logged'][0] . "'><button type ='submit' class ='button pulse'><i class=\"fas fa-check-circle\" ></i></button></form></th>";
        echo "<th><form method='post' action='generating.php'><input type = 'hidden' name = 'adminDecline' value = '" . $_SESSION['logged'][0] . "'><button type ='submit' class ='button pulse'><i class=\"fas fa-times-circle\" ></i></button></form></th>";
        echo '</table>';
    }else if (is_null($adminRes) && $teamCount != $teamSuhlas && !is_null($teamPoints))
    {   echo'<br>';
         echo $language[$_SESSION["lang"]][58];
    }else if (!$adminRes && $teamCount == $teamSuhlas && $teamCount != 0)
    {   echo'<br>';
         echo $language[$_SESSION["lang"]][59];
    }else if ($adminRes && $teamCount == $teamSuhlas && $teamCount != 0)
    {
        echo'<br>';
        echo $language[$_SESSION["lang"]][60];
    }

    ?>


</div>

    <div style="width:900px;">
        <h3 align="center">Robime kolace</h3>
        <br />
        <div id="piechart" style="width: 900px; height: 500px;"></div>
        <br />
        <div id="piechart2" style="width: 900px; height: 500px;"></div>
    </div>


</section>

<footer>

    Dizajn : Richard Borcin
</footer>
</body>
</html>
