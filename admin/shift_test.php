<?php
include "check_date.php";
if (isset($_GET['action'])){
    $year = $_GET['year'];
    $month = $_GET['month'];
    $day = $_GET['day'];
    $first_day = $day;

    //getからのデータを格納する配列
    $name_array = array();

    //配列に名前を入れる
    for ($x = 0; $x <= 6; $x++){    //$x = day(一週間分)
        for ($y = 0; $y <= 4; $y++){ //$y == timezone(時間帯分)
            $get_name1 = "change1_" . $x . "_" . $y;
            $get_name2 = "change2_" . $x . "_" . $y;

            $name_array[0][$x][$y] = $_GET[$get_name1];
            $name_array[1][$x][$y] = $_GET[$get_name2];
        }
    }
    //tempのシフト表との名前の比較
    //名前が一致しなかった場合はnumberを上書きする
    
    //スタッフ情報の読み込み
    $staff_url = "../data/management/staff.json";
    $json = file_get_contents($staff_url);
    $staff_array = json_decode($json, true);

    //時間帯情報の読み込み
    $time_zone_url = "../data/management/time_zone.json";
    $json = file_get_contents($time_zone_url);
    $time_zone_array = json_decode($json, true);

    for ($count = 0; $count <= 6; $count++){
        echo "<br>" . ($count + 1) . "日目----------------------------------------<br>";
        $count2 = 0;    //シフト表走査用
        $numberp = 0;   //時間帯で何人入っているかの判断用

        //tempシフト情報の取得
        $shift_url = "../data/shift/temp/" . $year . $month . $day . ".json";
        $json = file_get_contents($shift_url);
        $shift_array = json_decode($json, true);
        //候補者情報の取得(変更があった際の入れ替え用)
        $candidate_url = "../data/shift/temp/candidate/" . $year . $month . $day . ".json";
        $json = file_get_contents($candidate_url);
        $candidate_array = json_decode($json, true);

        for ($time_zone_count = 0; $time_zone_count < sizeof($time_zone_array['time_zone']); $time_zone_count++){

            echo $time_zone_array['time_zone'][$time_zone_count]['name'] . "---------------------<br>";
            while (($count2 < sizeof($shift_array['shift'])) &&
                ($shift_array['shift'][$count2]['min'] == $time_zone_array['time_zone'][$time_zone_count]['min']) &&
                ($shift_array['shift'][$count2]['max'] == $time_zone_array['time_zone'][$time_zone_count]['max'])){

                $key = $shift_array['shift'][$count2]['number'];
                if ($staff_array['staff'][$key]['name'] != $name_array[$numberp][$count][$time_zone_count]){
                    echo ($numberp + 1) . "人目 : 変更があります。　　　　　";
                    echo $staff_array['staff'][$key]['name'] . "→→→→→";
                    echo $name_array[$numberp][$count][$time_zone_count] . "<br>";
                    for ($x = 0; $x < (sizeof($staff_array['staff'])); $x++){  //変更後の人物のナンバーを取得する
                        if ($staff_array['staff'][$x]['name'] == $name_array[$numberp][$count][$time_zone_count])
                            $modify_number = $staff_array['staff'][$x]['number'];
                    }
                    for ($x = 0; $x < (sizeof($candidate_array[$time_zone_array['time_zone'][$time_zone_count]['name']])); $x++){
                        if ($candidate_array[$time_zone_array['time_zone'][$time_zone_count]['name']][$x]['number'] == $modify_number){
                            var_dump($candidate_array[$time_zone_array['time_zone'][$time_zone_count]['name']][$x]['number']);
                            echo $shift_array['shift'][$count2]['number'] . "(交換される人)と" . $candidate_array[$time_zone_array['time_zone'][$time_zone_count]['name']][$x]['number'] . "(交換する人)<br>";
                            $swap = $candidate_array[$time_zone_array['time_zone'][$time_zone_count]['name']][$x]['number'];
                            $candidate_array[$time_zone_array['time_zone'][$time_zone_count]['name']][$x]['number'] = $shift_array['shift'][$count2]['number'];
                            $shift_array['shift'][$count2]['number'] = $swap;
                            echo $shift_array['shift'][$count2]['number'] . "(交換される人)と" . $candidate_array[$time_zone_array['time_zone'][$time_zone_count]['name']][$x]['number'] . "(交換する人)<br>";
                        }
                    }
                }
                else
                    echo $numberp + 1 . "人目 : 変更はありませんでした。<br>";
                $count2++;
                $numberp++;
            }
            $numberp = 0;
        }
        //temp_modifyの作成
        $fjson = fopen("../data/shift/temp/" . $year . $month . $day . ".json", "w+b");
        fwrite($fjson, json_encode($shift_array, JSON_UNESCAPED_UNICODE));
        fclose($fjson);

        //temp_candidateの作成
        $fjson = fopen("../data/shift/temp/candidate/" . $year . $month . $day . ".json", "w+b");
        fwrite($fjson, json_encode($candidate_array, JSON_UNESCAPED_UNICODE));
        fclose($fjson);

        //日付の更新
        $day++;
        $day_array = check_date($year, $month, $day);
        $year = $day_array['year'];
        $month = $day_array['month'];
        $day = $day_array['day'];
    }

    echo "<form action = 'shift_create.php' method = 'get'>";
    echo "<input type = 'hidden' name = 'year' value = '$year'>";
    echo "<input type = 'hidden' name = 'month' value = '$month'>";
    echo "<input type = 'hidden' name = 'day' value = '$first_day'>";
    echo "<input type = 'hidden' name = 'modify' value = 'true'>";
    echo "<br><button type = 'submit' name = 'action' value = 'back'>確定</button>
                </form>";
    echo "<input type = 'button' value = '戻る' onclick = 'history.back();'>";
}
else
    echo "error<br>";
