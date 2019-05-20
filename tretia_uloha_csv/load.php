<?php
session_start();
if(isset($_POST['Submit'])){
    $oddelovac = $_POST['char'];
    $filename = $_FILES['sel_file']['name'];
    $casti_mena = explode('.', $filename);

    //pocet riadkov suboru
    $file = new SplFileObject($filename, 'r');
    $file->seek(PHP_INT_MAX);
    $riadky=$file->key() - 1;
    $hlavicka=array();
    $do_csv = array();

    if(strtolower(end($casti_mena)) == "csv") {
        $meno_suoru = $_FILES['sel_file']['tmp_name'];
        $handle = fopen($meno_suoru, "r");

        //pushovanie dat do poli
        $data = fgetcsv($handle, 100000, $oddelovac);
        //tu vlozim len zahlavie csv suboru
        for($i = 0; $i < count($data); $i++){
            array_push($hlavicka,$data[$i]);
        }
        //tu pushujem uz komplet vsetky zaznamy okrem hlavicky
        $zaznamy = array();
        while(($data = fgetcsv($handle,100000, $oddelovac)) !== false){\
            array_push($zaznamy,$data);
        }
        //pushnutie hesiel do zahlavia
        array_splice($hlavicka,4,0, "heslo");
        //pushnutie hlavicky do finalneho komplet pola pre csv subor
        array_push($do_csv,$hlavicka);
        //pushnutie vsetkych udajov s generovanymi heslami do finalneho pola
        foreach ($zaznamy as $item){
            $heslo = randstr();
            array_splice($item,4,0,$heslo);
            array_push($do_csv,$item);
        }
        $_SESSION['udaje'] = $do_csv;
        $_SESSION['oddelovac'] = $oddelovac;
        echo '<a href="download.php">'.$language[$_SESSION["lang"]][15].'<br>';
        fclose($handle);
	echo '<a href="index.php">'.$language[$_SESSION["lang"]][6];
    }else{
        echo $language[$_SESSION["lang"]][14];
    }
}

//generovanie hesla
function randstr ($len=10, $abc="aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ0123456789") {
    $letters = str_split($abc);
    $str = "";
    for ($i=0; $i<=$len; $i++) {
        $str .= $letters[rand(0, count($letters)-1)];
    };
    return $str;
};
//echo "Heslo je:  " . randstr(14);

?>