<?php
session_start();
//['logged'] -> len prihlásený
//['admin'] -> ak je aj admin
//['lang'] -> jazyk, 0 -> SK, 1 -> EN


//require_once('lang.php');
//require_once('config.php');
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang']=0;
}
//$_SESSION['lang']=1;
unset($_SESSION['logged']);
unset($_SESSION['admin']);

//require_once "config.php";

//$loginURL = $gClient->createAuthUrl();
$_SESSION['message'] = '';

/*$servername = "localhost";
$username = "prsp";
$password = "133625";
$dbName = "accounts";*/

//$mysqli = new mysqli($dbUrl, $dbLogin, $dbPass, $dbName);
//mysqli_set_charset($mysqli, 'utf8');

//if ($mysqli->connect_error) {
   // die("Connection failed: " . $mysqli->connect_error);
//



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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login and Registration Form with HTML5 and CSS3" />
    <meta name="keywords" content="html5, css3, form, switch, animation, :target, pseudo-class" />
    <meta name="author" content="Codrops" />
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/demo.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../css/style.css">


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
                        if (isset($_SESSION['admin']) && $_SESSION['admin' == 1]) {
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


<div class="container">
    <!-- Codrops top bar -->
    <div class="codrops-top">
        <a href="">
            <strong>&laquo; Previous Demo: </strong>Responsive Content Navigator
        </a>
        <span class="right">
                    <a href=" http://tympanus.net/codrops/2012/03/27/login-and-registration-form-with-html5-and-css3/">
                        <strong>Back to the Codrops Article</strong>
                    </a>
                </span>
        <div class="clr"></div>
    </div><!--/ Codrops top bar -->
    <header>
        <h1>Login and Registration Form <span>with HTML5 and CSS3</span></h1>
        <nav class="codrops-demos">
            <span>Click <strong>"Join us"</strong> to see the form switch</span>
            <a href="index.html" class="current-demo">Demo 1</a>
            <a href="index2.html">Demo 2</a>
            <a href="index3.html">Demo 3</a>
        </nav>
    </header>
    <section>
        <div id="container_demo" >
            <!-- hidden anchor to stop jump http://www.css3create.com/Astuce-Empecher-le-scroll-avec-l-utilisation-de-target#wrap4  -->
            <a class="hiddenanchor" id="toregister"></a>
            <a class="hiddenanchor" id="tologin"></a>
            <div id="wrapper">
                <div id="login" class="animate form">
                    <form  action="LdapCallback.php" autocomplete="on">
                        <h1>Log in</h1>
                        <p>
                            <label for="username" class="uname" data-icon="u" > Your email or username </label>
                            <!--  <input id="username" name="username" required="required" type="text" placeholder="myusername or mymail@mail.com"/>-->
                              <input title="name" type="text" name="adminLogin" placeholder="Admin Username" required><br/>

                          </p>
                          <p>
                              <label for="password" class="youpasswd" data-icon="p"> Your password </label>
                              <!-- <input id="password" name="password" required="required" type="password" placeholder="eg. X8df!90EO" />-->
                               <input title = "pass" type="password" name="adminPass" placeholder="Admin Password" required><br/>
                           </p>
                           <p class="keeplogin">
                               <input type="checkbox" name="loginkeeping" id="loginkeeping" value="loginkeeping" />
                               <label for="loginkeeping">Keep me logged in</label>
                           </p>
                           <p class="login button">
                               <input type="submit" value="Login" />
                           </p>
                           <p class="change_link">
                               Not a member yet ?
                               <a href="#toregister" class="to_register">Join us</a>
                           </p>
                       </form>
                   </div>

                   <div id="register" class="animate form">
                       <form  action="adminLogin.php" autocomplete="on">
                           <h1> Sign up </h1>
                           <p>
                               <label for="usernamesignup" class="uname" data-icon="u">Your username</label>
                               <input id="usernamesignup" name="usernamesignup" required="required" type="text" placeholder="mysuperusername690" />
                           </p>
                           <p>
                               <label for="emailsignup" class="youmail" data-icon="e" > Your email</label>
                               <input id="emailsignup" name="emailsignup" required="required" type="email" placeholder="mysupermail@mail.com"/>
                           </p>
                           <p>
                               <label for="passwordsignup" class="youpasswd" data-icon="p">Your password </label>
                               <input id="passwordsignup" name="passwordsignup" required="required" type="password" placeholder="eg. X8df!90EO"/>
                           </p>
                           <p>
                               <label for="passwordsignup_confirm" class="youpasswd" data-icon="p">Please confirm your password </label>
                               <input id="passwordsignup_confirm" name="passwordsignup_confirm" required="required" type="password" placeholder="eg. X8df!90EO"/>
                           </p>
                           <p class="signin button">
                               <input type="submit" value="Sign up"/>
                           </p>
                           <p class="change_link">
                               Already a member ?
                               <a href="#tologin" class="to_register"> Go and log in </a>
                           </p>
                       </form>
                   </div>

               </div>
           </div>
       </section>
   </div>
   </body>
   </html>