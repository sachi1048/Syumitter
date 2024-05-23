<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/checkbox.css">
    <title>趣味タグ選択画面</title>
</head>
<body>
<h1 class="syumitter1">Syumitter</h1> <!-- 上記のロゴ（？） -->
<p>＃趣味の追加・削除</p>
<form action="tag_sentaku.php" method="post">
    <input type="text" name="tagmei" placeholder="新規タグ追加">
    <button type="submit">追加</button> <!-- フォームを送信するボタン -->
    <form action="toukou.php" method="POST"> <!-- フォームの開始、データ送信先とメソッドを指定 -->
    <div> <!-- 選択可能なオプションをまとめるためのコンテナ -->
    <?php
        // 各オプションのチェックボックスとラベル
        $pdo = new PDO($connect,USER,PASS);
        $sql = $pdo->query('select * from Tag');
        for($sql as$row){
            
        }
        <input type="checkbox" id="option1" name="selectedOptions[]" value="option1"> <!-- 非表示のチェックボックス -->
        <label for="option1" class="selectable">＃占い</label> <!-- チェックボックスに対応するラベル -->

        <input type="checkbox" id="option2" name="selectedOptions[]" value="option2">
        <label for="option2" class="selectable">＃釣り</label>

        <input type="checkbox" id="option3" name="selectedOptions[]" value="option3">
        <label for="option3" class="selectable">＃J-POP</label>

        <input type="checkbox" id="option4" name="selectedOptions[]" value="option4">
        <label for="option4" class="selectable">＃カフェ巡り♨</label>
    </div>
</form>
</form>
</body>
</html>