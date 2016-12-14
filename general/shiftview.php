<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>シフト表確認</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
$ID = $_GET['ID'];
include '../general/shift_view.php';
include '../admin/shift_swap.php';
include '../admin/check_date.php';

$day_array = $_GET['day'];

echo "<div class=\"container\">";
echo "<div class=\"page-header\">";
echo "<h1>シフト表閲覧 <small>$day_array</small></h1>";
echo "</div>";

$year  = strstr($day_array, "/", TRUE);
$month = strstr(substr(strstr($day_array, "/"), 1), "/", TRUE);
$day = strstr(substr(strstr(substr(strstr($day_array, "/"), 1), "/"), 1), "～", TRUE);
$first_day = $day;

for ($x = 0; $x < 7; $x++){
    shift_view("main", $year, $month, $day);

    //日付の更新
    $day++;
    $day_array = check_date($year, $month, $day);
    $year = $day_array['year'];
    $month = $day_array['month'];
    $day = $day_array['day'];
}

echo "<form action = 'view_selectday.php'>";
echo "<input type = 'hidden' name = 'ID' value = '$ID'>";
echo "<button type = 'submit' class=\"btn btn-warning\">戻る</button>
             </form>";
?>
    </div>
</body>
</html>
