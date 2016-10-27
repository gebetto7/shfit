<?php
    function calculation($day)
    {
        //シフト表を最終作成日の取得
        $last_url = "../data/shift/last.json";
        $json = file_get_contents($last_url);
        $last = json_decode($json, true);

        //時給データの取得
        $wage_url = "../data/management/time_zone.json";
        $json = file_get_contents($wage_url);
        $wage = json_decode($json, true);

        //for ($count = $last['day'] + 1; $count <= $last['day'] + 7; $count++){

        /*JSONデータ(シフト情報)の読み込み*/
        $shift_url = "../data/shift/" . $last['year'] . $last['month'] . $day . "shift.json";
        $json = file_get_contents($shift_url);
        $shift_array = json_decode($json, true);

        /*JSONデータ(スタッフ情報)の読み込み*/
        $staff_url = "../data/management/staff.json";
        $json = file_get_contents($staff_url);
        $staff_array = json_decode($json, true);

        for ($shift_count = 0; $shift_count < sizeof($shift_array['shift']); $shift_count++) {   //シフト表に入ってる人の数だけループ
            for ($staff_count = 0; $staff_count < sizeof($staff_array['staff']); $staff_count++) {
                /*スタッフ情報とシフト情報を一致させるためにnumberに該当するスタッフが参照できるまでループ*/
                if ($shift_array['shift'][$shift_count]['number'] == $staff_array['staff'][$staff_count]['number']) {
                    /*一致した場合*/
                    $name = $staff_array['staff'][$staff_count]['name'];    //名前の格納
                    /*一致した従業員の番号に応じた勤務合計時間の取得*/
                    $sum_url = "../data/salary/salary" . $staff_count . ".json";
                    $json = file_get_contents($sum_url);
                    $sum = json_decode($json, true);
                    break;
                }
            }

            $salary_sum = 0;
            $salary_sum = $salary_sum + ($shift_array['shift'][$shift_count]['max'] - $shift_array['shift'][$shift_count]['min']);
            $sum['salary'][0]['weekly_hours'] = $sum['salary'][0]['weekly_hours'] + $salary_sum;
            echo $name . ":  勤務時間: " . $salary_sum . "時間  合計時間: " . $sum['salary'][0]['weekly_hours'] . "時間<br>";
            /*合計時間のjsonファイルへの書き出し*/
            $fjson = fopen($sum_url, "w+b");
            fwrite($fjson, json_encode($sum, JSON_UNESCAPED_UNICODE));
            fclose($fjson);
        }
    }
    //}