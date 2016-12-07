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

echo "----下記の内容で出勤希望を提出しました。----------------<br>";
for ($x = 0; $x <= 6; $x++){
    for ($y = 0; $y < sizeof($time_zone_array['time_zone']); $y++){
        $key = "t" . $x . "_" . $y;
        if (isset($_GET[$key])){
            echo $year . "年" . $month . "月" . $day . "日の" .
                $time_zone_array['time_zone'][$y]['name'] . " : 希望<br>";
            $push_data = Array(
                "number" => intval($ID),
                "min" => $time_zone_array['time_zone'][$y]['min'],
                "max" => $time_zone_array['time_zone'][$y]['max']
            );
            array_push($array['shift'], $push_data);
        }else{

        }
    }

    $url = "../data/shift/test/" . $year . $month . $day . ".json";
    if (!file_exists($url)){    //ファイルが存在しなかった場合、作成してから保存する
        file_put_contents($url, "");
        $fjson = fopen($url, "w+b");
        fwrite($fjson, json_encode($array, JSON_UNESCAPED_UNICODE));
        fclose($fjson);
    }
    else{                       //存在した場合は、そのデータを配列で読み込み配列を更新してから保存する
        $json = file_get_contents($url);
        $shift_array = json_decode($json, true);
        $overlapf = 0;
        for ($count =0; $count < sizeof($array['shift']); $count++){//出勤希望の数だけ
            for ($count2 = 0; $count < sizeof($shift_array['shift']); $count2++) {    //既に希望が出てる人数分だけ
                if ($shift_array['shift'][$count2]['number'] == $array['shift'][$count]['number']){ //既に提出があるものは省く
                    echo "既にこの時間帯に出勤希望を提出しています。<br>";
                    $overlapf = 1;
                    break;
                }
            }
            if (!$overlapf){    //重複がなければ配列に追記
                array_push($shift_array['shift'], $array['shift'][$count]);
            }
        }

        //配列のファイルへの保存
        $fjson = fopen($url, "w+b");
        fwrite($fjson, json_encode($shift_array, JSON_UNESCAPED_UNICODE));
        fclose($fjson);
        swap($url);
    }

    $array['shift'] = Array(); //初期化
    //日付の更新
    $day++;
    $day_array = check_date($year, $month, $day);
    $year = $day_array['year'];
    $month = $day_array['month'];
    $day = $day_array['day'];

}
echo "<form action = 'submission_selectday.php'>";
echo "<button type = 'submit'>戻る</button>
             </form>";