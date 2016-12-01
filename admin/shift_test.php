<?php
include "check_date.php";
if (isset($_GET['action'])){
    $year = $_GET['year'];
    $month = $_GET['month'];
    $day = $_GET['day'];

    //getからのデータを格納する配列
    $name_array = array();

    //配列に名前を入れる
    for ($x = 0; $x <= 6; $x++){    //$x = day(一週間分)
        for ($y = 0; $y <= 4; $y++){ //$y == timezone(時間帯分)
            $get_name1 = "change1_" . $x . "_" . $y;
            $get_name2 = "change2_" . $x . "_" . $y;

            $name_array[0][$x][$y] = $_GET[$get_name1];
            $name_array[1][$x][$y] = $_GET[$get_name2];
        }
    }
    //tempのシフト表との名前の比較
    //名前が一致しなかった場合はnumberを上書きする
    
    /*JSONデータ(スタッフ情報)の読み込み*/
    $staff_url = "../data/management/staff.json";
    $json = file_get_contents($staff_url);
    $staff_array = json_decode($json, true);

    //時間帯情報の読み込み
    $time_zone_url = "../data/management/time_zone.json";
    $json = file_get_contents($time_zone_url);
    $time_zone_array = json_decode($json, true);
    
    for ($count = 0; $count <= 6; $count++){
        echo "<br>" . ($count + 1) . "日目------------------<br>";
        $count2 = 0;
        $numberp = 0;   //時間帯で何人入っているかの判断用

        $shift_url = "../data/shift/temp/" . $year . $month . $day . ".json";
        $json = file_get_contents($shift_url);
        $shift_array = json_decode($json, true);

        for ($time_zone_count = 0; $time_zone_count < sizeof($time_zone_array['time_zone']); $time_zone_count++){

            echo $time_zone_array['time_zone'][$time_zone_count]['name'] . "---------------------<br>";
            while (($count2 < sizeof($shift_array['shift'])) &&
                ($shift_array['shift'][$count2]['min'] == $time_zone_array['time_zone'][$time_zone_count]['min']) &&
                ($shift_array['shift'][$count2]['max'] == $time_zone_array['time_zone'][$time_zone_count]['max'])){

                $key = $shift_array['shift'][$count2]['number'];
                if ($staff_array['staff'][$key]['name'] != $name_array[$numberp][$count][$time_zone_count]){
                    echo "変更があります。<br>";
                    echo "staff_array == " . $staff_array['staff'][$key]['name'] . "<br>";
                    echo "name_array == " . $name_array[$numberp][$count][$time_zone_count] . "<br>";
                }
                else{
                    echo $numberp + 1 . "人目は変更はありませんでした。<br>";
                }
                $count2++;
                $numberp++;
            }
            $numberp = 0;
        }
        $day++;
        //日付の更新
        $day_array = check_date($year, $month, $day);
        $year = $day_array['year'];
        $month = $day_array['month'];
        $day = $day_array['day'];
    }
    
}
else{
    echo "error<br>";
}