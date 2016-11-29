<?php
include 'check_date.php';
if (isset($_GET['action'])){
    if ($_GET['action'] == 'enter'){    //確定
        echo "シフト表を保存しました。<br>";
        echo "<form action = 'shift_create_selectday.php'>";

        $year = $_GET['year'];
        $month = $_GET['month'];
        $day = $_GET['day'];
        
        // シフト情報の読み込み
        for ($count = 0; $count <= 6; $count++){
            $shift_url = "../data/shift/temp/" . $year . $month . $day . ".json";
            $json = file_get_contents($shift_url);
            $shift_array = json_decode($json, true);

            $fjson = fopen("../data/shift/main/" . $year . $month . $day . ".json", "w+b");
            fwrite($fjson, json_encode($shift_array, JSON_UNESCAPED_UNICODE));
            fclose($fjson);

            $day++;
            //日付の更新
            $day_array = check_date($year, $month, $day);
            $year = $day_array['year'];
            $month = $day_array['month'];
            $day = $day_array['day'];
        }
        //tempフォルダの初期化
        $path = '../data/shift/temp';
        $res_dir = opendir( $path );
        while ( $file_name = readdir( $res_dir ) ){
            if (is_file($path. "/" .$file_name)) {
                unlink($path . "/" . $file_name);
            }
        }
        //temp/candidateフォルダの初期化
        $path = '../data/shift/temp/candidate';
        $res_dir = opendir( $path );
        while ( $file_name = readdir( $res_dir ) ){
            if (is_file($path. "/" .$file_name)) {
                unlink($path . "/" . $file_name);
            }
        }
        echo "<button type = 'submit'>戻る</button>";
        echo "</form>";
    }
    else if($_GET['action'] == 'modify'){   //修正

        $year = $_GET['year'];
        $month = $_GET['month'];
        $day = $_GET['day'];

        /*JSONデータ(スタッフ情報)の読み込み*/
        $staff_url = "../data/management/staff.json";
        $json = file_get_contents($staff_url);
        $staff_array = json_decode($json, true);
        for ($count = 0; $count <= 6; $count++) {

            $candidate_url = "../data/shift/temp/candidate/" . $year . $month . $day . ".json";
            $json = file_get_contents($candidate_url);
            $candidate_array = json_decode($json,true);

            while ($key_name = current($candidate_array)){
                echo key($candidate_array);
                /*時間の表示(表)*/
                echo '<table border="1" cellpadding="2"><tr><td></td>';
                for ($a = 0; $a <= 23; $a++) {
                    echo '<td>' . $a . '</td>';
                }
                echo '</tr>';

                for ($shift_count = 0; $shift_count < sizeof($candidate_array['shift']); $shift_count++) {
                    //シフト表1列表示部分
                    //ここから
                    echo '<tr>';
                    /*従業員名の格納*/
                    $number = $shift_array["shift"][$shift_count]["number"];
                    /*従業員名の表示*/
                    echo '<td>' . $staff_array['staff'][$number]['name'] . '</td>';

                    /*時間表表示*/
                    for ($time_count = 0; $time_count <= 23; $time_count++) {
                        if ($shift_array['shift'][$shift_count]['min'] <= $time_count && $shift_array['shift'][$shift_count]['max'] > $time_count) {
                            echo "<td>●</td>";
                        } else {
                            echo "<td>　</td>";
                        }
                    }
                    echo '</tr>';
                }
                next($candidate_array);
            }
            echo '</table><br>';
            $day++;
        }

    }
    else{
        echo "error<br>";
    }
}
else{
    echo "error<br>";
}