<?php
    //引数としてシフト表のurlを受け取り、そのシフト表を時間が早い順に整理する
    function swap($url)
    {
        //シフト表を最終作成日の取得
        $last_url = "../data/shift/last.json";
        $json = file_get_contents($last_url);
        $last = json_decode($json, true);

        /*JSONデータ(シフト情報)の読み込み*/
        $json = file_get_contents($url);
        $shift_array = json_decode($json, true);

        for ($i = 0; $i < sizeof($shift_array["shift"]) - 1; $i++) {
            for ($j = $i + 1; $j < sizeof($shift_array["shift"]) - 1; $j++) {
                if ($shift_array["shift"][$i]["min"] > $shift_array["shift"][$j]["min"]) {
                    $swap = $shift_array["shift"][$i];
                    $shift_array["shift"][$i] = $shift_array["shift"][$j];
                    $shift_array["shift"][$j] = $swap;
                } else if ($shift_array["shift"][$i]["min"] == $shift_array["shift"][$j]["min"]) {
                    if ($shift_array["shift"][$i]["max"] > $shift_array["shift"][$j]["max"]) {
                        $swap = $shift_array["shift"][$i];
                        $shift_array["shift"][$i] = $shift_array["shift"][$j];
                        $shift_array["shift"][$j] = $swap;
                    }
                }
            }
        }

        $fjson = fopen($url, "w+b");
        fwrite($fjson, json_encode($shift_array, JSON_UNESCAPED_UNICODE));
        fclose($fjson);
    }
