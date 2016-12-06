<?php
$ID = $_GET['ID'];
if ($_GET['action'] == 'normal'){  //日付選択画面から来た場合
    $day_array = $_GET['day'];

    $year  = strstr($day_array, "/", TRUE);
    $month = strstr(substr(strstr($day_array, "/"), 1), "/", TRUE);
    $day = strstr(substr(strstr(substr(strstr($day_array, "/"), 1), "/"), 1), "～", TRUE);
    $first_day = $day;
}
echo "<form>";
