<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
require('db.php');
require_once('../lang.php');

if(!isset($_SESSION["logged"])|| $_SESSION["logged"]==null){
    header("location: ../login.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>Finálne zadanie</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/btn.css">
    <link rel="stylesheet" type="text/css" href="../css/table.css">

</head>
<body>

<style>
    input[type=file] {
        display: inline-block;
    }
    .width-70{
        width: 70%;
    }
    .tab-user{
        padding: 1em;
    }

    .error-message{
        position: fixed;
        left: 63%;
        right: 2%;
        width: 35%;
        z-index: 10;
    }

    .txt-l{ text-align:left;}
   .txt-c{ text-align:center;}
   .txt-r{ text-align:right;}
   .txt-j{ text-align:justify;}
   .txt-col-black{ color:black;}
   .txt-col-red{ color:red;}
   .txt-col-green{ color:green;}
   .txt-col-blue{ color:blue;}
    
   .black{ background: black;}
   .red{ background: red;}
   .green{ background: green;}
   .blue{ background: blue;} 
   .text-color{width:40px; height:30px; margin: 10px}
   
   div#editor{height:50px; transition: all 0.6s ease; -webkit-transition: all 0.6s ease;}
   div#editor.hide{height:0px; overflow:hidden;}
    
</style>
<header>

    <nav class="main-nav">
        <ul>
            <li>
                <ul>
                    <?php
                    if (!isset($_SESSION['logged'])) {
                        echo '<li><a href="../index.php" class = "active">'.$language[$_SESSION["lang"]][6].'</a></li>';
                        echo '<li><a href="../login.php">'.$language[$_SESSION["lang"]][0].'</a></li>';
                    } else {
                        echo '<li><a href="../index.php" class = "active">'.$language[$_SESSION["lang"]][6].'</a></li>';
                        //echo '<li><a href="logout.php">'.$language[$_SESSION["lang"]][1].'</a></li>';
                        echo '<li><a href="../state.php">'.$language[$_SESSION["lang"]][2].'</a></li>';
                        echo '<li><a href="../rating.php">'.$language[$_SESSION["lang"]][3].'</a></li>';
                        echo '<li><a href="index.php">'.$language[$_SESSION["lang"]][69].'</a></li>';
                        if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
                            echo '<li><a href="../generating.php">'.$language[$_SESSION["lang"]][4].'</a></li>';
                            echo '<li><a href="../tretia_uloha_csv/mail.php">Mail</a></li>'; 
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
                echo '<li><a href="../langSwitch.php/?redirectedFrom=6"><img src="https://lipis.github.io/flag-icon-css/flags/4x3/sk.svg" height="24" width="32" ></a></li>';
            }
            else if ($_SESSION['lang'] == 0)
            {
                echo '<li><a href="../langSwitch.php/?redirectedFrom=6"><img src="https://lipis.github.io/flag-icon-css/flags/4x3/gb.svg" height="24" width="32"></a></li>';
            }
            ?>
        </ul>
    </nav>

    <nav class="main-nav2">
        <ul>

            <?php
            if (isset($_SESSION['logged'])) {
                echo '<li><a href="logout.php" style="margin-top: 50px;">'.$language[$_SESSION["lang"]][1].'</a></li>';
                echo '<li><a href="index.php">'.$_SESSION["loggedUserName"].'</a></li>';
            }

            ?>
        </ul>
    </nav>

</header>

<h1><?php echo $language[$_SESSION["lang"]][77];?></h1>

    <?php echo $language[$_SESSION["lang"]][70];?> <input type="radio" name="type" value="text" id="text" checked>Plain text
             <input type="radio" name="type" value="html" id="html">HTML<br>
    <?php echo $language[$_SESSION["lang"]][72];?><input type="file" name="csv_file"  id="csv_file">
    <?php echo $language[$_SESSION["lang"]][74];?><input type="text" name="char" id="char">
   <?php echo $language[$_SESSION["lang"]][75];?><input type="file" name="sel_file"><br>

    <?php echo $language[$_SESSION["lang"]][78];?>
    <ul class="nav nav-tabs" id="users_from_file" >
    </ul>

    <div class="tab-content tab-user" id="user-email-text">
    </div>

    <div contenteditable="true" id="sablona">
    </div>

    <input type="submit" name="Submit" id="submit" value="<?php echo $language[$_SESSION["lang"]][19];?>">
    
 <div id="editor" class="hide" style="max-width:700px; margin: 0 auto;">
  <div>
    <div style="display:inline-block; position:relative; width:150px;">
      <h3><?php echo $language[$_SESSION["lang"]][79];?></h3>
    </div>
    <button class="text-color black" data-color="black" title="<?php echo $language[$_SESSION["lang"]][80];?>"></button>
    <button class="text-color red" data-color="red" title="<?php echo $language[$_SESSION["lang"]][81];?>"></button>
    <button class="text-color green" data-color="green" title="<?php echo $language[$_SESSION["lang"]][82];?>"></button>
    <button class="text-color blue" data-color="blue" title="<?php echo $language[$_SESSION["lang"]][83];?>"></button>
  </div>
 </div>
<div class="error-message" id="div-error">
</div>

<?php
$history = getHistory();
?>
<section>
    <div class="container">
        <h2><?php echo $language[$_SESSION["lang"]][76];?></h2>
        <table class="table table-hover table-sortable" >
            <thead>
            <tr>
                <th><?php echo $language[$_SESSION["lang"]][36];?></th>
                <th><?php echo $language[$_SESSION["lang"]][9];?></th>
                <th><?php echo $language[$_SESSION["lang"]][71];?></th>
                <th><?php echo $language[$_SESSION["lang"]][70];?></th>
            </tr>
            </thead>
            <tbody>
            <?php

            for($i = 0; $i<sizeof($history); $i++){

            echo '<tr><td>'.$history[$i]["Meno_Studenta"].'</td>
               <td>'.$history[$i]["Predmet_Spravy"].'</td>
               <td>'.$history[$i]["Datum_odoslania"].'</td>
               <td>'.$history[$i]["id_sablony"].'</td>
            </tr>';
           } ?>

            </tbody>
        </table>
    </div>

</section>

    
</body>
<script>
    function showErrorMessage(message, color){
        error = "<div class=\"alert "+ color +" alert-dismissible fade show animated fadeInUp duration-2s \"  role=\"alert\">\n" +
            "            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n" +
            "                <span aria-hidden=\"true\">&times;</span>\n" +
            "            </button>\n" +
            "            <span> "+message+" </span>\n" +
            "        </div>";
        return error;
    }

    $(document).ready(function()
    {
        $("#submit").click(function () {
            var allUsers = [];
            $(".tab-pane .user").each(function( index ) {
                allUsers.push({
                    email: $( this ).data("email"),
                    meno: $(this).data("meno"),
                    text:  $( this ).html(),
                })
            });
            sablona = 1;
            if($('#html').is(":checked"))
            {
                sablona=2;
            };
        //    console.log(allUsers);
            $.ajax({
                url: "mailing.php",
                type: "post",
                data: {allUsers, 'char': $("#char").val(), 'sablona':sablona},
                success: function (response) {
                   // console.log(response);
                    message = "";
                    stav = "alert-success";
                    if(response == 1){
                        message = "Správa bola odoslaná úspešne.";

                    }
                    else{
                        message = "Vyskytla sa chyba!. "+response;
                        stav = "alert-danger";
                    }
                    console.log(response);
                    $("#div-error").html(showErrorMessage(message, stav));
                    $("#div-error .alert").delay(3000).fadeOut(1000, function () { $(this).remove(); });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });


        var data_arr = [];

        function readFile(file, callback){
            var reader = new FileReader();
            reader.onload = callback;
            reader.readAsText(file);
        }

        $("#csv_file").change(function(e) {
            var files = e.target.files;
            var file = files[0];
            readFile(file, function(e) {

            var ext = $("input#csv_file").val().split(".").pop().toLowerCase();
            if($.inArray(ext, ["csv"]) == -1) {
                alert('Upload CSV');
                return false;
            }

            if (files != undefined) {
                    var lines = e.target.result.split('\r\n');
                    for (i = 0; i < lines.length; i++)
                    {
                        if(i != 0 && lines[i] != ""){
                            data_arr[i] = lines[i].split(";");
                        }
                    }
                    var namesHtml = "";
                    var userText = "";

                    if($('#text').is(":checked"))
                    {
                        dataText = <?php echo getData("plain") ?>;
                    }
                    else if($('#html').is(":checked"))
                    {
                        dataText = <?php echo getData("html") ?>;
                    };
                   // console.log(dataText);
                    for(index = 1; index < data_arr.length; index++) {
                        if (index == 1) {
                            namesHtml += "<li class='active'><a data-toggle=\"tab\" href=\"#" + index + "\">" + data_arr[index][1] + "</a></li>";
                            userText += "<div id=\"" + index + "\" class=\"tab-pane fade in active width-70\">\n" +
                                "            <div class='user' contenteditable=\"true\" id=\"user-" + index + "\" data-meno=\"" + data_arr[index][3] + "\" data-email=\"" + data_arr[index][2] + "\">\n";
                            for (i = 0; i < dataText.length; i++) {
                                if (i == 2) {
                                    userText += dataText[i] + data_arr[index][0];
                                } else if (i == 3) {
                                    userText += dataText[i] + data_arr[index][4];
                                } else if (i == 4) {
                                    userText += dataText[i] + data_arr[index][3];
                                } else if (i == 6) {
                                    userText += dataText[i] + data_arr[index][6];
                                } else if (i == 7) {
                                    userText += dataText[i] + data_arr[index][7];
                                } else if (i == 8) {
                                    userText += dataText[i] + data_arr[index][8];
                                } else if (i == 9) {
                                    userText += dataText[i] + data_arr[index][9];
                                } else if (i == 10) {
                                    userText += dataText[i] + data_arr[index][10];
                                } else
                                    userText += dataText[i];
                            }
                            userText += "            </div>\n" +
                                "        </div>";
                        } else{
                            namesHtml += "<li><a data-toggle=\"tab\" href=\"#" + index + "\">" + data_arr[index][1] + "</a></li>";
                        userText += "<div id=\"" + index + "\" class=\"tab-pane fade width-70\">\n" +
                            "            <div class='user' contenteditable=\"true\" id=\"user-" + index + "\" data-meno=\"" + data_arr[index][3] + "\" data-email=\"" + data_arr[index][2] + "\">\n";

                        for (i = 0; i < dataText.length; i++) {
                            if (i == 2) {
                                userText += dataText[i] + data_arr[index][0];
                            } else if (i == 3) {
                                userText += dataText[i] + data_arr[index][4];
                            } else if (i == 4) {
                                userText += dataText[i] + data_arr[index][3];
                            } else if (i == 6) {
                                userText += dataText[i] + data_arr[index][6];
                            } else if (i == 7) {
                                userText += dataText[i] + data_arr[index][7];
                            } else if (i == 8) {
                                userText += dataText[i] + data_arr[index][8];
                            } else if (i == 9) {
                                userText += dataText[i] + data_arr[index][9];
                            } else if (i == 10) {
                                userText += dataText[i] + data_arr[index][10];
                            } else
                                userText += dataText[i];
                        }

                        userText += "       </div>\n" +
                            "        </div>";
                    }
                    }
                    $("#users_from_file").html(namesHtml);
                    $("#user-email-text").html(userText);
            }
            return false;
            });
        });
        
        $("#text").click(function(){
            if(!$("div#editor").hasClass("hide")) $("div#editor").addClass("hide");            
        });
        $("#html").click(function(){
            $("div#editor").toggleClass("hide");  
        });
        
        $("button.text-color").on("click", function(){
        var color = "";
        switch($(this).attr("data-color")){
          case "black":
            color = "black";
            break;
          case "red":
            color = "red"
            break;
          case "blue":
            color = "blue"
            break;
          case "green":
            color = "green"
            break;
        }
        
        // vybratie highlitnuteho textu a pridanie <span> okolo textu
        var selection= window.getSelection().getRangeAt(0);
        var selectedText = selection.extractContents();
        var span= document.createElement("span");
        span.style.color = color;
        span.appendChild(selectedText);
        selection.insertNode(span);
        
      });

    });
</script>
<script src="tableSorter.js"></script>
</html>
