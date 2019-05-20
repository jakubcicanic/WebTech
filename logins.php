<?php
session_start();
//['logged'] -> len prihlásený
//['admin'] -> ak je aj admin
//['lang'] -> jazyk, 0 -> SK, 1 -> EN

require_once('lang.php');
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang']=0;
}
//$_SESSION['lang']=1;
unset($_SESSION['logged']);
$_SESSION['admin']=1;

//require_once "config.php";

//$loginURL = $gClient->createAuthUrl();
$_SESSION['message'] = '';

$servername = "localhost";
$username = "prsp";
$password = "133625";
$dbName = "accounts";

$mysqli = new mysqli($servername, $username, $password, $dbName);
mysqli_set_charset($mysqli, 'utf8');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = '".$username."' AND password = '".$password."' LIMIT 1";


    $result = $mysqli->query($sql);

    if($result->num_rows == 1)
    {
        $row = mysqli_fetch_array($result);
        $id = $row['user_id'];

        $logins_sql = "INSERT INTO logins (user_id, login_type) "
            . "VALUES('$id', 'default')";

        if($mysqli->query($logins_sql) === TRUE){

            $_SESSION['message'] = "Vitaj $username.";

            $_SESSION['id'] = $id;
            header("location: welcome.php");

        }else mysqli_error($mysqli);
    }
    else{
        $_SESSION['message'] = "Nesprávne prihlasovacie údaje" . mysqli_error($mysqli);

    }

}

?>


<!DOCTYPE HTML>
<html>
<head>
    <title>Finálne Zadanie</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/btn.css">

</head>
<body>
<header>
    <nav class="main-nav">
        <ul>
            <li>
                <ul>
                    <?php
                    if (!isset($_SESSION['logged'])) {
                        echo '<li><a href="index.php">'.$language[$_SESSION["lang"]][6].'</a></li>';
                        echo '<li><a href="login.php"  class = "active">'.$language[$_SESSION["lang"]][0].'</a></li>';
                    } else {
                        echo '<li><a href="logout.php">'.$language[$_SESSION["lang"]][1].'</a></li>';
                        echo '<li><a href="state.php">'.$language[$_SESSION["lang"]][2].'</a></li>';
                        echo '<li><a href="rating.php">'.$language[$_SESSION["lang"]][3].'</a></li>';
                        if (isset($_SESSION['admin'])) {
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
                echo '<li><a href="langSwitch.php/?redirectedFrom=1"><img src="https://lipis.github.io/flag-icon-css/flags/4x3/sk.svg" height="24" width="32"></a></li>';
            }
            else if ($_SESSION['lang'] == 0)
            {
                echo '<li><a href="langSwitch.php/?redirectedFrom=1"><img src="https://lipis.github.io/flag-icon-css/flags/4x3/gb.svg" height="24" width="32"></a></li>';
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
    <h1 style="font-size: 30px"><strong><?php echo $language[$_SESSION["lang"]][0] ?></strong></h1>
</section>


<div class="tabs">
    <a class="tab" onclick="tabs.show('tab1')">Tab One</a>
    <a class="tab" onclick="tabs.show('tab2')">Tab Two</a>
</div>

<form name="myForm" action="#" >

    <!-- note that for the first tab we need to
         manually set the display to visible -->
    <div id="tab1" class="tabContent" style="display:block">
        This is tab 1
        <div class="common_area">
        </div>
    </div>

    <div id="tab2" class="tabContent">
        This is tab 2
        <div class="common_area">
        </div>
    </div>
    <input type="submit" action="http://example.com/" method="post">

</form>

<div id="common_stuff" class="common_stuff" >
    <table>
        <tr>
            <td>Field One: </td>
            <td><input type="text" name="fieldOne" id="exfieldOne" value=""/></td>
        </tr>
        <tr>
            <td>Field Two: </td>
            <td><input type="text" name="fieldTwo" id="exfieldTwo" value=""/></td>
        </tr>
        <tr>
            <td>Field Three: </td>
            <td><input type="text" name="fieldThree" id="exfieldThree" value=""/></td>
        </tr>
    </table>
</div>
<hr>

<!-- scripts in body are executed on document ready -->
<script type="text/javascript">

    var tabs = (function () {

        // More hackery, list the tab divs
        var tabs = ["tab1", "tab2"],
            domTabs = [],
            commonStuff,
            obj,
            cldrn,
            child,
            currentPrefix,
            show,
            i,
            j;

        // Recursively iterate through node children and rename form elements
        function renameNodes(node) {
            var i;
            if (node.length) {
                for (i = 0; i < node.length; i += 1) {
                    renameNodes(node[i]);
                }
            } else {
                // rename any form-related elements
                if (typeof node.form !== 'undefined') {
                    node.id = currentPrefix + '_' + node.id;
                    node.name = currentPrefix + '_' + node.name;

                    // Assume that form elements do not have child form elements
                } else if (node.children) {
                    renameNodes(node.children);
                }
            }
        }

        // Clone the common stuff dom element and prepend the tabId to the elements
        function getCommonStuff(tabId) {
            var commonClone = commonStuff.cloneNode(true);
            // hack for ie6/7
            if (!!document.all) {
                commonClone.innerHTML = commonStuff.innerHTML;
            }

            currentPrefix = tabId;
            renameNodes(commonClone);
            return commonClone;
        }

        show = function showTab(tab) {
            var i;

            for (i = 0; i < domTabs.length; i += 1) {
                if (tabs[i] === tab) {
                    domTabs[i].style.display = "block";
                } else {
                    domTabs[i].style.display = "none";
                }
            }
        };

        // Let's keep a reference to the dom nodes so we don't have to fish
        for (i = 0; i < tabs.length; i += 1) {
            domTabs.push(document.getElementById(tabs[i]));
        }

        commonStuff = document.getElementById("common_stuff");

        // remove the common stuff from the form
        commonStuff.parentNode.removeChild(commonStuff);

        for (i = 0; i < domTabs.length; i += 1) {
            obj = domTabs[i];

            // Find the correct div
            cldrn = obj.childNodes;
            for (j = 0; j < cldrn.length; j += 1) {
                child = cldrn[j];
                if (child.className === "common_area") {
                    // Copy the common content over to the tab
                    child.appendChild(getCommonStuff(tabs[i]));
                    break;
                }
            }
        }

        // show the first tab
        show(tabs[0]);

        return {
            show: show // return the show function
        };

    }());

</script>
</body>
</html>
