<?php
    session_start();
    const SERVER = 'mysql301.phy.lolipop.lan';
    const DBNAME = 'LAA1517472-syumitta';
    const USER = 'LAA1517472';
    const PASS = 'kitagawa';

    $connect = 'mysql:host='.SERVER.';dbname='.DBNAME.';charset=utf8';
    $pdo=new PDO($connect,USER,PASS);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理画面</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <button class="back-button" type="button" onclick="location.href='main.php'">キャンセル</button>
    <div class="center">
    ログアウトしますか？
    <div class="container">
        <button class="mainbutton" type="button" onclick="location.href='login.php'">ログアウト</button>
        <button class="mainbutton" type="button" onclick="location.href='main.php'">キャンセル</button>

    </div>
    </div>
</body>
</html>
