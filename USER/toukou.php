<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <title>投稿画面</title>
</head>
<body>
    <h1 class="syumitter1">Syumitter</h1> <!-- 上記のロゴ（？） -->
    <form action="toukou.php" method="post"><!-- 投稿ボタンを押したら一回自分の画面を再ロードしてDBにデータを追加してからマイプロフィール画面に飛ばす -->
        <img src="#" alt="未作成"> <!-- 未作成　ここに写真や動画を選択、表示 -->
        <p>タイトル</p>
        <input type="text" name="title" required><!-- 投稿テーブルのタイトルに入れる用の入力フォームrequiredを付けることで入力必須項目にしている-->
        <br>
        <button class="frame" onclick="location.href='tag_sentaku'">＃趣味タグ追加</button><!-- これから作成予定　この後から画面遷移した後に選択したものをSESSIONに入れる-->
        <p>キャプション</p>
        <input class="frame" type="text" name="setumei" required><!-- 投稿の説明？-->
        <br>
        <button class="nextbotton" type="submit">投稿</button>
    </form>
</body>
</html>