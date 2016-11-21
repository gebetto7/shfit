<?php
function time_check($year, $month, $day){
    $shift_url = "../data/shift/" . $year . $month . $day . "shift.json";
    $json = file_get_contents($shift_url);
    $shift_array = json_decode($json, true);

    /*JSONデータ(スタッフ情報)の読み込み*/
    $staff_url = "../data/management/staff.json";
    $json = file_get_contents($staff_url);
    $staff_array = json_decode($json,true);

    for ($count = 0; $count <= sizeof($shift_array['shift']); $count++){

        $key = $shift_array['shift'][$count]['number'];
        $time_url = "../data/time/time" . $key . ".json";
        $time_array = json_decode($json, true);
        
    }
}