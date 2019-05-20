<?php
session_start();

function createDatabase($conn, $dbName) {
   //$_SESSION['errorMsg'] = 'test';
    
    $sql = "CREATE DATABASE IF NOT EXISTS $dbName";
        
    if ($conn->query($sql)) {
        //$_SESSION['errorMsg'] = 'created master database';
    } else {
        $_SESSION['errorMsg'] = $conn->error;       
    }  
}

function createSubjectsTable($conn) {
    $sql = 'CREATE TABLE IF NOT EXISTS Predmety (
    ID INT AUTO_INCREMENT,
    ID_studenta INT NOT NULL,
    meno VARCHAR(50) NOT NULL,
    cv1 FLOAT,
    cv2 FLOAT,
    cv3 FLOAT,
    cv4 FLOAT,
    cv5 FLOAT,
    cv6 FLOAT,
    cv7 FLOAT,
    cv8 FLOAT,
    cv9 FLOAT,
    cv10 FLOAT,
    cv11 FLOAT,
    Z1 FLOAT,
    Z2 FLOAT,
    VT FLOAT,
    SK_T FLOAT,
    SK_P FLOAT,
    Spolu FLOAT,
    Znamka VARCHAR(2),
    Predmet VARCHAR(50) NOT NULL,
    Rocnik VARCHAR(15) NOT NULL,
    PRIMARY KEY (ID)
    ) ENGINE=MEMORY;'; 
    
        
    if ($conn->query($sql)) {
        //$_SESSION['errorMsg'] = 'created table subjects';
    } else {
        $_SESSION['errorMsg'] = $conn->error;       
    }  
}


//PREROBIT 
function createStudentTable($conn) {
    $sql = 'CREATE TABLE IF NOT EXISTS Tim (
     ID INT AUTO_INCREMENT,
     ID_STUDENT VARCHAR (10) NOT NULL,
     meno VARCHAR (50) NOT NULL,
     email VARCHAR (50) NOT NULL,
     heslo VARCHAR (50),
     tim INT,
     predmet VARCHAR (50),
     semester VARCHAR (5),
     rok VARCHAR(15),
     body INT,
     Suhlas INT,
     PRIMARY KEY (ID)
    ) ENGINE=MEMORY;';
    if ($conn->query($sql)) {
        //$_SESSION['errorMsg'] = 'created table teams';
    } else {
        $_SESSION['errorMsg'] = $conn->error;       
    }
}

function createTeamInfoTable($conn) {
    
}

function setupDatabase($dbUrl, $dbLogin, $dbPass, $dbName) {
    
    //$_SESSION['errorMsg'] = 'SetupDatabase';
    
    $conn = new mysqli($dbUrl,$dbLogin,$dbPass);
   
    if ($conn->connect_error) {
        $_SESSION['errorMsg'] = 'error inside setupDatabase';
        return;
    } 
     
    
    //createDatabase funguje
    createDatabase($conn, $dbName);
    
    //zatvoríme connect a otvoríme nový 
    $conn->close();
    
    $conn = new mysqli($dbUrl, $dbLogin, $dbPass, $dbName);
    
    //createSubject table funguje
    
    createSubjectsTable($conn);
    createStudentTable($conn);
    
    $conn->close();   
}

function connectToDatabase($dbUrl, $dbLogin, $dbPass, $dbName)
{
    $conn = mysqli_connect($dbUrl, $dbLogin, $dbPass, $dbName);
    mysqli_set_charset($conn, "utf8");
    return $conn;
}
    
?>