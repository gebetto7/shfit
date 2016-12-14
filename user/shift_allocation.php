<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>シフト表提出-確認-</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>シフト表提出 <small>確認</small></h1>
    </div>
<?php
include '../admin/check_date.php';
include '../admin/shift_swap.php';
$ID = $_GET['ID'];
$year = $_GET['year'];
$month = $_GET['month'];
$day = $_GET['day'];
$first_day = $day;

//時間帯データの取得
$time_zone_url = "../data/management/time_zone.json";
$json = file_get_contents($time_zone_url);
$time_zone_array = json_decode($json, true);

$array['shift'] = Array();//振り分け用の配列

$msg = "";          //ここのメッセージをerrorかsuccessに振り分ける
$error_msg = "<br>下記の内容は既に希望を提出しています。<br>";
$success_msg = "<br>下記の内容で出勤希望を提出しました。<br>";

for ($x = 0; $x <= 6; $x++){
    for ($y = 0; $y < sizeof($time_zone_array['time_zone']); $y++){
        $key = "t" . $x . "_" . $y;
        if (isset($_GET[$key])){        //希望があった場合
            $push_data = Array(
                "number" => intval($ID),
                "min" => $time_zone_array['time_zone'][$y]['min'],
                "max" => $time_zone_array['time_zone'][$y]['max']
            );
            array_push($array['shift'], $push_data);
        }
    }

    $url = "../data/shift/original/" . $year . $month . $day . ".json";
    if (!file_exists($url)){    //ファイルが存在しなかった場合作成する
        file_put_contents($url, Array(
            "shift" => Array()
        ));
    }
    //そのデータを配列で読み込み配列を更新してから保存する
    $json = file_get_contents($url);
    $shift_array = json_decode($json, true);
    $overlapf = 0;
    for ($count =0; $count < sizeof($array['shift']); $count++){//出勤希望の数だけ
        for ($count2 = 0; $count2 < sizeof($shift_array['shift']); $count2++) {    //既に希望が出てる人数分だけ
            if (($shift_array['shift'][$count2]['number'] == $array['shift'][$count]['number'])
                && ($shift_array['shift'][$count2]['min'] == $array['shift'][$count]['min'])
                && ($shift_array['shift'][$count2]['max'] == $array['shift'][$count]['max'])){ //既に提出があるものは省く
                for ($time_zone_count = 0; $time_zone_count < sizeof($time_zone_array['time_zone']); $time_zone_count++){
                    if ($array['shift'][$count]['min'] == $time_zone_array['time_zone'][$time_zone_count]['min']){
                        $error_msg .= $year . "年" . $month . "月" . $day . "日: " . $time_zone_array['time_zone'][$time_zone_count]['name'] . "<br>";
                    }
                }
                $overlapf = 1;
                break;
            }
        }
        if (!$overlapf){    //重複がなければ配列に追記
            if ($shift_array['shift'] == null)
                $shift_array['shift'][0] = $array['shift'][$count];
            else
                array_push($shift_array['shift'], $array['shift'][$count]);
            for ($time_zone_count = 0; $time_zone_count < sizeof($time_zone_array['time_zone']); $time_zone_count++){
                if ($array['shift'][$count]['min'] == $time_zone_array['time_zone'][$time_zone_count]['min']){
                    $success_msg .= $year . "年" . $month . "月" . $day . "日: " . $time_zone_array['time_zone'][$time_zone_count]['name'] . "<br>";
                }
            }
        }
    }

    //配列のファイルへの保存
    $fjson = fopen($url, "w+b");
    fwrite($fjson, json_encode($shift_array, JSON_UNESCAPED_UNICODE));
    fclose($fjson);
    swap($url);

    $array['shift'] = Array(); //初期化
    //日付の更新
    $day++;
    $day_array = check_date($year, $month, $day);
    $year = $day_array['year'];
    $month = $day_array['month'];
    $day = $day_array['day'];

}
if ($success_msg != "<br>下記の内容で出勤希望を提出しました。<br>"){
    echo "<div class=\"alert alert-success\" role=\"alert\"><strong>success</strong>：　" . $success_msg . "</div>";
}
if ($error_msg != "<br>下記の内容は既に希望を提出しています。<br>"){
    echo "<div class=\"alert alert-warning\" role=\"alert\"><strong>warning</strong>：　" . $error_msg . "</div>";
}
echo "<form action = 'submission_selectday.php'>";
echo "<input type = 'hidden' name = 'ID' value = '$ID'>";
echo "<button type = 'submit' class=\"btn btn-warning\">戻る</button>
             </form>";
?>
    </div>
</body>
</html>