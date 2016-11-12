<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>シフト表作成</title>
</head>
<body>
<table border="1">
    <?php
        include 'shift_swap.php';   //シフト表整形
        include 'time_calculation.php';   //勤務合計時間算出
        include 'shift_view_func.php';      //シフト閲覧

        /*JSONデータ(スタッフ情報)の読み込み*/
        $staff_url = "../data/management/staff.json";

        $json = file_get_contents($staff_url);
        $staff_array = json_decode($json,true);

        /*JSONデータ(シフト情報)の読み込み*/
        $shift_url = "../data/shift/2016102shift.json";
        swap($shift_url);
        $json = file_get_contents($shift_url);
        $shift_array = json_decode($json,true);

        //シフト表を最終作成日の取得
        $last_url = "../data/shift/last.json";
        $json = file_get_contents($last_url);
        $last = json_decode($json, true);

        /*日付の表示*/
        for ($count = $last['day'] + 1; $count <= $last['day'] + 7; $count++){
            shift_view($last['year'], $last['month'], $count);
            time_calculation($last['day'] + 1);
        }
    ?>
</table>
</body>
</html>
