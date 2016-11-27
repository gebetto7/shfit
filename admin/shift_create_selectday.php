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
    include 'shift_view.php';  //シフト閲覧
    include 'mastery_check.php';
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

    $count = 0;
    $week_count = 0;
    $key = 0;
    while ($week_count < 4){
        while ($count < 7) {
            if (checkdate($last['month'], $last['day'], $last['year'])) {
                $day_array[$key]['year'] = $last['year'];
                $day_array[$key]['month'] = $last['month'];
                $day_array[$key]['day'] = $last['day'];

                $last['day']++;
                $key++;
                $count++;
            }
            else if($last['month'] != 13){
                $last['day'] = 1;
                $last['month']++;
                if ($last['month'] == 13) {
                    $last['year']++;
                    $last['month'] = 1;
                }
                $day_array[$key]['year'] = $last['year'];
                $day_array[$key]['month'] = $last['month'];
                $day_array[$key]['day'] = $last['day'];

                $last['day']++;
                $key++;
                $count++;
            }
        }
        $count  = 0;
        $week_count++;
    }
    $day1 = $day_array[0]['year'] . "/" . $day_array[0]['month'] . "/" . $day_array[0]['day'] . "～" . $day_array[6]['year'] . "/" . $day_array[6]['month'] . "/" . $day_array[6]['day'];
    $day2 = $day_array[7]['year'] . "/" . $day_array[7]['month'] . "/" . $day_array[7]['day'] . "～" . $day_array[13]['year'] . "/" . $day_array[13]['month'] . "/" . $day_array[13]['day'];
    $day3 = $day_array[14]['year'] . "/" . $day_array[14]['month'] . "/" . $day_array[14]['day'] . "～" . $day_array[20]['year'] . "/" . $day_array[20]['month'] . "/" . $day_array[20]['day'];
    $day4 = $day_array[21]['year'] . "/" . $day_array[21]['month'] . "/" . $day_array[21]['day'] . "～" . $day_array[27]['year'] . "/" . $day_array[27]['month'] . "/" . $day_array[27]['day'];

    //日付の判定を行い、その日付をformのnameに表示する
    echo "<form action = 'shift_create.php' method = 'get'>";
    echo "<input type = 'submit' name = 'day' value = '$day1'><br><br>";
    echo "<input type = 'submit' name = 'day' value = '$day2'><br><br>";
    echo "<input type = 'submit' name = 'day' value = '$day3'><br><br>";
    echo "<input type = 'submit' name = 'day' value = '$day4'><br><br>";
    echo "</form>";
    //confirmation_screen($message, $last['year'], $last['month'], $last['day']);
    /*for ($count = 0; $count < sizeof($staff_array['staff']); $count++){
        //週間時間と日数のリセット
        $time_url = "../data/time/time" . $count . ".json";
        $json = file_get_contents($time_url);
        $time = json_decode($json, true);

        $time['time'][0]['weekly_hours'] = 0;
        $time['time'][0]['weekly_days'] = 0;

        //合計時間のjsonファイルへの書き出し
        $fjson = fopen($time_url, "w+b");
        fwrite($fjson, json_encode($time, JSON_UNESCAPED_UNICODE));
        fclose($fjson);
    }*/
?>
</body>
</html>
