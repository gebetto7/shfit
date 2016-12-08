<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
</head>
<body>
<?php
if (isset($_GET['error'])){
    switch ($_GET['error']){
        case 1:
            echo "存在しない従業員IDです。<br>";
            break;
        case 2:
            echo "従業員IDが入力されていませんでした。<br>";
            break;
    }
}

echo "従業員IDを入力してください。<br>";
echo "<form action = 'login_dec.php' method = 'get'>";
echo "<input type = 'number' name = 'ID' min = '1' max = '999'><br><br>";
echo "<input type = 'submit' value = '送信'>";
echo "</form>";
?>
</body>
</html>