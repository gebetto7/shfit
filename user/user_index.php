<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>従業員トップページ</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
$ID = $_GET['ID'];
echo "<div class=\"container\">";
echo "<div class=\"page-header\">";
echo "<h1>トップページ <small>スタッフ用</small></h1>";
echo "</div>";
echo "<div class='form-group'>";
echo "<form action = 'submission_selectday.php' method = 'get'>";
echo "<input type = 'hidden' name = 'ID' value = '$ID'>";
echo "<button type = 'submit' class=\"btn btn-default\">シフト表提出</button>";
echo "</form>";
echo "</div>";

echo "<div class='form-group'>";
echo "<form action = '../general/view_selectday.php' method = 'get'>";
echo "<input type = 'hidden' name = 'ID' value = '$ID'>";
echo "<button type = 'submit' class=\"btn btn-default\">シフト表閲覧</button>";
echo "</form>";
echo "</div>";

echo "<div class='form-group'>";
echo "<form action = 'blank_selectday.php' method = 'get'>";
echo "<input type = 'hidden' name = 'ID' value = '$ID'>";
echo "<button type = 'submit' class=\"btn btn-default\">欠員一覧</button>";
echo "</form>";
echo "</div>";

echo "<div class='form-group'>";
echo "<form action = '../general/login.php'>";
echo "<button type = 'submit' class=\"btn btn-danger\">ログアウト</button></form>";
echo "</div>";

echo "</div>";
echo "</div>";
?>
</body>
</html>