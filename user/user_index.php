<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>従業員トップページ</title>
</head>
<body>
<?php
$ID = $_GET['ID'];
echo "<form action = 'submission_selectday.php' method = 'get'>";
echo "<input type = 'hidden' name = 'ID' value = '$ID'>";
echo "<button type = 'submit'>シフト表提出</button>";
echo "</form><br>";

echo "<form action = '../general/view_selectday.php' method = 'get'>";
echo "<input type = 'hidden' name = 'ID' value = '$ID'>";
echo "<button type = 'submit'>シフト表閲覧</button>";
echo "</form><br>";

echo "<form action = '../general/login.php'>";
echo "<button type = 'submit'>ログアウト</button>
                 </form>";
?>
</body>
</html>