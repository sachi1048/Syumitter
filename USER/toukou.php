<?php
require 'db-connect.php';
session_start();
$pdo = new PDO($connect,USER,PASS);
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['toukousuru'])){
        $currentDateTime = date('Y-m-d H:i:s');
        if(isset($_POST['tag1'],$_POST['tag2'],$_POST['tag3'])){
            $ads=$pdo->prepare('insert into Toukou values(null,?,?,?,?,?,?,?,?)');
            $ads->execute([$_POST['title'],$currentDateTime,$_POST['naiyou'],$_POST['setumei'],$_POST['tag1'],$_POST['tag2'],$_POST['tag3'],$_SESSION['user']['user_name']]);
            header("Location: myprofile.php");
            exit;
        }else if(isset($_POST['tag1'],$_POST['tag2'])){
            $ads=$pdo->prepare('insert into Toukou values(null,?,?,?,?,?,?,null,?');
            $ads->execute([$_POST['title'],$currentDateTime,$_POST['naiyou'],$_POST['setumei'],$_POST['tag1'],$_POST['tag2'],$_SESSION['user']['user_name']]);
            header("Location: myprofile.php");
            exit;
        }else if(isset($_POST['tag1'])){
            $ads=$pdo->prepare('insert into Toukou values(null,?,?,?,?,?,null,null,?');
            $ads->execute([$_POST['title'],$currentDateTime,$_POST['naiyou'],$_POST['setumei'],$_POST['tag1'],$_SESSION['user']['user_name']]);
            header("Location: myprofile.php");
            exit;
        }else{
            echo '<h2>趣味タグを選択してください</h2>';
        }
    }
}
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
        <input type="hidden" name="naiyou" value="toukou1.jpg">
        <p class="koumoku">タイトル</p>
        <input type="text" name="title" required><!-- 投稿テーブルのタイトルに入れる用の入力フォームrequiredを付けることで入力必須項目にしている-->
        <br>
        <?php
        // 初期化
        $selectedTags = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['selectedOptions']) && is_array($_POST['selectedOptions'])) {
                $count=0;
                foreach($_POST['selectedOptions'] as $pow){
                    $sel=$pdo->prepare('select * from Tag where tag_id = ?');
                    $sel->execute([$pow]);
                    foreach($sel as $woe){
                        $count++;
                        echo '<p class="tag_ln">#',$woe['tag_mei'],'</p>';
                        echo '<input type="hidden" name="tag',$count,'" value="',$woe['tag_id'];
                    }
                }
                // 選択された趣味タグIDを変数に保存した状態
            }
        }
        // ここで$selectedTags変数に選択されたタグのIDが保存されています。
        // $selectedTagsを使って必要な処理を続けることができます。
        ?>
        <button class="tagbutton" onclick="location.href='tag_sentaku'">＃趣味タグ追加</button><!-- これから作成予定　この後から画面遷移した後に選択したものをSESSIONに入れる-->
        <p class="koumoku">キャプション</p>
        <input type="text" name="setumei" required><!-- 投稿の説明？-->
        <br>
        <button class="nextbotton" type="submit" name="toukousuru">投稿</button>
    </form>
</body>
</html>