<?php
    session_start();
    unset($_SESSION['account']);
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
    <link rel="stylesheet" href="../css/management.css">
</head>
<body>
    <button class="back-button" type="button" onclick="location.href='../main.html'">メインへ戻る</button><!-- PHPになったら適応させてください -->
    <div class="center">
    <div class="container">
        <button class="button" type="button" onclick="location.href='account_List.php'">アカウント一覧</button>
        <button class="button" type="button" onclick="location.href='account_invalidList.php'">凍結中のアカウント一覧</button>
    </div>
</div>
</body>
</html>
