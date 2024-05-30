<?php require 'db-connect.php';
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/checkbox.css">
    <title>投稿画面</title>
</head>
<body>
    <h1 class="syumitter1">Syumitter</h1> <!-- 上記のロゴ（？） -->
    <form action="toukou.php" method="post"><!-- 投稿ボタンを押したら一回自分の画面を再ロードしてDBにデータを追加してからマイプロフィール画面に飛ばす -->
        <img src="#" alt="未作成"> <!-- 未作成　ここに写真や動画を選択、表示 -->
        <p class="koumoku">タイトル</p>
        <input type="text" name="title" required><!-- 投稿テーブルのタイトルに入れる用の入力フォームrequiredを付けることで入力必須項目にしている-->
        <br>
        <?php
        $pdo = new PDO($connect,USER,PASS);
        // 初期化
        $selectedTags = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['selectedOptions']) && is_array($_POST['selectedOptions'])) {
                foreach($_POST['selectedOptions'] as $pow){
                    $sel=$pdo->prepare('select * from Tag where tag_id = ?');
                    $sel->execute([$pow]);
                    $count = 0;
                    foreach($sel as $woe){
                        $count++;
                        echo '<p class="tag_ln">#',$woe['tag_mei'],'</p>';
                        if($count == 3){
                            break;
                        }
                    }
                }
                // 選択された趣味タグIDを変数に保存した状態
            }
        }
        // ここで$selectedTags変数に選択されたタグのIDが保存されています。
        // $selectedTagsを使って必要な処理を続けることができます。
        ?>
        <button onclick="location.href='tag_sentaku'">＃趣味タグ追加</button><!-- これから作成予定　この後から画面遷移した後に選択したものをSESSIONに入れる-->
        <p class="koumoku">キャプション</p>
        <input type="text" name="setumei" required><!-- 投稿の説明？-->
        <br>
        <button class="nextbotton" type="submit">投稿</button>
    </form>
</body>
</html>