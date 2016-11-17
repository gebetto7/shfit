<?php
function matching($year, $month, $day){
    //シフト情報の取得
    $shift_url = "../data/shift/original/" . $year . $month . $day . "original.json";
    $json = file_get_contents($shift_url);
    $shift_array = json_decode($json, true);

    /*JSONデータ(スタッフ情報)の読み込み*/
    $staff_url = "../data/management/staff.json";
    $json = file_get_contents($staff_url);
    $staff_array = json_decode($json,true);

    $time_zone_url = "../data/management/time_zone.json";
    $json = file_get_contents($time_zone_url);
    $time_zone_array = json_decode($json, true);

    $count = 0;
    for ($time_zone_count = 0; $time_zone_count < sizeof($time_zone_array['time_zone']); $time_zone_count++){
        while (($count < sizeof($shift_array['shift'])) &&
            ($shift_array['shift'][$count]['min'] == $time_zone_array['time_zone'][$time_zone_count]['min']) &&
            ($shift_array['shift'][$count]['max'] == $time_zone_array['time_zone'][$time_zone_count]['max'])){

            $staff_key = $shift_array['shift'][$count]['number'];
            $time_url = "../data/time/time" . $staff_key . ".json";
            $json = file_get_contents($time_url);
            $time_array = json_decode($json, true);

            if (($shift_array['shift'][$count]['max'] - $shift_array['shift'][$count]['min']) > 8){
                echo $staff_array['staff'][$staff_key]['name'] . "さんは8時間以上の時間を希望したため除外されました。";
            }
            elseif ($time_array['time'][0]['weekly_hours'] > 40){
                echo $staff_array['staff'][$staff_key]['name'] . "さんは今週40時間以上働いているため除外されました。";
            }
            else {
                $count++;
            }
        }
    }
    $fjson = fopen("../data/shift/" . $year . $month . $day . "shift.json", "w+b");
    fwrite($fjson, json_encode($shift_array, JSON_UNESCAPED_UNICODE));
    fclose($fjson);
}