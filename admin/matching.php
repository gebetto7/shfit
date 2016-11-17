<?php
    //swapされたシフト希望表からシフト表を作成する
    function matching($year, $month, $day){
        //シフト情報の取得
        $shift_url = "../data/shift/original/" . $year . $month . $day . "original.json";
        $json = file_get_contents($shift_url);
        $shift_array = json_decode($json, true);

        /*JSONデータ(スタッフ情報)の読み込み*/
        $staff_url = "../data/management/staff.json";
        $json = file_get_contents($staff_url);
        $staff_array = json_decode($json,true);

        $count = 0;
        while (($shift_array['shift'][$count]['min'] == 0) &&
            ($shift_array['shift'][$count]['max'] == 6)){
            //シフトの先頭から読み込み、その従業員の勤務時間を参照する
            $staff_key = $shift_array['shift'][$count]['number'];
            $time_url = "../data/time/time" . $staff_key . ".json";
            $json = file_get_countents($time_url);
            $time_array = json_decode($json, true);
            if ($staff_array['staff'][$staff_key]['']){

            }
            $count++;
        }

        while (($shift_array['shift'][$count]['min'] == 6) &&
            ($shift_array['shift'][$count]['max'] == 9)){

            $count++;
        }

        while (($shift_array['shift'][$count]['min'] == 9) &&
            ($shift_array['shift'][$count]['max'] == 17)){

            $count++;
        }

        while (($shift_array['shift'][$count]['min'] == 17) &&
            ($shift_array['shift'][$count]['max'] == 22)){

            $count++;
        }

    }