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

    /*JSONデータ(スタッフ情報)の読み込み*/
    $staff_url = "../data/staff.json";
    $json = file_get_contents($staff_url);
    $staff_array = json_decode($json,true);
    
    /*JSONデータ(シフト情報)の読み込み*/
    $shift_url = "../data/shift/shift.json";
    $json = file_get_contents($shift_url);
    $shift_array = json_decode($json,true);

    /*JSONデータ(勤務時間情報)の読み込み*/
    $working_url = "../data/working.json";
    $json = file_get_contents($working_url);
    $working_array = json_decode($json,true);

    /*時間の表示(表)*/
    echo '<tr><td></td>';
    for ($a = 0; $a <= 23; $a++){
        echo '<td>'. $a .'</td>';
    }
    echo '</tr>';

    
    $year = date('Y');
    $month = date('n');

    /*シフト表の表示*/
    for($staff_count = 0; $staff_count < sizeof($staff_array['staff']); $staff_count++) {
        echo '<tr>';
        /*従業員名の表示*/
        echo '<div><td>' . $staff_array['staff'][$staff_count]['name'] . '</td></div>';

        /*時間表表示*/
        for ($time_count = 0; $time_count <= 23; $time_count++) {
            if ($shift_array['shift'][$staff_count]['min'] <= $time_count && $shift_array['shift'][$staff_count]['max'] > $time_count) {
                echo "<td>●</td>";
            } else {
                echo "<td>　</td>";
            }
        }
        echo '</tr>';
    }
    ?>
</table>
</body>
</html>
