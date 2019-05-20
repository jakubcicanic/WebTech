<?php

require_once("../config.php");

$mysqli = new mysqli($dbUrl, $dbLogin, $dbPass, "mails");
mysqli_set_charset($mysqli, 'utf8');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function getData($typ)
{
    global $mysqli;
    $data = [];
    $str = "";
    $sql = "SELECT * FROM mail WHERE typ = '".$typ."'";
    $result = $mysqli->query($sql);
    while($row = $result->fetch_assoc()){
        array_push($data, $row["text"]);
       // $str .= $row["text"];
    }
    return json_encode($data);
}

//fcia pre vlozenie zaznamu do db
function postHistory($meno, $predmet, $sablona){
  
  global $mysqli;
  
  $sql = 'INSERT INTO `historia`(`Datum_odoslania`, `Meno_Studenta`, `Predmet_Spravy`, `id_sablony`) 
  VALUES (now(), "'.$meno.'", "'.$predmet.'", '.$sablona.')';
  $mysqli->query($sql) or die($mysqli->error);
}

?>