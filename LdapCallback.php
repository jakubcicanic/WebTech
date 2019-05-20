<?php
//include '../../../myinfo/isconfig.php';
session_start();

require_once("config.php");

$ldapuid = $_POST['isUid'];
$ldappass = $_POST['isPass'];
$dn  = 'ou=People, DC=stuba, DC=sk';
$ldaprdn  = "uid=$ldapuid, $dn";
$ldapconn = ldap_connect("ldap.stuba.sk");
$set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

$mysqli = new mysqli($dbUrl, $dbLogin, $dbPass, $dbName);
mysqli_set_charset($mysqli, 'utf8');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$ldapMail = $ldapuid."@is.stuba.sk";
$sql = "SELECT email FROM Tim WHERE email = '".$ldapMail."' AND heslo = '".$ldappass."'";

if($result = $mysqli->query($sql))
{
    $message = "ZBEHLO";

}else $message = mysqli_error($mysqli);


if ($ldapconn) {

    $results = ldap_search($ldapconn, $dn, "uid=$ldapuid", array("givenname", "employeetype", "surname", "mail", "faculty", "cn", "uisid", "uid"));
    $info = ldap_get_entries($ldapconn, $results);
    $i = 0;
    $isName = $info[$i]['cn'][0] . "<br>";
    $isName = substr($isName, 0, -4);
    $isMail = $info[$i]['mail'][0] . "<br>";
    $isMail = substr($isMail, 0, -4);
    $isId = $info[$i]['uisid'][0];

    if ($result->num_rows > 0) {
        $_SESSION['logged'] = array(

            0 => $isId,
            1 => $info[$i]['cn'][0],
            // $info[$i]['givenname'][0]
        );
        $_SESSION['loggedUserName'] = $info[$i]['cn'][0];
        $_SESSION['email'] = $ldapuid."@stuba.sk";
        $_SESSION['password'] = $ldappass;

        header("location: index.php");
    } else {


        // binding to ldap server
        $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);

        // verify binding
        if ($ldapbind) {
            echo "LDAP bind successful...";

//                $_SESSION['message'] = "Vitaj $ldapuid.";
                    $_SESSION['logged'] = array(
                        0 => $isId,

                        1 => $info[$i]['cn'][0],
                        // $info[$i]['givenname'][0]
                    );

                    $_SESSION['loggedUserName'] = $info[$i]['cn'][0];

                    header("location: index.php");
            
        } else {
            echo "LDAP bind failed..." . ldap_error($ldapconn);
            echo $ldapMail;
            echo $message;
        }

    }


    ldap_unbind($ldapconn);
}

?>