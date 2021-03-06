<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>シフト表提出-日時選択-</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>シフト表提出 <small>日時選択</small></h1>
    </div>
<?php
include '../admin/check_date.php';
$ID = $_GET['ID'];
if ($_GET['action'] == 'normal'){  //日付選択画面から来た場合
    $day_array = $_GET['day'];

    $year  = strstr($day_array, "/", TRUE);
    $month = strstr(substr(strstr($day_array, "/"), 1), "/", TRUE);
    $day = strstr(substr(strstr(substr(strstr($day_array, "/"), 1), "/"), 1), "～", TRUE);
    $first_day = $day;
}

/*JSONデータ(スタッフ情報)の読み込み*/
$staff_url = "../data/management/staff.json";
$json = file_get_contents($staff_url);
$staff_array = json_decode($json, true);

//時間帯データの取得
$time_zone_url = "../data/management/time_zone.json";
$json = file_get_contents($time_zone_url);
$time_zone_array = json_decode($json, true);

echo "<form action = 'shift_allocation.php' method = 'get'>";
for ($count = 0; $count <= 6; $count++) {   //一週間分
    echo $year . "年" . $month . "月" . $day . "日<br>";
    for ($i = 0;$i < sizeof($time_zone_array['time_zone']); $i++){
        $t = "t" . $count . "_" . $i;
        $time_zone_name = $time_zone_array['time_zone'][$i]['name'];
        echo "<label class=\"checkbox-inline\"><input type = 'checkbox' name = '$t' value = '1'>" . $time_zone_name . "</label>";
    }
    //日付の更新
    $day++;
    $day_array = check_date($year, $month, $day);
    $year = $day_array['year'];
    $month = $day_array['month'];
    $day = $day_array['day'];
    echo "<br><br>";
}
echo "<div class=\"form-group\">";
echo "<input type = 'hidden' name = 'ID' value = '$ID'>";
echo "<input type = 'hidden' name = 'year' value = '$year'>";
echo "<input type = 'hidden' name = 'month' value = '$month'>";
echo "<input type = 'hidden' name = 'day' value = '$first_day'>";
echo "<button type = 'submit' class=\"btn btn-success\">確定</button>";
echo "</div></form>";

echo "<form action = 'submission_selectday.php' method = 'get'>";
echo "<input type = 'hidden' name = 'ID' value = '$ID'>";
echo "<button type = 'submit' class=\"btn btn-warning\">戻る</button>
             </form>";
?>
</div>
</body>
</html>