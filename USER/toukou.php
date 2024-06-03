<?php
require 'db-connect.php';
session_start();
$pdo = new PDO($connect,USER,PASS);

// 投稿処理
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['toukousuru'])){
        // 現在の日付と時間を年/月/日 時：分：秒の形で変数に保存
        $currentDateTime = date('Y-m-d H:i:s');
        // タグ１とタグ2、タグ３すべてにデータがある場合の追加処理
        if(isset($_POST['tag1'],$_POST['tag2'],$_POST['tag3'])){
            $ads=$pdo->prepare('insert into Toukou values(null,?,?,?,?,?,?,?,?)');
            $ads->execute([$_POST['title'],$currentDateTime,$_POST['naiyou'],$_POST['setumei'],$_POST['tag1'],$_POST['tag2'],$_POST['tag3'],$_SESSION['user']['user_name']]);
            header("Location: myprofile.php");
            exit;
        }else if(isset($_POST['tag1'],$_POST['tag2'])){
            // タグ１とタグ２が
            $ads=$pdo->prepare('insert into Toukou values(null,?,?,?,?,?,?,null,?)');
            $ads->execute([$_POST['title'],$currentDateTime,$_POST['naiyou'],$_POST['setumei'],$_POST['tag1'],$_POST['tag2'],$_SESSION['user']['user_name']]);
            header("Location: myprofile.php");
            exit;
        }else if(isset($_POST['tag1'])){
            $ads=$pdo->prepare('insert into Toukou values(null,?,?,?,?,?,null,null,?)');
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
    <link rel="stylesheet" href="CSS/checkbox.css">
    <link rel="stylesheet" href="CSS/menu.css">
    <title>投稿画面</title>
</head>
<body>
    <h1 class="syumitter1">Syumitter</h1> <!-- 上記のロゴ（？） -->
    <form action="toukou.php" method="post" enctype="multipart/form-data"> <!-- enctype属性を追加 -->
        <div class="toukougazou" id="toukougazou">
            <input type="file" id="fileInput" accept="image/*" style="display: none;">
            <button type="button" class="center-button" onclick="document.getElementById('fileInput').click();">写真・動画を選択</button>
            <p id="fileName"></p>
        </div>
        <input type="hidden" name="naiyou" id="naiyou"><!-- 画像ファイル名を保存するhiddenフィールド -->
        
        <p class="koumoku">タイトル</p>
        <input class="inp" type="text" name="title" required><!-- 投稿テーブルのタイトルに入れる用の入力フォームrequiredを付けることで入力必須項目にしている-->
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
                        echo '<div class="tag_ln">#',$woe['tag_mei'],'</div>';
                        echo '<input type="hidden" name="tag',$count,'" value="',$woe['tag_id'],'">';
                    }
                }
                // 選択された趣味タグIDを変数に保存した状態
            }
        }
        // ここで$selectedTags変数に選択されたタグのIDが保存されています。
        // $selectedTagsを使って必要な処理を続けることができます。
        ?>
        <button class="tagbutton" type="button" onclick="location.href='tag_sentaku.php'">＃趣味タグ追加</button><!-- これから作成予定　この後から画面遷移した後に選択したものをSESSIONに入れる-->
        <p class="koumoku">キャプション</p>
        <input class="setumeinp" type="text" name="setumei" required><!-- 投稿の説明？-->
        <br>
        <button class="nextbutton" type="submit" name="toukousuru">投稿</button>
    </form>
    <?php require 'menu.php';?>

    <script>
    document.getElementById('fileInput').addEventListener('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('toukougazou').style.backgroundImage = 'url(' + e.target.result + ')';
            }
            reader.readAsDataURL(file);
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('naiyou').value = file.name;
        }
    });
    </script>
</body>
</html>