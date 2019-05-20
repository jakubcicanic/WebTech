<?php
$dbUrl = 'localhost';
$dbLogin = 'prsp';
$dbPass = '133625';
$dbName = 'master';

$mysqli = new mysqli($dbUrl, $dbLogin, $dbPass, $dbName);
mysqli_set_charset($mysqli, 'utf8');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$tim = $_GET['tim'];
$predmet = $_GET['predmet'];
$query1 = "SELECT ID_STUDENT,meno,body FROM `Tim` where tim = " . $tim . " and predmet = '" . $predmet. "'";
$result1 = mysqli_query($mysqli,$query1);

$do_csv = array();
$priprava = array();
$oddelovac= $_GET['oddelovac'];
$i = 0;
while($row = mysqli_fetch_array($result1))
{
    $id = $row["ID_STUDENT"];
    $meno = $row['meno'];
    $body = $row['body'];
    $priprava[$i] = array();
    array_push($priprava[$i],$id,$meno, $body);
    $i++;
}
//var_dump($priprava);
foreach ($priprava as $part){
    array_push($do_csv,$part);
}

header( 'Content-Type: application/csv' );
header( 'Content-Disposition: attachment; filename=novycsv.csv;' );
ob_end_clean();//vycistenie buffera
$novy_subor = fopen( 'php://output', 'w' );
//vlozenie dat do suboru
foreach ($do_csv as $udaj){
    fputcsv($novy_subor,$udaj,$oddelovac);
}
echo readfile($novy_subor);
//uzavretie suborov
fclose($novy_subor);
ob_flush();//vycistenie buffera
