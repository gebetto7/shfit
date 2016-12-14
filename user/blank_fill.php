<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>欠員補充-日・時間帯選択-</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>欠員補充 <small>時間帯選択</small></h1>
    </div>
<?php
include '../admin/check_date.php';
$ID = $_GET['ID'];
if (isset($_GET['day'])){  //日付選択画面から来た場合
    $day_array = $_GET['day'];

    $year  = strstr($day_array, "/", TRUE);
    $month = strstr(substr(strstr($day_array, "/"), 1), "/", TRUE);
    $day = strstr(substr(strstr(substr(strstr($day_array, "/"), 1), "/"), 1), "～", TRUE);
    $first_day = $day;
}

echo "<form action = 'fill_enter.php' method = 'get'>";
for ($x = 0; $x <= 6; $x++){    //一週間分
    $date = $year . $month . $day;
    $url = "../data/shift/blank/" . $date . ".json";
    if (file_exists($url)) {
        echo "<div class=\"form-group\">";
        echo $year . "年" . $month . "月" . $day . "日<br>";
        //欠員一覧の取得
        $json = file_get_contents($url);
        $blank_array = json_decode($json, true);

        for ($y = 0; $y < sizeof($blank_array[$date]); ) {          //欠員がある時間帯
            $checkbox_name = $year . "/" . $month . "/" . $day . "_" . $blank_array[$date][$y];
            echo "<label class=\"checkbox-inline\"><input type = 'checkbox' name = '$checkbox_name' value = '1'>" . $blank_array[$date][$y] . "</label>";
            if ((isset($blank_array[$date][$y + 1]))
                && ($blank_array[$date][$y] == $blank_array[$date][$y + 1]))    //issetがないと最後の判断のときに存在しない添字を参照してしまうため
                $y += 2;
            else
                $y++;
        }
        echo "<br>";
    }
    else
        echo "<p>" . $year . "年" . $month . "月" . $day . "日<br>欠員はありませんでした。</p>";
    echo "</div>";
    //日付の更新
    $day++;
    $day_array = check_date($year, $month, $day);
    $year = $day_array['year'];
    $month = $day_array['month'];
    $day = $day_array['day'];
}
echo "<div class=\"form-group\">";
echo "<input type = 'hidden' name = 'ID' value = '$ID'>";
echo "<input type = 'hidden' name = 'year' value = '$year'>";
echo "<input type = 'hidden' name = 'month' value = '$month'>";
echo "<input type = 'hidden' name = 'day' value = '$first_day'>";
echo "<input type = 'submit' class=\"btn btn-success\"></form>";
echo "</div>";

echo "<form action = 'blank_selectday.php' method = 'get'>";
echo "<input type = 'hidden' name = 'ID' value = '$ID'>";
echo "<button type = 'submit' class=\"btn btn-warning\">戻る</button>
             </form>";
?>
    </div>
</body>
</html>
