<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>欠員補充-確認-</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
include '../admin/check_date.php';
$ID = $_GET['ID'];

if (isset($_GET['year'])){
    $year = $_GET['year'];
    $month = $_GET['month'];
    $day = $_GET['day'];
}
//時間帯情報の読み込み
$time_zone_url = "../data/management/time_zone.json";
$json = file_get_contents($time_zone_url);
$time_zone_array = json_decode($json, true);

$count = 0;         //埋まったかの確認用
$overlapf = 0;      //重複しているかのフラグ
$msg = "";          //echoする文章格納用
for ($x = 0; $x <= 6; $x++){    //一週間分
    $date = $year . $month . $day;
    echo $year . "年" . $month . "月" . $day . "日<br>";
    //shift/mainの読み込み
    $shift_url = "../data/shift/main/" . $year . $month . $day . ".json";
    $json = file_get_contents($shift_url);
    $shift_array = json_decode($json, true);
    //欠員情報の読み込み
    $blank_url = "../data/shift/blank/" . $year . $month . $day . ".json";
    if (file_exists($blank_url)){
        $json = file_get_contents($blank_url);
        $blank_array = json_decode($json, true);
    }
    for ($y = 0; $y < sizeof($time_zone_array['time_zone']); $y++){     //時間帯分
        $name = $year . "/" . $month . "/" . $day . "_" . $time_zone_array['time_zone'][$y]['name'];
        if (isset($_GET[$name])){
            //mainshiftにpushするデータを用意する
            $push_array = Array(
                "number" => intval($ID),
                "min" => $time_zone_array['time_zone'][$y]['min'],
                "max" => $time_zone_array['time_zone'][$y]['max']
            );
            for ($count = 0; $count < sizeof($shift_array['shift']); $count++){
                if (($shift_array['shift'][$count]['number'] == $push_array['number'])
                    &&   $shift_array['shift'][$count]['min'] == $push_array['min']
                    &&   $shift_array['shift'][$count]['max'] == $push_array['max']){
                    $msg .= "既に" . $time_zone_array['time_zone'][$y]['name'] . "に出勤しています。<br>";
                    $overlapf = 1;      //フラグを立てる
                }
            }
            if (!$overlapf){
                //mainにpushする
                array_push($shift_array['shift'], $push_array);
                $msg .= $time_zone_array['time_zone'][$y]['name'] . "に出勤希望を提出しました。<br>";
                //pushされた情報を欠員一覧から削除する

                for ($blank_count = 0; $blank_count < sizeof($blank_array[$date]); $blank_count++) { //blank情報の走査
                    if ($blank_array[$date][$blank_count] == $time_zone_array['time_zone'][$y]['name']) {   //埋まった時間帯とマッチした場合そのblank情報を削除する
                        array_splice($blank_array[$date], $blank_count, 1);
                    }
                }
                $count++;
            }
        }
        $overlapf = 0;
    }
    //mainへの反映
    $fjson = fopen($shift_url, "w+b");
    fwrite($fjson, json_encode($shift_array, JSON_UNESCAPED_UNICODE));
    fclose($fjson);

    if ($count){    //一回でもいたら保存する、入れ替えをしなかった場合はファイルを保存しない
        if ($blank_array[$date]){   //欠員一覧が全部埋まった場合は保存せずファイルを消す
            $fjson = fopen($blank_url, "w+b");
            fwrite($fjson, json_encode($blank_array, JSON_UNESCAPED_UNICODE));
            fclose($fjson);
        }
        else{
            unlink($blank_url);
        }
    }

    //日付の更新
    $day++;
    $day_array = check_date($year, $month, $day);
    $year = $day_array['year'];
    $month = $day_array['month'];
    $day = $day_array['day'];

    if($msg == "")
        $msg .= "変更はありません。<br>";
    echo "<p>" . $msg . "</p>";
    $msg = "";
    $count = 0;
}
echo "<br><form action = 'blank_selectday.php' method = 'get'>";
echo "<input type = 'hidden' name = 'ID' value = '$ID'>";
echo "<button type = 'submit' class=\"btn btn-warning\">戻る</button>
             </form>";
?>
</body>
</html>
