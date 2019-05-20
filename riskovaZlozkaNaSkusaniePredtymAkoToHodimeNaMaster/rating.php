<?php
session_start();
require_once('lang.php');
require_once ('config.php');
require_once('functions/createDatabase.php');
if (!isset($_SESSION['logged'])) {
    $_SESSION['errorMsg'] = $language[$_SESSION["lang"]]['e0'];
    header('location: index.php');
}

$conn = connectToDatabase($dbUrl, $dbLogin, $dbPass, $dbName);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    $cptSqlDraw = 'SELECT kapitan FROM timy_body where tim = '.$_SESSION['loggedTeam']." AND predmet = '".$_SESSION['currentSubject']."'";
    $cptDraw = $conn->query($cptSqlDraw);
    $teamCpt = mysqli_fetch_array($cptDraw);
    if(is_null($teamCpt['kapitan']))
    {
        $sqlAddCaptain = "UPDATE timy_body SET kapitan = '".$_SESSION['logged'][0]."' WHERE tim = ".$_SESSION['loggedTeam']." AND predmet = '".$_SESSION['currentSubject']."'";
        if ($conn->query($sqlAddCaptain) === TRUE) {

            $_SESSION['sqlErrorMsg'] = $_SESSION['logged'][1] . $language[$_SESSION["lang"]][50];

        } else {
            $_SESSION['sqlErrorMsg'] = mysqli_error($conn);
        }
    }


    $addPointsSql = "UPDATE Tim SET body= ".$_POST['studentPoints']." WHERE email= '".$_POST['studentID']."' AND predmet = '".$_POST['subjectID']."'";

   // $_SESSION['errorMsg'] = $_POST['studentID'].$_POST['studentPoints'];

    $teamSqlDraw = 'SELECT body FROM timy_body where tim = '.$_SESSION['loggedTeam']." AND predmet = '".$_POST['subjectID']."'";
    //$teamSqlDraw = 'SELECT body FROM timy_body where tim = 1';
    $teamSqlAllPoints = 'SELECT SUM(body) as body FROM Tim where tim = '.$_SESSION['loggedTeam']." AND predmet = '".$_POST['subjectID']."'";
    $draw = $conn->query($teamSqlDraw);
    while ($qValues=mysqli_fetch_array($draw))
    {
        $teamPoints = $qValues['body'];
    }

    $draw = $conn->query($teamSqlAllPoints);
    while ($qValues=mysqli_fetch_array($draw))
    {
        $teamPointsAll = $qValues['body'];
    }

    if((int)$teamPointsAll+(int)$_POST['studentPoints']<= (int)$teamPoints) {
        if ($conn->query($addPointsSql) === TRUE) {

           $_SESSION['errorMsg'] = $language[$_SESSION["lang"]][39] . $_POST['studentPoints'] . $language[$_SESSION["lang"]][40] . $_POST['studentID'];
            unset($_POST);

        } else {
            $_SESSION['errorMsg'] = mysqli_error($conn);
            unset($_POST);
        }
    }else {
        $_SESSION['errorMsg'] = $language[$_SESSION["lang"]][38];
        unset($_POST);
    }



   // header("location: rating.php");
}else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['studentAccept'])) {

        $accepted = "UPDATE Tim SET Suhlas = TRUE  WHERE ID_STUDENT= '" . $_SESSION['logged'][0] . "' AND tim = ".$_SESSION['loggedTeam']." AND predmet = '".$_SESSION['currentSubject']."'";
        if ($conn->query($accepted) === TRUE) {
            $_SESSION['errorMsg'] = $language[$_SESSION["lang"]][41] . $_SESSION['logged'][1];
            unset($_GET['studentAccept']);
        } else $_SESSION['errorMsg'] = mysqli_error($conn);

    } else if (isset($_GET['studentDecline'])) {

        $declined = "UPDATE Tim SET Suhlas = FALSE WHERE ID_STUDENT= '" . $_SESSION['logged'][0] . "' AND tim = ".$_SESSION['loggedTeam']." AND predmet = '".$_SESSION['currentSubject']."'";

        if ($conn->query($declined) === TRUE) {
            $_SESSION['errorMsg'] = $language[$_SESSION["lang"]][42] . $_SESSION['logged'][1];
            unset($_GET['studentDecline']);
        } else $_SESSION['errorMsg'] = mysqli_error($conn);

    }
}




?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Finálne Zadanie</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/table.css">
    <link rel="stylesheet" type="text/css" href="css/button.css">
</head>
<body>

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
                        echo '<li><a href="rating.php" class = "active">'.$language[$_SESSION["lang"]][3].'</a></li>';
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
                echo '<li><a href="langSwitch.php/?redirectedFrom=3"><img src="https://lipis.github.io/flag-icon-css/flags/4x3/sk.svg" height="24" width="32" ></a></li>';
            }
            else if ($_SESSION['lang'] == 0)
            {
                echo '<li><a href="langSwitch.php/?redirectedFrom=3"><img src="https://lipis.github.io/flag-icon-css/flags/4x3/gb.svg" height="24" width="32"></a></li>';
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
    <h1 style="font-size: 30px"><strong><?php echo $language[$_SESSION["lang"]][3] ?></strong></h1>
</section>
        
        <div id="tables">
            <?php 
                if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
                    echo $language[$_SESSION["lang"]][32];

                    if (isset($_SESSION['errorMsg'])) {
                       echo '<br>';
                        echo $_SESSION['errorMsg'];
                        echo '<br>';
                        unset($_SESSION['errorMsg']);
                    }

                    echo '
                        <form method = "POST" enctype = "multipart/form-data" action="functions/insertSubject.php">';
                        
                        echo $language[$_SESSION["lang"]][9];
                        echo ' 
                        <br>
                        <input type="text" name="subjectName" required>
                        
                        <br>';

                        echo $language[$_SESSION["lang"]][10];
                        echo '
                        <br>
                        <select name="subjectYear">
                            <option value="ZS 2017/2018"> ZS 2017/2018 </option>
                            <option value="LS 2017/2018"> LS 2017/2018 </option>
                            <option value="ZS 2018/2019"> ZS 2018/2019 </option>
                            <option value="LS 2018/2019"> LS 2018/2019 </option>
                            <option value="ZS 2019/2020"> ZS 2019/2020 </option>
                            <option value="LS 2019/2020"> LS 2019/2020 </option>
                        </select>
                        
                        <br>';

                        echo $language[$_SESSION["lang"]][11];
                        echo ' 
                        <br>
                        <select name="subjectFileDivider">
                            <option value="semicolon"> ; </option>
                            <option value="comma"> , </option>
                        </select>
                        
                        <br>';

                        echo $language[$_SESSION["lang"]][12];
                        echo '<br>
                        <input type="file" name="subjectFile" required>
                        
                   
                        
                        ';
                    
                        echo '<br><br>
                         <button type="submit" class="button pulse">'.$language[$_SESSION["lang"]][19].'</button>
                        
                        </form>
                        
                        ';
                   
                    
                } else if ($_SESSION['admin'] == 0){


                    //echo '<h1>Body Za Predmet</h1>';
                    //echo $_SESSION['logged'][0];
                    $sqlSelectTeam = 'SELECT tim from Tim where ID_STUDENT = '.$_SESSION['logged'][0];
                    $conn = connectToDatabase($dbUrl, $dbLogin, $dbPass, $dbName);
                    $result = $conn->query($sqlSelectTeam);
                    while ($row = mysqli_fetch_array($result)) {
                        $loggedTeam = $row['tim'];
                        $_SESSION['loggedTeam'] = $row['tim'];
                    }
                    //echo'<br>'.$loggedTeam;

                    $teamSqlDraw = 'SELECT body FROM timy_body where tim = '.$loggedTeam." AND predmet = '".$_GET['predmet']."'";
                    $captainSqlDraw = 'SELECT kapitan FROM timy_body where tim = '.$loggedTeam." AND predmet = '".$_GET['predmet']."'";
                    $draw = $conn->query($teamSqlDraw);
                    $captainDraw = $conn->query($captainSqlDraw);
                    $teamCaptain = mysqli_fetch_array($captainDraw);
                    $captain = $teamCaptain['kapitan'];

                    while ($qValues=mysqli_fetch_array($draw))
                        if (is_null($qValues['body'])) {

                            echo $language[$_SESSION["lang"]][43];
                        }else  echo "<h1><b>". $language[$_SESSION["lang"]][44].$qValues['body'].$language[$_SESSION["lang"]][33].".</b></h1>";

                        if(is_null($teamCaptain['kapitan']))
                        {
                            echo $language[$_SESSION["lang"]][45];

                        }else echo "<h2><b>". $language[$_SESSION["lang"]][46].$captain.".</b></h2>";

                    echo "<hr>";

                    if (isset($_SESSION['errorMsg'])) {
                        echo '<br>';
                        echo $_SESSION['errorMsg'];
                        echo $_SESSION['sqlErrorMsg'];
                        echo '<br>';
                        unset($_SESSION['errorMsg']);
                        unset($_SESSION['sqlErrorMsg']);
                    }

                    $subjectSql = "SELECT DISTINCT predmet FROM Tim";

                    //$query = $conn->query($teamsSql); // Run your query
                    $subjectResult = $conn->query($subjectSql); // Run your query

                    echo '<form action="" method="get">';
                    echo '<h3 style="font-size: 30px"><b>Select Subject</b></h3>';
                    echo '<select name="predmet">'; // Open your drop down box
                    // Loop through the query results, outputing the options
                    while ($row = mysqli_fetch_array($subjectResult)) {
                        echo '<option value="'.$row['predmet'].'">'.$row['predmet'].'</option>';
                        echo $row['predmet'];
                    }
                    echo '</select>';

                    echo '<button type="submit" class="fas fa-arrow-right button pulse"><i class="fas fa-arrow-right" ></i></button>';
                    echo '</form>';

                    $_SESSION['currentSubject'] = $_GET['predmet'];
                    $sqlAccepted = "SELECT Suhlas,body FROM Tim WHERE ID_STUDENT = '".$_SESSION['logged'][0]."'"." AND predmet = '".$_GET['predmet']."'";
                    $rs = $conn->query($sqlAccepted);
                    $acpt = mysqli_fetch_array($rs);

                    echo '  <table  class="container" >
                <tr>
                    <th>'.$language[$_SESSION["lang"]][35].'</th>
                    <th>'.$language[$_SESSION["lang"]][36].'</th>
                    <th>'.$language[$_SESSION["lang"]][33].'</th>';
                    echo' <th>Odpoved</th>';
                  if($acpt['Suhlas'] == NULL && $acpt['body'] != NULL)echo'  <th>'.$language[$_SESSION["lang"]][47].'</th><th>'.$language[$_SESSION["lang"]][48].'</th>';

                   // echo'<th>Suhlas</th><tr>';

        $teamTableSql = "SELECT meno, email,body,Suhlas FROM `Tim` WHERE predmet = '".$_SESSION['currentSubject']."' AND tim = ".$loggedTeam;
        $result = $conn->query($teamTableSql);

    if ($result->num_rows > 0) {
    // output data of each row
    while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['meno'] . "</td>";

        if((is_null($row['body']) && is_null($teamCaptain['kapitan'])) or (is_null($row['body']) && $teamCaptain['kapitan'] == $_SESSION['logged'][0])) {
            echo "<td>
                <form method='post' action='rating.php'>
                <input type='number' name='studentPoints'>
                <input type='hidden' name='studentID' value='".$row['email']."'>
                <input type='hidden' name='subjectID' value='".$_GET['predmet']."'>";


            echo "<button type='submit' class=\"button pulse\"><i class=\"fas fa-arrow-right\" ></i></button>
                    </form>
                </td>";
        }else {
            echo "<td>" . $row['body'] . "</td>";


            if (is_null($row['Suhlas']))
            {
                echo "<td>".$language[$_SESSION["lang"]][49]."</td>";
            }
            else if (!is_null($row['Suhlas']))
            {
                if($row['Suhlas'] == 1)echo "<td><i style='font-size:25px; color:green' class=\"fas fa-user-check\"></i></td>";
                else if ($row['Suhlas'] == 0)echo "<td><i style='font-size:25px; color:red'  class=\"fas fa-user-times\"></i></td>";
            }

            if($_SESSION['logged'][1] === $row['meno'] && !is_null($row['body'])) {

                 if ($acpt['Suhlas'] == NULL) {
                    echo "<td><form method='get' action='rating.php'><input type = 'hidden' name = 'studentAccept' value = '" . $_SESSION['logged'][0] . "'><button type ='submit' class ='button pulse'><i class=\"fas fa-check-circle\" ></i></button></form></td>";
                    echo "<td><form method='get' action='rating.php'><input type = 'hidden' name = 'studentDecline' value = '" . $_SESSION['logged'][0] . "'><button type ='submit' class ='button pulse'><i class=\"fas fa-times-circle\" ></i></button></form></td>";
                }
            }
        }


        echo "<tr>";
    }
    }

echo '</table>';



                }
            ?>
        </div>

<footer>

    Dizajn : Richard Borcin

</footer>
    </body>
</html>