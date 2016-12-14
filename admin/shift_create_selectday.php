<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>シフト表作成-週選択-</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>シフト表作成 <small>週選択</small></h1>
    </div>
<?php
    include 'shift_swap.php';
    include 'time_calculation.php';   //勤務合計時間算出
    include '../general/shift_view.php';  //シフト閲覧
    include 'mastery_check.php';
    include 'matching.php';
    include 'check_date.php';

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
    echo "<input type = 'hidden' name = 'action' value = 'normal'>";
    echo "<input type = 'hidden' name = 'modify' value = 'false'>";
    echo "<div class='form-group'>";
    echo "<input type = 'submit' class=\"btn btn-default\" name = 'day' value = '$day1'>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<input type = 'submit' class=\"btn btn-default\" name = 'day' value = '$day2'>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<input type = 'submit' class=\"btn btn-default\" name = 'day' value = '$day3'>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<input type = 'submit' class=\"btn btn-default\" name = 'day' value = '$day4'>";
    echo "</div>";
    echo "</form>";

    echo "<form action = 'admin_index.php'>";
    echo "<div class='form-group'>";
    echo "<button type = 'submit' class=\"btn btn-warning\">戻る</button>";
    echo "</div></form>";
?>
    </div>
</body>
</html>
