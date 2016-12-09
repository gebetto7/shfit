<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php

echo "<div class=\"container\">";
if (isset($_GET['error'])){
    switch ($_GET['error']){
        case 1:
            echo "<div class=\"alert alert-warning\" role=\"alert\"><strong>error</strong>: 存在しない従業員IDです。</div>";
            break;
        case 2:
            echo "<div class=\"alert alert-warning\" role=\"alert\"><strong>error</strong>: 従業員IDが入力されていません。</div>";
            break;
    }
}

echo "<form action = 'login_dec.php' method = 'get'>";
echo "<div class = 'form-group'>";
echo "<input type = 'number' class = 'form-control' id = 'InputNumber' placeholder='従業員No' name='ID' min = '1'>
    </div>";
echo "<button type = 'submit' class=\"btn btn-success center-block\">送信</button>";
echo "</form>";

echo "</div>";
?>
</body>
</html>