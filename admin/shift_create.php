<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>シフト表作成-確認-</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
    include 'shift_swap.php';
    include 'time_calculation.php';   //勤務合計時間算出
    include '../general/shift_view.php';  //シフト閲覧
    include 'mastery_check.php';
    include 'matching.php';
    include 'check_date.php';

    /*JSONデータ(スタッフ情報)の読み込み*/
    $staff_url = "../data/management/staff.json";
    $json = file_get_contents($staff_url);
    $staff_array = json_decode($json, true);

    if ($_GET['action'] == 'back'){     //修正画面から戻ってきた場合
        $year = $_GET['year'];
        $month = $_GET['month'];
        $day = $_GET['day'];
        $first_day = $day;
    } 
    else if ($_GET['action'] == 'normal'){  //日付選択画面から来た場合
        $day_array = $_GET['day'];

        $year  = strstr($day_array, "/", TRUE);
        $month = strstr(substr(strstr($day_array, "/"), 1), "/", TRUE);
        $day = strstr(substr(strstr(substr(strstr($day_array, "/"), 1), "/"), 1), "～", TRUE);
        $first_day = $day;
    }
    if ($_GET['modify'] == 'true'){     //修正したデータを確定した場合
        for ($count = 0; $count <= 6; $count++) {

            mastery_check("temp/modify", $year, $month, $day);
            shift_view("temp/modify", $year, $month, $day);
            time_calculation("temp/modify", $year, $month, $day);

            $day++;
            //日付の更新
            $day_array = check_date($year, $month, $day);
            $year = $day_array['year'];
            $month = $day_array['month'];
            $day = $day_array['day'];

        }
    }
    else {
        //tempフォルダの初期化
        $path = '../data/shift/temp';
        $res_dir = opendir($path);
        while ($file_name = readdir($res_dir)) {
            if (is_file($path . "/" . $file_name)) {
                unlink($path . "/" . $file_name);
            }
        }
        $path = '../data/shift/blank';
        $res_dir = opendir($path);
        while ($file_name = readdir($res_dir)) {
            if (is_file($path . "/" . $file_name)) {
                unlink($path . "/" . $file_name);
            }
        }

        for ($count = 0; $count <= 6; $count++) {

            $message = matching($staff_array, $year, $month, $day);    //希望表からシフト表を作成
            mastery_check("temp", $year, $month, $day);
            shift_view("temp", $year, $month, $day);
            time_calculation("temp", $year, $month, $day);

            $day++;
            //日付の更新
            $day_array = check_date($year, $month, $day);
            $year = $day_array['year'];
            $month = $day_array['month'];
            $day = $day_array['day'];

            echo $message . "<br>";
        }
    }
    for ($count = 0; $count < sizeof($staff_array['staff']); $count++) {
            //週間時間と日数のリセット
        $time_url = "../data/time/time" . $count . ".json";
        $json = file_get_contents($time_url);
        $time = json_decode($json, true);

        $time['time'][0]['weekly_hours'] = 0;
        $time['time'][0]['weekly_days'] = 0;

        //合計時間のjsonファイルへの書き出し
        $fjson = fopen($time_url, "w+b");
        fwrite($fjson, json_encode($time, JSON_UNESCAPED_UNICODE));
        fclose($fjson);
    }
    echo "以上の内容でよろしいですか？<br>";
    echo "<form action = 'shift_enter.php' method = 'get'>";
    //hidden属性
    //日付情報
    echo "<input type = 'hidden' name = 'year' value = '$year'>";
    echo "<input type = 'hidden' name = 'month' value = '$month'>";
    echo "<input type = 'hidden' name = 'day' value = '$first_day'>";
    echo "<div class='form-group'>";
    echo "<button type = 'submit' class=\"btn btn-default\" name = 'action' value = 'enter'>確定</button>";
    echo "<button type = 'submit' class=\"btn btn-default\" name = 'action' value = 'modify'>修正</button>";
    echo "</div></form>";

    echo "<form action = 'shift_create_selectday.php'>";
    echo "<button type = 'submit' class=\"btn btn-warning\">戻る</button>
             </form>";
    ?>
</body>
</html>
