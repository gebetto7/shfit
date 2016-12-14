<?php
function matching($staff_array, $year, $month, $day){
    //シフト情報の取得
    $shift_url = "../data/shift/original/" . $year . $month . $day . ".json";
    $json = file_get_contents($shift_url);
    $shift_array = json_decode($json, true);
    /*JSONデータ(スタッフ情報)の読み込み*/
    //$staff_url = "../data/management/staff.json";
    //$json = file_get_contents($staff_url);
    //$staff_array = json_decode($json,true);
    //時間帯情報の読み込み
    $time_zone_url = "../data/management/time_zone.json";
    $json = file_get_contents($time_zone_url);
    $time_zone_array = json_decode($json, true);
    //候補者データの読み込み
    $candidate_url = "../data/shift/temp/candidate/" . $year . $month . $day .".json";
    if (!file_exists($candidate_url)){
        file_put_contents($candidate_url, "");
    }

    $count = 0;     //シフトを頭から参照するためのカウントキー
    $candidate_count = 0;       //法律を越えていない候補者数をカウント
    $candidate_array['shift'] = array();    //候補者を入れる配列
    $main_array['shift'] = array();         //シフト表になる配列
    $message_array = "";
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
            if (($time_array['time'][0]['weekly_hours'] + $this_week_array[$staff_key]['weekly_hours']) >= 40){
                $message_array .= $time_zone_array['time_zone'][$time_zone_count]['name'] . "：" . $staff_array['staff'][$staff_key]['name'] . "さんは今週" . $time_array['time'][0]['weekly_hours'] . "時間働いているため除外されました。<br>";
                $count++;
            }
            else if ($this_week_array[$staff_key]['weekly_hours'] > 8){
                $message_array .= $time_zone_array['time_zone'][$time_zone_count]['name'] . "：" . $staff_array['staff'][$staff_key]['name'] . "さんは一日で" . $this_week_array[$staff_key]['weekly_hours'] . "時間働いているため除外されました。<br>";
                $count++;
            }
            else if (($time_array['time'][0]['weekly_hours'] + $this_week_array[$staff_key]['weekly_hours'] + $this_time) > 40){
                $message_array .= $time_zone_array['time_zone'][$time_zone_count]['name'] . "：" . $staff_array['staff'][$staff_key]['name'] .
                    "さんは" . $this_time . "時間働くと今週" . ($this_week_array[$staff_key]['weekly_hours'] + $this_time + $time_array['time'][0]['weekly_hours']) . "時間働くことになるため除外されました。<br>";
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
            $splice = array_splice($candidate_array['shift'], 0, 1);    //候補者配列から実際にシフトに入った人物を削除する
        }
        else if ($candidate_count > 1){    //二人以上いた場合
            //週間労働時間が短い順にソートする
            for ($i = $candidate_count - 1; $i > 0; $i--) {
                for ($j = 0; $j < $i; $j++) {
                    $key = $candidate_array['shift'][$j]['number'];
                    $time_url = "../data/time/time" . $key . ".json";
                    $json = file_get_contents($time_url);
                    $compare_arrayi = json_decode($json, true);
                    $time = $compare_arrayi['time'][0]['weekly_hours'] + $this_week_array[$key]['weekly_hours'];

                    $key2 = $candidate_array['shift'][$j + 1]['number'];
                    $time_url2 = "../data/time/time" . $key2 . ".json";
                    $json = file_get_contents($time_url2);
                    $compare_arrayj = json_decode($json, true);
                    $time2 = $compare_arrayj['time'][0]['weekly_hours'] + $this_week_array[$key2]['weekly_hours'];
                    if ($time > $time2){
                        $swap = $candidate_array['shift'][$j];
                        $candidate_array['shift'][$j] = $candidate_array['shift'][$j + 1];
                        $candidate_array['shift'][$j + 1] = $swap;
                    }
                }
            }
            //ソートした配列の1番目と2番目の人をシフトに入れる(candidate_arrayには候補者が残っている)
            array_push($main_array['shift'], $candidate_array['shift'][0]);
            array_push($main_array['shift'], $candidate_array['shift'][1]);
            //確定した人の週間時間の計算
            $this_week_array[$candidate_array['shift'][0]['number']]['weekly_hours'] += $this_time;
            $this_week_array[$candidate_array['shift'][1]['number']]['weekly_hours'] += $this_time;

            $splice = array_splice($candidate_array['shift'], 0, 2);    //候補者配列から実際にシフトに入った人物を削除する
            $candidate_array_file[$time_zone_array['time_zone'][$time_zone_count]['name']] = $candidate_array['shift'];

            $fjson = fopen($candidate_url, "w+b");
            fwrite($fjson, json_encode($candidate_array_file, JSON_UNESCAPED_UNICODE));
            fclose($fjson);
        }
        $candidate_array['shift'] = array();    //候補者配列の初期化
        $candidate_count = 0;
    }
    $fjson = fopen("../data/shift/temp/" . $year . $month . $day . ".json", "w+b");
    fwrite($fjson, json_encode($main_array, JSON_UNESCAPED_UNICODE));
    fclose($fjson);
    return $message_array;
}