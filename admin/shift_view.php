<?php
//日付を引数として受け取り、
function shift_view($folder, $year, $month, $day)
{
    /*JSONデータ(スタッフ情報)の読み込み*/
    $staff_url = "../data/management/staff.json";
    $json = file_get_contents($staff_url);
    $staff_array = json_decode($json,true);

    /*日付の表示*/
    echo $year . "年" . $month . "月" . $day . "日<br>";
    /*JSONデータ(シフト情報)の読み込み*/
    $shift_url = "../data/shift/" . $folder . "/" . $year . $month . $day . ".json";
    swap($shift_url);
    $json = file_get_contents($shift_url);
    $shift_array = json_decode($json, true);

    /*時間の表示(表)*/
    echo '<table border="1" cellpadding="2"><tr><td></td>';
    for ($a = 0; $a <= 23; $a++) {
        echo '<td>' . $a . '</td>';
    }
    echo '</tr>';

    for ($shift_count = 0; $shift_count < sizeof($shift_array['shift']); $shift_count++) {
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
            //ここまで
    }
        echo '</table><br>';
}