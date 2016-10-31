<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>管理者用トップページ</title>
</head>
<br>
<button type="button" onclick="location.href='shift_create.php'">シフト表作成</button></br></br>
<button type="button" onclick="location.href='staff.html'">従業員管理</button></br></br>
<button type="button" onclick="location.href='ketuin.html'">欠員補充</button></br></br>
<button type="button" onclick="location.href='shift_view.php'">シフト表確認</button></br></br>
<button type="button" onclick="location.href='time_setting.php'">時間帯設定</button></br></br>
<?php
    echo "作業用";
?>
<button type="button" onclick="location.href='salary_reset.php'">勤務時間リセット</button></br></br>
</body>
</html>