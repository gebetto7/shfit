<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>シフト表閲覧</title>
</head>
<body>
<?php
    include 'shift_view_func.php';   //
    include 'shift_swap.php';

    $last_url = "../data/shift/last.json";
    $json = file_get_contents($last_url);
    $last = json_decode($json, true);
    $last['day']++;

    for ($count = 0; $count <= 6; $count++){
        shift_view($last['year'], $last['month'], $last['day']);
        $last['day']++;
    }
?>
</table>
</body>
</html>