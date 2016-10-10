<?php
    //シフト表を最終作成日の取得
    $last_url = "../data/shift/last.json";
    $json = file_get_contents($last_url);
    $last = json_decode($json, true);

    for ($count = $last['day'] + 1; $count <= $last['day'] + 7; $count++){

        /*JSONデータ(シフト情報)の読み込み*/
        $shift_url = "../data/shift/" . $last['year'] . $last['month'] . $count . "shift.json";
        $json = file_get_contents($shift_url);
        $shift_array = json_decode($json,true);

        /*JSONデータ(スタッフ情報)の読み込み*/
        $staff_url = "../data/staff.json";
        $json = file_get_contents($staff_url);
        $staff_array = json_decode($json,true);
        var_dump($staff_array);

        /*勤務合計時間計算のための*/
        $salary_url = "";
        echo $count . "------------------------------<br><br>";
        for ($shift_count = 0; $shift_count < sizeof($shift_array['shift']); $shift_count++){
            for ($staff_count = 0; $staff_count < sizeof($staff_array['staff']); $staff_count++){
                if ($shift_array['shift'][$shift_count]['number'] == $staff_array['staff'][$staff_count]['number']) {
                    $name = $staff_array['staff'][$staff_count]['name'];
                }
            }
            $salary_sum = 0;
            $salary_sum = $salary_sum + ($shift_array['shift'][$shift_count]['max'] - $shift_array['shift'][$shift_count]['min']);
            echo $name . "さんは" . $salary_sum . "時間働きました<br>";
        }
    }
?>