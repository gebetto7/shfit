<?php
    /*JSONデータ(シフト情報)の読み込み*/
    $shift_url = "../data/shift/2016102shift.json";
    $json = file_get_contents($shift_url);
    $shift_array = json_decode($json,true);
