<?php
if (isset($_GET['action'])){
    if ($_GET['action'] == 'enter'){    //
        echo "シフト表を保存しました。<br>";
        echo "<form action = 'shift_create_selectday.php'>";

        $year = $_GET['year'];
        $month = $_GET['month'];
        $day = $_GET['day'];
        
        // シフト情報の読み込み
        $shift_url = "../data/shift/temp/" . $year . $month . $day . ".json";
        $json = file_get_contents($shift_url);
        $shift_array = json_decode($json, true);

        $fjson = fopen("../data/shift/main/" . $year . $month . $day . ".json", "w+b");
        fwrite($fjson, json_encode($shift_array, JSON_UNESCAPED_UNICODE));
        fclose($fjson);

        //tempフォルダの初期化
        $path = '../data/shift/temp';
        $res_dir = opendir( $path );
        while ( $file_name = readdir( $res_dir ) ){
            if (is_file($path. "/" .$file_name)) {
                unlink($path . "/" . $file_name);
            }
        }
        echo "<button type = 'submit'>戻る</button>";
        echo "</form>";
    }
    else{   //修正画面へ
        header("Location: shift_modify.php");
    }
}
else{
    echo "error<br>";
}