<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<?php

    $_SESSION['group_id'] = array();

    $pdo = new PDO($connect, USER, PASS);
    $user_name = $_SESSION['user']['user_name'];
    $display_name = $_SESSION['user']['display_name'];
    $aikon = $_SESSION['user']['aikon'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/menu.css">
    <title>新規グループチャット作成画面</title>
</head>
<body>
    <h1 class="h1-2">Syumitter</h1>
    <a href="group_list.php">
        <span class="btn-mdr2"></span>
    </a>

    <div class="frame">
        <h2>新規グループチャット作成<h2>
        <div class="aikon">
            <lable label="file_label">
                <img src="img/aikon/<?php echo $aikon; ?>" class="maru">
                <input type="file" name="aikon">
            </lable>
        </div>
        <input type="text" name="group_name" placeholder="チャット名">
        <br>

        <!-- 後で変更 -->
        <button class="btn-tag" type="button" onclick="location.href='tag_sentaku.php'">＃趣味タグ選択</button>
        <br>
        <button class="btn-tag" type="button" onclick="location.href='tag_sentaku.php'">メンバーの招待</button>
        <br><br>
        <button class="nextbutton" type="submit">作成</button>
    </div>


    <?php require 'menu.php';?>

</body>
</html>