<?php
$ID = $_GET['ID'];
include '../general/shift_view.php';
include '../admin/shift_swap.php';
include '../admin/check_date.php';

$day_array = $_GET['day'];

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
echo "<button type = 'submit'>戻る</button>
             </form>";