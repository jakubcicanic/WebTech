<?php
session_start();
require_once('lang.php');
require_once('config.php');


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
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/table.css">
    <link rel="stylesheet" type="text/css" href="css/button.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

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
                        echo '<li><a href="state.php" class = "active">'.$language[$_SESSION["lang"]][2].'</a></li>';
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
                echo '<li><a href="langSwitch.php/?redirectedFrom=2"><img src="https://lipis.github.io/flag-icon-css/flags/4x3/sk.svg" height="24" width="32" ></a></li>';
            }
            else if ($_SESSION['lang'] == 0)
            {
                echo '<li><a href="langSwitch.php/?redirectedFrom=2"><img src="https://lipis.github.io/flag-icon-css/flags/4x3/gb.svg" height="24" width="32"></a></li>';
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
    <h1 style="font-size: 30px"><strong><?php echo $language[$_SESSION["lang"]][2] ?></strong></h1>
</section>
        
        <div id="tables">
            <?php 
                if (isset($_SESSION['admin'])) {
                    echo '<hr>';
                    echo '<h3 style="font-size: 30px"><b>'.$language[$_SESSION["lang"]][20].'</b></h3>';
                    
                    if(isset($_POST['rocnik']) AND isset($_POST['predmet'])) {
                        $conn = new mysqli($dbUrl, $dbLogin, $dbPass, $dbName);
                        
                        $sql = "SHOW COLUMNS FROM Predmety";
                        $sql = "SELECT * FROM Predmety where Predmet = '".$_POST['predmet']."' AND Rocnik ='".$_POST['rocnik']."'";
                            
                        if ($result = $mysqli->query($sql)) {
                        echo '<table>';
                        foreach($result->fetch_all() as $row) {
                              foreach($row as $key  => $value) {
                                 echo '<tr><td>' . $key . '</td><td>' . $value . '</td></tr>';
                             }
                        }
                        echo '</table>';
                        }
                        
                        
                    }
                    
                    


                    $conn = new mysqli($dbUrl, $dbLogin, $dbPass, $dbName);
                    
                    $sql = 'SELECT DISTINCT Predmet, Rocnik from Predmety';
                    
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0 ) {
                        echo '<table class="container">
                         <tr>
                    <th>'.$language[$_SESSION["lang"]][29].'</th>
                    <th>'.$language[$_SESSION["lang"]][30].'</th>
                    <th>'.$language[$_SESSION["lang"]][31].'</th>
                    <th>'.$language[$_SESSION["lang"]][62].'</th>
                    <th>'.$language[$_SESSION["lang"]][63].'</th>
                    
                 
                    <tr>';
                        while($row = $result->fetch_assoc()){
                            echo '
                            <tr>
                            <td>
                                ' . $row['Predmet'] . '
                            </td>
                            
                            <td>
                                ' . $row['Rocnik'] . '
                            </td>
                            
                            <td>
                            <form method="POST" action="functions/delSubject.php">
                            
                                <input type="hidden" name="predmet" value="' . $row['Predmet'] . '" >
                                    
                                <input type="hidden" name="rocnik" value="' . $row['Rocnik'] . '" >
                            
                                <button type="submit" value="vymazat" class="button pulse"><i class="fas fa-trash-alt"></i></button>
                                   
                            </form>
                            </td>
                            <td>
                            <form method="POST" action="state.php">
                            
                                <input type="hidden" name="predmet" value="' . $row['Predmet'] . '" >
                                    
                                <input type="hidden" name="rocnik" value="' . $row['Rocnik'] . '" >
                            
                                <button type="submit" value="ukážka" class="button pulse"><i class="fas fa-trash-alt"></i></button>
                                
                            </form>
                            </td>
                            <td>
                            <form method="post" action="functions/downloadPdf.php">
                            
                                <input type="hidden" name="predmet" value="' . $row['Predmet'] . '" >
                                    
                                <input type="hidden" name="rocnik" value="' . $row['Rocnik'] . '" >
                            
                                <button type="submit" value="vytlačenie" class="button pulse"><i class="fas fa-trash-alt"></i></button>
                                
                            </form>
                            </td>
                            </tr>
                            ';
                            
                        }
                        echo '</table>';
                    } else {
                        echo $language[$_SESSION["lang"]][22];
                    }
                    
                    $conn->close();

                    echo '<br>';
                    echo '<hr>';
                    echo '<h3 style="font-size: 30px"><b>'.$language[$_SESSION["lang"]][21].'</b></h3>';

                    //ZOZNAM PREDMETOV NA VYMAZANIE A FORMULÁR NA NACITANIE NOVEHO PREDMETU DO DATABAZY
                    
                    
                    echo '
                        <form method="post" action="addSubject.php">';
                        
                        echo $language[$_SESSION["lang"]][9];
                        echo ' 
                        <br>
                        <input type="text" name="subjectName" required>
                        
                        <br>';

                        echo $language[$_SESSION["lang"]][10];
                        echo '
                        <br>
                        <select name="subjectYear">
                            <option value="2017"> ZS 2017/2018 </option>
                            <option value="2017"> LS 2017/2018 </option>
                            <option value="2018"> ZS 2018/2019 </option>
                            <option value="2018"> LS 2018/2019 </option>
                            <option value="2019"> ZS 2019/2020 </option>
                            <option value="2019"> LS 2019/2020 </option>
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
                        <button type="submit" value="odošli" class="button pulse">'.$language[$_SESSION["lang"]][19].'</button>
                        
                        </form>
                        
                        ';
                    
                } else {
                    
                    //ZOZNAM PREDMETOV PRE STUDENTA LEBO JE loggnuty
                    
                    echo $language[$_SESSION["lang"]][51];
                    
                    $conn = new mysqli($dbUrl,$dbLogin,$dbPass,$dbName);
                    
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    } 
                    
                    $sql = 'SELECT * FROM Predmety WHERE ID_studenta = ' . $_SESSION['logged'][0];
                    
                    //nacitanie riadkov z databazy
                    
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0 ) {
                        while($row = $result->fetch_assoc()){
                            echo '<p> ' . $row['Predmet'] . ' ' . $row[Rocnik] . ' <p><br>';
                            
                            echo '<table> 
                                <tr>
                                <td>'.$language[$_SESSION["lang"]][24].'</td>
                                <td>'.$language[$_SESSION["lang"]][25].'</td>
                                <td>'.$language[$_SESSION["lang"]][26].'</td>
                                <td>'.$language[$_SESSION["lang"]][27].'</td>
                                <td>'.$language[$_SESSION["lang"]][28].'</td>
                                </tr>
                                
                                <tr>
                                <td>'.$row['cv1']+$row['cv2']+$row['cv3'].'</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                </tr>
                            </table>'; 
                            
                            
                        }
                    } else {
                        echo $language[$_SESSION["lang"]][23];
                    }
                        
                   $conn->close(); 
                }
            ?>
        </div>

<footer>

    Dizajn : Richard Borcin
</footer>
    </body>
</html>