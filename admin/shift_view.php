<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>シフト表閲覧</title>
</head>
<body>
<?php
    //シフト表を最終作成日の取得
    $last_url = "../data/shift/last.json";
    $json = file_get_contents($last_url);
    $last = json_decode($json, true);

    /*JSONデータ(スタッフ情報)の読み込み*/
    $staff_url = "../data/management/staff.json";
    $json = file_get_contents($staff_url);
    $staff_array = json_decode($json,true);

    for ($count = $last['day'] + 1; $count <= $last['day'] + 7; $count++) {
        /*日付の表示*/
        echo $last["year"] . "年" . $last["month"] . "月" . $count . "日<br>";
        /*JSONデータ(シフト情報)の読み込み*/
        $shift_url = "../data/shift/" . $last["year"] . $last["month"] . $count . "shift.json";
        $json = file_get_contents($shift_url);
        $shift_array = json_decode($json, true);

        /*時間の表示(表)*/
        echo '<table border="1" cellpadding="2"><tr><td></td>';
        for ($a = 0; $a <= 23; $a++){
            echo '<td>'. $a .'</td>';
        }
        echo '</tr>';

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
            echo '</tr>';
            //ここまで
        }
        echo '</table><br>';
    }
?>
</table>
</body>
</html>