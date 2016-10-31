<?php
    $time_url = "../data/management/time_zone.json";
    $json = file_get_contents($time_url);
    $time = json_decode($json, true);

    echo $time["time_zone"][0]["wage"];