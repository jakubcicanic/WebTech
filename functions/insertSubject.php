<?php
session_start();
require_once('../config.php');
require_once('createDatabase.php');
$conn = connectToDatabase($dbUrl, $dbLogin, $dbPass, $dbName);
$subject = $_POST['subjectName'];
$subjectYear = explode(' ', $_POST['subjectYear']);
$divider = $_POST['subjectFileDivider'];

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {$_SESSION['errorMsg'] = 'Connected to database';};

echo $_SESSION['errorMsg'];

if(isset($_FILES['subjectFile'])){
    echo 'skrr';
    $errors= array();
    $file_name = $_FILES['subjectFile']['name'];
    $file_size = $_FILES['subjectFile']['size'];
    $file_tmp = $_FILES['subjectFile']['tmp_name'];
    $file_type = $_FILES['subjectFile']['type'];
    $target_dir = "/home/xborcin/public_html/files/";
    $file_ext=strtolower(end(explode('.',$_FILES['subjectFile']['name'])));

    $extensions= array("csv");

    if(in_array($file_ext,$extensions)=== false){
        $errors[]="extension not allowed, please choose a JPEG or PNG file.";
    }

    if($file_size > 2097152) {
        $errors[]='File size must be excately 2 MB';
    }

    if(empty($errors)==true) {
        //move_uploaded_file($file_tmp,"images/".$file_name);
        //$myfile = fopen($target_dir. $file_name, "r") or die("Unable to open file!");
        //echo "Success";
        if (move_uploaded_file($file_tmp, $target_dir. $file_name)) {
            $mainFile = file_get_contents($target_dir. $file_name);
            //print_r($mainFile);

            $file=$target_dir. $file_name;
            $csv= file_get_contents($file);
            $array = array_map("str_getcsv", explode("\n", $csv));
            $json = json_encode($array);
            $decode = json_decode($json);
            $i = 0;
            $f = 0;
            foreach ($decode as $d){
                $data = explode($divider,$d[0]);
                if($i > 0) {

                    $sql = "INSERT INTO Tim (ID_STUDENT, meno, email, heslo, tim, predmet, semester, rok) "
                        . "VALUES('".$data[0]."', '".$data[1]."', '".$data[2]."', '".$data[3]."', '".$data[4]."', '".$subject."', '".$subjectYear[0]."', '".$subjectYear[1]."')";

                    if($conn->query($sql) === TRUE)
                    {   $f++;
                        $addedRecords = 'Added '. $f .' Records';
                    }else $_SESSION['errorMsg'] = mysqli_error($conn);
                }$i++;
            }
            echo $_SESSION['errorMsg'];


            $teamsSql = 'SELECT DISTINCT tim, predmet FROM `Tim`  ORDER by tim ASC';
            $teamSqlCheck = 'SELECT DISTINCT tim, predmet FROM `timy_body`  ORDER by tim ASC';
            $teamsCheck= array();
            $subjectCheck= array();
            $teamCheckResult = $conn->query($teamSqlCheck);

            if ($teamCheckResult->num_rows > 0) {
// output data of each row
                while ($row = mysqli_fetch_array($teamCheckResult)) {
                    array_unshift($teamsCheck, $row['tim']);
                    array_unshift($subjectCheck, $row['predmet']);
                }
            }


            $result = $conn->query($teamsSql);
            $teamNumbers = array();
            $k = 0;
            if ($result->num_rows > 0) {
// output data of each row
                while ($row = mysqli_fetch_array($result)) {
                    if(in_array($row['predmet'],$subjectCheck)=== false ) {
                        $addTeamSql = "INSERT INTO timy_body (tim, predmet) "
                            . "VALUES('" . $row['tim'] . "', '".$row['predmet']."')";
                        if ($conn->query($addTeamSql) === TRUE) {
                            $k++;
                            $addedTeams = ' and Added ' . $k . ' Teams';
                        } else mysqli_error($conn);

                    }
                }
            }
            echo "</table>";
            unlink( $target_dir. $file_name);
            $_SESSION['errorMsg'] = '<h1>'.$addedRecords.$addedTeams.'</h1>';
        }
    }else{
        print_r($errors);
    }
}
header("location: ../rating.php");

?>

