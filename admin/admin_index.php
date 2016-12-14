<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>管理者用トップページ</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>トップページ <small>管理者</small></h1>
    </div>
<div class="form-group">
    <button type="button" class="btn btn-default" onclick="location.href='shift_create_selectday.php'">シフト表作成</button>
</div>
<div class="form-group">
    <button type="button" class="btn btn-default" onclick="location.href='../general/view_selectday.php'">シフト表確認</button>
</div>
<div class="form-group">
    <button type = 'button' class="btn btn-danger" onclick = "location.href = '../general/login.php'">ログアウト</button>
</div>
    </div>
</body>
</html>