<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>管理者用トップページ</title>
</head>
<body>
<br>
<button type="button" onclick="location.href='shift_create_selectday.php'">シフト表作成</button></br></br>
<button type="button" onclick="location.href='../general/view_selectday.php'">シフト表確認</button></br></br>
<?php
    echo "作業用";
?>
<button type="button" onclick="location.href='time_reset.php'">勤務時間リセット</button></br></br>
<button type = 'button' onclick = "location.href = '../general/login.php'">ログアウト</button>
</body>
</html>