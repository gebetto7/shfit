<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <link rel="stylesheet" type="text/css" href="../css/shift.css">
    <title>シフト表作成</title>
</head>
<body>
<table border="1">
    <?php
    include 'shift_swap.php';   //シフト表整形
    include 'salary_calculation.php';   //勤務合計時間算出

    /*JSONデータ(スタッフ情報)の読み込み*/
    $staff_url = "../data/management/staff.json";
    $json = file_get_contents($staff_url);
    $staff_array = json_decode($json,true);
    
    /*JSONデータ(シフト情報)の読み込み*/
    $shift_url = "../data/shift/2016102shift.json";
    swap($shift_url);   //
    $json = file_get_contents($shift_url);
    $shift_array = json_decode($json,true);

    //シフト表を最終作成日の取得
    $last_url = "../data/shift/last.json";
    $json = file_get_contents($last_url);
    $last = json_decode($json, true);

    /*日付の表示*/

    /*時間の表示(表)*/
    echo '<tr><td></td>';
    for ($a = 0; $a <= 23; $a++){
        echo '<td>'. $a .'</td>';
    }
    echo '</tr>';


    $year = date('Y');
    $month = date('n');

    /*シフト表の表示*/
    for($shift_count = 0; $shift_count < sizeof($shift_array['shift']); $shift_count++) {
        //シフト表1列表示部分
        //ここから
        echo '<tr>';
        /*従業員名の格納*/
        $number = $shift_array["shift"][$shift_count]["number"];
        /*従業員名の表示*/
        echo '<div><td>' . $staff_array['staff'][$number]['name'] . '</td></div>';

        /*時間表表示*/
        for ($time_count = 0; $time_count <= 23; $time_count++) {
            if ($shift_array['shift'][$shift_count]['min'] <= $time_count && $shift_array['shift'][$shift_count]['max'] > $time_count) {
                echo "<td>●</td>";
            } else {
                echo "<td>　</td>";
            }
        }
        echo "</tr>";
        //ここまで
    }
    calculation($last['day'] + 1);
    ?>
</table>
</body>
</html>
