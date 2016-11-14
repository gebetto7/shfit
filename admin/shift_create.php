<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>シフト表作成</title>
</head>
<body>
<table border="1">
    <?php
        include 'shift_swap.php';
        include 'time_calculation.php';   //勤務合計時間算出
        include 'shift_view_func.php';//シフト閲覧
        include 'mastery_check.php';
        include 'time_check.php';
        include 'check.php';

        //シフト表を最終作成日の取得
        $last_url = "../data/shift/last.json";
        $json = file_get_contents($last_url);
        $last = json_decode($json, true);
        $last['day']++;

        for ($count = 0; $count <= 6; $count++){
            //日にちの妥当性の判断については、別途関数を用意し、そこで判断して正しかった場合通常動作、正しくなかった場合は月及び年の繰り上げを行う
            //そのための関数を用意する
            shift_view($last['year'], $last['month'], $last['day']);
            time_calculation($last['year'], $last['month'], $last['day']);
            check($last['year'], $last['month'], $last['day']);
            $last['day']++;
        }
    ?>
</table>
</body>
</html>
