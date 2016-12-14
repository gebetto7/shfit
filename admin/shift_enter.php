<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>シフト表作成</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
include 'check_date.php';
if (isset($_GET['action'])){
    if ($_GET['action'] == 'enter'){    //確定

        //------------title------------------------
        echo "<div class=\"container\">";
        echo "<div class=\"page-header\">";
        echo "<h1>シフト表作成 <small>完了</small></h1>";
        echo "</div>";
        //-----------------------------------------
        echo "<div class=\"alert alert-success\" role=\"alert\"><strong>success</strong>: シフト表を保存しました．</div>";
        echo "<form action = 'shift_create_selectday.php'>";

        $year = $_GET['year'];
        $month = $_GET['month'];
        $day = $_GET['day'];

        // シフト情報の読み込み
        for ($count = 0; $count <= 6; $count++){
            $shift_url = "../data/shift/temp/" . $year . $month . $day . ".json";
            $json = file_get_contents($shift_url);
            $shift_array = json_decode($json, true);

            $fjson = fopen("../data/shift/main/" . $year . $month . $day . ".json", "w+b");
            fwrite($fjson, json_encode($shift_array, JSON_UNESCAPED_UNICODE));
            fclose($fjson);

            $day++;
            //日付の更新
            $day_array = check_date($year, $month, $day);
            $year = $day_array['year'];
            $month = $day_array['month'];
            $day = $day_array['day'];
        }
        //tempフォルダの初期化
        $path = '../data/shift/temp';
        $res_dir = opendir( $path );
        while ( $file_name = readdir( $res_dir ) ){
            if (is_file($path. "/" .$file_name)) {
                unlink($path . "/" . $file_name);
            }
        }
        //temp/candidateフォルダの初期化
        $path = '../data/shift/temp/candidate';
        $res_dir = opendir( $path );
        while ( $file_name = readdir( $res_dir ) ){
            if (is_file($path. "/" .$file_name)) {
                unlink($path . "/" . $file_name);
            }
        }
        echo "<button type = 'submit' class=\"btn btn-warning\">戻る</button>";
        echo "</form>";
    }
//=============================================================================================
    else if($_GET['action'] == 'modify'){   //修正

        $date = $_GET['date'];
        $year = $_GET['year'];
        $month = $_GET['month'];
        $day = $_GET['day'];
        $first_day = $day;

        //------------title------------------------
        echo "<div class=\"container\">";
        echo "<div class=\"page-header\">";
        echo "<h1>シフト表修正 <small>$date</small></h1>";
        echo "</div>";
        //-----------------------------------------.

        /*JSONデータ(スタッフ情報)の読み込み*/
        $staff_url = "../data/management/staff.json";
        $json = file_get_contents($staff_url);
        $staff_array = json_decode($json, true);

        //時間帯情報の読み込み
        $time_zone_url = "../data/management/time_zone.json";
        $json = file_get_contents($time_zone_url);
        $time_zone_array = json_decode($json, true);

        echo "<form class=\"form-inline\" action = 'shift_modify_enter.php' method = 'get'>";

        for ($count = 0; $count <= 6; $count++) {

            $candidate_url = "../data/shift/temp/candidate/" . $year . $month . $day . ".json";
            $json = file_get_contents($candidate_url);
            $candidate_array = json_decode($json,true);

            $shift_url = "../data/shift/temp/" . $year . $month . $day . ".json";
            $json = file_get_contents($shift_url);
            $shift_array = json_decode($json, true);

            echo "<p class='lead'>" . $year . "年" . $month . "月" . $day . "日</p>";
            $count2 = 0;
            $time_zone_count = 0;
            $numberp = 0;

            for ($time_zone_count = 0; $time_zone_count < sizeof($time_zone_array['time_zone']); $time_zone_count++){

                echo "<p class='lead'>" . $time_zone_array['time_zone'][$time_zone_count]['name'] . "</p>";

                /*時間の表示(表)*/
                echo '<table class="table table-bordered table-hover"><thead><tr><td></td>';
                for ($a = 0; $a <= 23; $a++) {
                    echo '<td>' . $a . '</td>';
                }
                echo '</tr></thead>';

                while (($count2 < sizeof($shift_array['shift'])) &&
                    ($shift_array['shift'][$count2]['min'] == $time_zone_array['time_zone'][$time_zone_count]['min']) &&
                    ($shift_array['shift'][$count2]['max'] == $time_zone_array['time_zone'][$time_zone_count]['max'])) {

                    echo '<tr>';
                    $number = $shift_array["shift"][$count2]["number"];
                    echo '<td>' . $staff_array['staff'][$number]['name'] . '</td>';

                    for ($time_count = 0; $time_count <= 23; $time_count++) {
                        if ($shift_array['shift'][$count2]['min'] <= $time_count && $shift_array['shift'][$count2]['max'] > $time_count) {
                            echo "<td>●</td>";
                        }
                        else {
                            echo "<td>　</td>";
                        }
                    }
                    echo '</tr>';
                    $numberp++;
                    $count2++;
                }
                echo '</table><br>';

                //現在シフトに入ってる人を選択肢に入れるための処理
                $numberp = $count2 - $numberp;
                $candidate1_key = $shift_array['shift'][$numberp]['number'];
                $candidate1 = $staff_array['staff'][$candidate1_key]['name'];
                $numberp++;
                $candidate2_key = $shift_array['shift'][$numberp]['number'];
                $candidate2 = $staff_array['staff'][$candidate2_key]['name'];

                $time_zone_now = $time_zone_array['time_zone'][$time_zone_count]['name'];

                //候補者シフトの表示

                echo "候補者1";
                $submit_name1 = "change1_" . $count . "_" . $time_zone_count;
                echo "<select class=\"form-control\" name = '$submit_name1'>";
                echo "<option value = '$candidate1'>$candidate1</option>";
                for ($x = 0; $x < sizeof($candidate_array[$time_zone_now]); $x++){
                    $candidate_name_key = $candidate_array[$time_zone_now][$x]['number'];
                    $candidate_name = $staff_array['staff'][$candidate_name_key]['name'];
                    echo "<option name = '$candidate_name'>$candidate_name</option>";
                }
                echo "</select>";
                echo "候補者2";
                $submit_name2 = "change2_" . $count . "_" . $time_zone_count;
                echo "<select class=\"form-control\" name = '$submit_name2'>";
                echo "<option value = '$candidate2'>$candidate2</option>";
                for ($x = 0; $x < sizeof($candidate_array[$time_zone_now]); $x++){
                    $candidate_name_key = $candidate_array[$time_zone_now][$x]['number'];
                    $candidate_name = $staff_array['staff'][$candidate_name_key]['name'];
                    echo "<option name = '$candidate_name'>$candidate_name</option>";
                }
                echo "</select><br><br>";
                $numberp = 0;
            }
            $day++;
            //日付の更新
            $day_array = check_date($year, $month, $day);
            $year = $day_array['year'];
            $month = $day_array['month'];
            $day = $day_array['day'];
            echo "<br><br>";
        }
        echo "<div class='form-group'>";
        echo "<input type = 'hidden' name = 'date' value = '$date'>";
        echo "<input type = 'hidden' name = 'year' value = '$year'>";
        echo "<input type = 'hidden' name = 'month' value = '$month'>";
        echo "<input type = 'hidden' name = 'day' value = '$first_day'>";
        echo "<button type = 'submit' class=\"btn btn-success\" name = 'action' value = 'enter'>確定</button>";
        echo "</div></form>";

        echo "<form action = 'shift_create.php' method = 'get'>";
        echo "<div class='form-group'>";
        echo "<input type = 'hidden' name = 'date' value = '$date'>";
        echo "<input type = 'hidden' name = 'year' value = '$year'>";
        echo "<input type = 'hidden' name = 'month' value = '$month'>";
        echo "<input type = 'hidden' name = 'day' value = '$first_day'>";
        echo "<input type = 'hidden' name = 'modify' value = 'false'>";
        echo "<br><button type = 'submit' class=\"btn btn-warning\" name = 'action' value = 'back'>戻る</button>";
        echo "</div></form>";
    }
    else{
        echo "error<br>";
    }
}
else{
    echo "error<br>";
}
?>
</div>
</body>
</html>