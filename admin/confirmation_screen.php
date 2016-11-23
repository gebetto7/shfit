<?php
    //===================================================
    //confirmation_screen関数:
    //-------------------------------------------------
    //自動作成したシフト表を反映するまでにワンクッション置くための確認画面(修正もここで行う？)
    //===================================================
    function confirmation_screen($year, $month, $day){

        //シフト情報の取得
        $shift_url = "../data/shift/temp/" . $year . $month . $day . ".json";
        $json = file_get_contents($shift_url);
        $shift_array = json_decode($json, true);

    }