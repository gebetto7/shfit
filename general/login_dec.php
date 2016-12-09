<?php
$ID = $_GET['ID'];
if ($ID == 999){
    header('location: ../admin/admin_index.php?ID=' . $_GET['ID']);
    exit;
}
else if($ID){
    /*JSONデータ(スタッフ情報)の読み込み*/
    $staff_url = "../data/management/staff.json";
    $json = file_get_contents($staff_url);
    $staff_array = json_decode($json, true);

    if ($ID < sizeof($staff_array['staff'])){
        header('location: ../user/user_index.php?ID=' . $_GET['ID']);
        exit;
    }
    else{
        header('location: login.php?error=1');
        exit;
    }
}
else{
    header('location: login.php?error=2');
    exit;
}
?>