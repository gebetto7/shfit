<?php
    //シフト表を最終作成日の取得
    $last_url = "../data/shift/last.json";
    $json = file_get_contents($last_url);
    $last = json_decode($json, true);

    /*JSONデータ(シフト情報)の読み込み*/
    $shift_url = "../data/shift/2016102shift.json";
    $json = file_get_contents($shift_url);
    $shift_array = json_decode($json,true);

    for ($i = 0; $i < sizeof($shift_array["shift"]) - 1; $i++){
        for ($j = $i + 1; $j < sizeof($shift_array["shift"]) - 1; $j++){
            if ($shift_array["shift"][$i]["min"] > $shift_array["shift"][$j]["min"]){
                $swap = $shift_array["shift"][$i];
                $shift_array["shift"][$i] = $shift_array["shift"][$j];
                $shift_array["shift"][$j] = $swap;
            }
            else if($shift_array["shift"][$i]["min"] == $shift_array["shift"][$j]["min"]){
                if ($shift_array["shift"][$i]["max"] > $shift_array["shift"][$j]["max"]){
                    $swap = $shift_array["shift"][$i];
                    $shift_array["shift"][$i] = $shift_array["shift"][$j];
                    $shift_array["shift"][$j] = $swap;
                }
            }
        }
    }

    $fjson = fopen($shift_url, "w+b");
    fwrite($fjson, json_encode($shift_array, JSON_UNESCAPED_UNICODE));
    fclose($fjson);
