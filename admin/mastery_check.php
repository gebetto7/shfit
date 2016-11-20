<?php
    function mastery_check($year, $month, $day){
        header("Content-Type: application/json; charset=utf-8");
        //シフト情報の取得
        $shift_url = "../data/shift/" . $year . $month . $day . "shift.json";
        $json = file_get_contents($shift_url);
        $shift_array = json_decode($json, true);

        /*JSONデータ(スタッフ情報)の読み込み*/
        $staff_url = "../data/management/staff.json";
        $json = file_get_contents($staff_url);
        $staff_array = json_decode($json,true);
        
        
        $count = 0;
        $mastery_sum = 0;
        while (($shift_array['shift'][$count]['min'] == 0) &&
                ($shift_array['shift'][$count]['max'] == 6)){
            $url = $shift_array['shift'][$count]['number'];
            $mastery_sum = $mastery_sum + $staff_array['staff'][$url]['mastery'];
            $count++;
        }
        if ($mastery_sum < 4){
            echo "<br>夜勤の習熟度が足りません。<br>";
        }
        while (($shift_array['shift'][$count]['min'] == 6) &&
                ($shift_array['shift'][$count]['max'] == 9)){
            $url = $shift_array['shift'][$count]['number'];
            $mastery_sum = $mastery_sum + $staff_array['staff'][$url]['mastery'];
            $count++;
        }
        if ($mastery_sum < 4){
            echo "<br>朝勤の習熟度が足りません。<br>";
        }
        while (($shift_array['shift'][$count]['min'] == 9) &&
                ($shift_array['shift'][$count]['max'] == 17)){
            $url = $shift_array['shift'][$count]['number'];
            $mastery_sum = $mastery_sum + $staff_array['staff'][$url]['mastery'];
            $count++;
        }
        if ($mastery_sum < 4){
            echo "<br>昼勤の習熟度が足りません。<br>";
        }
        while (($shift_array['shift'][$count]['min'] == 17) &&
                ($shift_array['shift'][$count]['max'] == 22)){
            $url = $shift_array['shift'][$count]['number'];
            $mastery_sum = $mastery_sum + $staff_array['staff'][$url]['mastery'];
            $count++;
        }
        if ($mastery_sum < 4){
            echo "<br>夕勤の習熟度が足りません。<br>";
        }
    }