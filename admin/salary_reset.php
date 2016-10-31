<?php
    /*JSONデータ(スタッフ情報)の読み込み*/
    $staff_url = "../data/management/staff.json";
    $json = file_get_contents($staff_url);
    $staff_array = json_decode($json,true);

    for ($count = 0; $count <= sizeof($staff_array["staff"]); $count++){
        $url = "../data/time/time" . $count . ".json";
        $json = file_get_contents($url);
        $sum = json_decode($json, true);

        $sum["time"][0]["weekly_hours"] = 0;
        $sum["time"][0]["weekly_days"] = 0;

        $sum["time"][1]["monthly_hours"] = 0;
        $sum["time"][1]["monthly_days"] = 0;

        $sum["time"][2]["yearly_hours"] = 0;
        $sum["time"][2]["yearly_days"] = 0;

        $fjson = fopen($url, "w+b");
        fwrite($fjson, json_encode($sum, JSON_UNESCAPED_UNICODE));
        fclose($fjson);
    }