<?php
if(isset($_FILES['image'])){
    $errors= array();
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $target_dir = "/home/xborcin/public_html/files/";
    $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));

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

            echo '<div id="tables" class = "table">';
            echo "<table id=\"files\" style=\"float: left;\" cellspacing=\"0\" cellpadding=\"0\">";

            // Cycle through the array
            for ($i = 0; $i<=5; $i++) {
                echo "<tr>";
                for($k = 0; $k<=4; $k++) {

                    // Output a row

                    echo "<td>" . $decode[$i][$k] . "</td>";

                }
                echo "</tr>";
            }

            // Close the table
            echo "</table>";
            echo '</div>';




            unlink( $target_dir. $file_name);
        }
    }else{
        print_r($errors);
    }
}
?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>


<form action = "" method = "POST" enctype = "multipart/form-data">
    <input type = "file" name = "image" />
    <input type = "submit"/>
    

</form>

</body>
</html>