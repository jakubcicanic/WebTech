<?php
//phpinfo();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('db.php');

mb_internal_encoding("UTF-8");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpMailer/Exception.php';
require 'phpMailer/PHPMailer.php';
require 'phpMailer/SMTP.php';


$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->SMTPDebug = 2;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = 'mail.stuba.sk';                        // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $_SESSION['email'];                      // SMTP username    zadat meno prihlaseneho do isu
    $mail->Password   = $_SESSION['password'];                              // SMTP password    zadat heslo do isu
    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 587;                                    // TCP port to connect to
    $mail->setLanguage("sk");
    $mail->setFrom($_SESSION['email'], 'Mail');


    for($i=0; $i<sizeof($_POST["allUsers"]); $i++){

        $mail->addAddress($_POST["allUsers"][$i]["email"], 'Email');     // Add a recipient

        // Attachments
        //   $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //   $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        // Content
        $predmet = "Oznam";
        if(isset($_POST["char"])) $predmet = $_POST["char"];
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $predmet;
        $mail->Body    = $_POST["allUsers"][$i]["text"];

        $mail->CharSet = 'UTF-8';
        $mail->send();
        echo 'Message has been sent';


        $meno = $_POST["allUsers"][$i]["meno"];

        $sablona = $_POST["sablona"];

        postHistory($meno, $predmet, $sablona);
    }
    return 1;
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    return $mail->ErrorInfo;
}



?> 