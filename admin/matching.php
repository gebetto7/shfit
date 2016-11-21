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

    $count = 0;     //シフトを頭から参照するためのカウントキー
    $candidate_count = 0;       //法律を越えていない候補者数をカウント
    $candidate_array['shift'] = array();    //候補者を入れる配列
    $main_array['shift'] = array();         //シフト表になる配列

    //スタッフ分の今週の勤務時間計算のための配列確保
    $this_week_array = array();
    $this_week_hours['weekly_hours'] = 0;
    for ($i = 0; $i < sizeof($staff_array['staff']); $i++){
        array_push($this_week_array, $this_week_hours);
    }

    for ($time_zone_count = 0; $time_zone_count < sizeof($time_zone_array['time_zone']); $time_zone_count++){
        while (($count < sizeof($shift_array['shift'])) &&
            ($shift_array['shift'][$count]['min'] == $time_zone_array['time_zone'][$time_zone_count]['min']) &&
            ($shift_array['shift'][$count]['max'] == $time_zone_array['time_zone'][$time_zone_count]['max'])){
            //指定した時間帯と完全に一致している希望を提出している人のみを処理
            $this_time = $shift_array['shift'][$count]['max'] - $shift_array['shift'][$count]['min'];

            $staff_key = $shift_array['shift'][$count]['number'];
            $time_url = "../data/time/time" . $staff_key . ".json";
            $json = file_get_contents($time_url);
            $time_array = json_decode($json, true);


            //法律をオーバーしてる人物を除く
            if ($time_array['time'][0]['weekly_hours'] >= 40){
                echo $time_zone_array['time_zone'][$time_zone_count]['name'] . "：" . $staff_array['staff'][$staff_key]['name'] . "さんは今週" . $time_array['time'][0]['weekly_hours'] . "時間働いているため除外されました。<br>";
                $count++;
            }
            else if (($time_array['time'][0]['weekly_hours'] + $this_week_array[$staff_key]['weekly_hours']) > 40){
                echo $time_zone_array['time_zone'][$time_zone_count]['name'] . "：" . $staff_array['staff'][$staff_key]['name'] .
                    "さんは" . $this_time . "時間働くと今週" . ($time_array['time'][0]['weekly_hours'] + $this_week_array[$staff_key]['weekly_hours']) . "時間働くことになるため除外されました。<br>";
                $count++;
            }
            else if ($this_week_array[$staff_key]['weekly_hours'] > 8){
                echo $time_zone_array['time_zone'][$time_zone_count]['name'] . "：" . $staff_array['staff'][$staff_key]['name'] . "さんは一日で" . $this_week_array[$staff_key]['weekly_hours'] . "時間働いているため除外されました。<br>";
                $count++;
            }
            else if ($this_week_array[$staff_key]['weekly_hours'] > 8){
                echo $time_zone_array['time_zone'][$time_zone_count]['name'] . "：" . $staff_array['staff'][$staff_key]['name'] . "さんは一日で" . $this_week_array[$staff_key]['weekly_hours'] . "時間働いているため除外されました。<br>";
                $count++;
            }
            else {
                //法律をオーバーしていない人を入れる
                $slice = array_slice($shift_array['shift'][$count], 0);
                array_push($candidate_array['shift'] , $slice);
                $count++;
                $candidate_count++;
            }
        }
        //法律を越えていない人物の中から週間労働時間が一番短い人物を残す
        if ($candidate_count == 0){    //シフトに入れる人がいなかった場合
            //何もしない
        }
        else if ($candidate_count == 1){   //一人の場合
            array_push($main_array['shift'], $candidate_array['shift'][0]); //そのまま入れる
            //確定した人の週間時間の計算
            $this_week_array[$candidate_array['shift'][0]['number']]['weekly_hours'] += $this_time;
        }
        else if ($candidate_count > 1){    //二人以上いた場合
            //週間労働時間が短い順にソートする
            for ($i = 0; $i < $candidate_count; $i++) {

                $key = $candidate_array['shift'][$i]['number'];
                $time_url = "../data/time/time" . $key . ".json";
                $json = file_get_contents($time_url);
                $compare_arrayi = json_decode($json, true);
                $time = $compare_arrayi['time'][0]['weekly_hours'] + $this_week_array[$key]['weekly_hours'];

                for ($j = $i + 1; $j < $candidate_count; $j++) {

                    $key = $candidate_array['shift'][$j]['number'];
                    $time_url = "../data/time/time" . $key . ".json";
                    $json = file_get_contents($time_url);
                    $compare_arrayj = json_decode($json, true);
                    $time2 = $compare_arrayj['time'][0]['weekly_hours'] + $this_week_array[$key]['weekly_hours'];

                    if ($time > $time2){
                        $swap = $candidate_array['shift'][$i];
                        $candidate_array['shift'][$i] = $candidate_array['shift'][$j];
                        $candidate_array['shift'][$j] = $swap;
                    }
                }
            }
            //ソートした配列の1番目と2番目の人をシフトに入れる(candidate_arrayには候補者が残っている)
            array_push($main_array['shift'], $candidate_array['shift'][0]);
            array_push($main_array['shift'], $candidate_array['shift'][1]);

            //確定した人の週間時間の計算
            $this_week_array[$candidate_array['shift'][0]['number']]['weekly_hours'] += $this_time;
            $this_week_array[$candidate_array['shift'][1]['number']]['weekly_hours'] += $this_time;
        }

        $candidate_array['shift'] = array();    //候補者配列の初期化
        $candidate_count = 0;
    }

    $fjson = fopen("../data/shift/" . $year . $month . $day . "shift.json", "w+b");
    fwrite($fjson, json_encode($main_array, JSON_UNESCAPED_UNICODE));
    fclose($fjson);
}