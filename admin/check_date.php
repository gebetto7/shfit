<?php
//年、月、日を渡すと正しい日にちを配列に入れて返す
    function check_date($year, $month, $day){
        if (checkdate($month, $day, $year)) {
            $day_array['year'] = $year;
            $day_array['month'] = $month;
            $day_array['day'] = $day;
        }
        else{
            $day = 1;
            $month++;
            if ($month == 13) {
                $year++;
                $month = 1;
            }
            $day_array['year'] = $year;
            $day_array['month'] = $month;
            $day_array['day'] = $day;
        }
        
        return $day_array;
    }