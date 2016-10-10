<?php
$_LOGIN["id"] = $_POST["user_id"];
$_LOGIN["password"] = $_POST["password"];

if ($_LOGIN["id"] != "001" || $_LOGIN["password"] != "100"){
    echo "ログインに失敗しました。";
}

?>