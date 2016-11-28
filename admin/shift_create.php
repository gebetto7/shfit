<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>シフト表作成</title>
</head>
<body>
    <?php
        if (isset($_GET['day'])){
            $day_array = $_GET['day'];

            $year  = strstr($day_array, "/", TRUE);
            $month = strstr(substr(strstr($day_array, "/"), 1), "/", TRUE);
            $day = strstr(substr(strstr(substr(strstr($day_array, "/"), 1), "/"), 1), "～", TRUE);
        }
        else{
            echo "error<br>";
        }
        //tempフォルダの初期化
        $path = '../data/shift/temp';
        $res_dir = opendir( $path );
        while ( $file_name = readdir( $res_dir ) ){
            if (is_file($path. "/" .$file_name)) {
                unlink($path . "/" . $file_name);
            }
        }
        include 'shift_swap.php';
        include 'time_calculation.php';   //勤務合計時間算出
        include 'shift_view.php';  //シフト閲覧
        include 'mastery_check.php';
        include 'matching.php';

        /*JSONデータ(スタッフ情報)の読み込み*/
        $staff_url = "../data/management/staff.json";
        $json = file_get_contents($staff_url);
        $staff_array = json_decode($json,true);

        for ($count = 0; $count <= 6; $count++){
            $message = matching($staff_array, $year, $month, $day);    //希望表からシフト表を作成
            mastery_check("temp", $year, $month, $day);
            shift_view("temp", $year, $month, $day);
            time_calculation("temp", $year, $month, $day);
            $day++;
            echo $message . "<br>";
        }
        $day -= 7;  //日情報を戻す
        for ($count = 0; $count < sizeof($staff_array['staff']); $count++){
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
        echo "<input type = 'hidden' name = 'day' value = '$day'>";
        //候補者情報

        echo "<button type = 'submit' name = 'action' value = 'enter'>確定</button>";
        echo "<button type = 'submit' name = 'action' value = 'modify'>修正</button>
                </form><br><br>";
        echo "<form action = 'shift_create_selectday.php'>";
        echo "<button type = 'submit'>戻る</button>
                </form>";
    ?>
</body>
</html>
