<?php
    //シフト表を最終作成日の取得
    $last_url = "../data/shift/last.json";
    $json = file_get_contents($last_url);
    $last = json_decode($json, true);

    /*JSONデータ(シフト情報)の読み込み*/
    $shift_url = "../data/shift/2016102shift.json";
    $json = file_get_contents($shift_url);
    $shift_array = json_decode($json,true);

    var_dump($shift_array["shift"][0]); echo "<br>";
    var_dump($shift_array["shift"][1]); echo "<br>";

    $swap = $shift_array["shift"][0];
    $shift_array["shift"][0] = $shift_array["shift"][1];
    $shift_array["shift"][1] = $swap;

    var_dump($shift_array["shift"][0]); echo "<br>";
    var_dump($shift_array["shift"][1]); echo "<br>";

    $fjson = fopen($shift_url, "w+b");
    fwrite($fjson, json_encode($shift_array, JSON_UNESCAPED_UNICODE));
    fclose($fjson);
