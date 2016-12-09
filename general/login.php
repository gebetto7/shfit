<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
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

echo "<form action = 'login_dec.php' method = 'get' class = 'form-inline'>";
echo "<div class = 'form-group'><label for = 'InputNumber' class='sr-only'>従業員No</label>";
echo "<input type = 'number' class = 'form-control' id = 'InputNumber' placeholder='従業員No' name='ID' min = '1'></div>";
echo "<button type = 'submit' class=\"btn btn-success\">送信</button>";
echo "</form>";
?>
</body>
</html>