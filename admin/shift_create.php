<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>シフト表作成</title>
</head>
<body>
    <?php
        include 'shift_swap.php';
        include 'time_calculation.php';   //勤務合計時間算出
        include 'shift_view_func.php';  //シフト閲覧
        include 'mastery_check.php';
        include 'time_check.php';
        include 'matching.php';

        //シフト表を最終作成日の取得
        $last_url = "../data/shift/last.json";
        $json = file_get_contents($last_url);
        $last = json_decode($json, true);
        $last['day']++;

        /*JSONデータ(スタッフ情報)の読み込み*/
        $staff_url = "../data/management/staff.json";
        $json = file_get_contents($staff_url);
        $staff_array = json_decode($json,true);
    
        for ($count = 0; $count <= 6; $count++){
            //日にちの妥当性の判断については、別途関数を用意し、そこで判断して正しかった場合通常動作、正しくなかった場合は月及び年の繰り上げを行う
            //そのための関数を用意する
            
            matching($last['year'], $last['month'], $last['day']);    //希望表からシフト表を作成
            mastery_check($last['year'], $last['month'], $last['day']);
            shift_view($last['year'], $last['month'], $last['day']);
            time_calculation($last['year'], $last['month'], $last['day']);
            $last['day']++;
            echo "<br><br>";
        }
    
        for ($count = 0; $count < sizeof($staff_array['staff']); $count++){
            //週間時間と日数のリセット
            $time_url = "../data/time/time" . $count . ".json";
            $json = file_get_contents($time_url);
            $time = json_decode($json, true);
            
            $time['time'][0]['weekly_hours'] = 0;
            $time['time'][0]['weekly_days'] = 0;

            /*合計時間のjsonファイルへの書き出し*/
            $fjson = fopen($time_url, "w+b");
            fwrite($fjson, json_encode($time, JSON_UNESCAPED_UNICODE));
            fclose($fjson);
        }
    ?>
</body>
</html>
