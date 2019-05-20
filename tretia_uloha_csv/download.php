<?php
session_start();
$do_csv = $_SESSION['udaje'];
$oddelovac = $_SESSION['oddelovac'];
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

