<html>
<head>
</head>
<body>
<?php
    $ID = $_GET['ID'];
    echo "<form action = 'submission_selectday.php' method = 'get'>";
    echo "<input type = 'hidden' name = 'ID' value = '$ID'>";
    echo "<button type = 'submit'>シフト表提出</button>";
    echo "</form>";

    echo "<form action = '../admin/shift_view.php' method = 'get'>";
    echo "<input type = 'hidden' name = 'ID' value = '$ID'>";
    echo "<button type = 'submit'>シフト表閲覧</button>";
    echo "</form>";
?>
</body>
</html>