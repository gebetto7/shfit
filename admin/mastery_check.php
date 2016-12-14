<?php
    //===================================================
    //mastery_check関数:
    //-------------------------------------------------
    //習熟度のチェックとシフトに入っている人数のチェックを行う
    //===================================================
    function mastery_check($folder, $year, $month, $day){
        //シフト情報の取得
        $shift_url = "../data/shift/" . $folder . "/" . $year . $month . $day . ".json";
        if (!file_exists($shift_url)){
            return 0;
        }
        $json = file_get_contents($shift_url);
        $shift_array = json_decode($json, true);

        //時間帯データの取得
        $time_zone_url = "../data/management/time_zone.json";
        $json = file_get_contents($time_zone_url);
        $time_zone_array = json_decode($json, true);

        /*JSONデータ(スタッフ情報)の読み込み*/
        $staff_url = "../data/management/staff.json";
        $json = file_get_contents($staff_url);
        $staff_array = json_decode($json,true);

        $mastery_sum = 0;       //習熟度の合計
        $count = 0;
        $shift_count = 0;       //時間帯に何人入っているかの確認

        $date = $year . $month . $day;
        $shift_blank[$date] = Array();       //空いている時間帯保存用

        for ($time_zone_count = 0; $time_zone_count < sizeof($time_zone_array['time_zone']); $time_zone_count++) {
            while (($count < sizeof($shift_array['shift'])) &&
                ($shift_array['shift'][$count]['min'] == $time_zone_array['time_zone'][$time_zone_count]['min']) &&
                ($shift_array['shift'][$count]['max'] == $time_zone_array['time_zone'][$time_zone_count]['max'])) {

                $key = $shift_array['shift'][$count]['number'];
                $mastery_sum += $staff_array['staff'][$key]['mastery'];
                $count++;
                $shift_count++;
            }
            switch ($shift_count){
                case 2:     //正常動作
                    if ($mastery_sum < 4){
                        echo $time_zone_array['time_zone'][$time_zone_count]['name'] . "の習熟度が足りません。<br>";
                    }
                    break;
                case 1:     //一人足りない場合
                    echo $time_zone_array['time_zone'][$time_zone_count]['name'] . "の従業員が1人足りません。<br>";
                    array_push($shift_blank[$date], $time_zone_array['time_zone'][$time_zone_count]['name']);
                    break;
                case 0:     //一人も入っていない場合
                    echo $time_zone_array['time_zone'][$time_zone_count]['name'] . "に出勤可能な従業員がいません。<br>";
                    array_push($shift_blank[$date], $time_zone_array['time_zone'][$time_zone_count]['name']);
                    array_push($shift_blank[$date], $time_zone_array['time_zone'][$time_zone_count]['name']);
                    break;
            }
            $mastery_sum = 0;
            $shift_count  =0;
        }
        if ($shift_blank[$date]){
            $fjson = fopen("../data/shift/blank/" . $year . $month . $day . ".json", "w+b");
            fwrite($fjson, json_encode($shift_blank, JSON_UNESCAPED_UNICODE));
            fclose($fjson);
        }
    }