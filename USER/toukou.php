<?php
// DB接続
require 'db-connect.php';
// セッション開始
session_start();
// PDOインスタンス作成
$pdo = new PDO($connect, USER, PASS);

// 投稿処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['toukousuru'])) {
        // ログインしていなければエラーを表示
        if (!isset($_SESSION['user']['user_name'])) {
            echo '<h1 style="text-align:center; color:red;">エラーが発生しました</h1>';
        } else {
            // 現在の日付と時間を年/月/日 時：分：秒の形で変数に保存
            $currentDateTime = date('Y-m-d H:i:s');
            
            // ファイルがアップロードされたか確認
            if (isset($_FILES['fileInput']) && $_FILES['fileInput']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'img/toukou/';
                $fileName = basename($_FILES['fileInput']['name']);
                $uploadFile = $uploadDir . $fileName;

                // 同じファイル名が存在するかチェック
                if (!file_exists($uploadFile)) {
                    // ファイルを指定のフォルダに移動
                    if (move_uploaded_file($_FILES['fileInput']['tmp_name'], $uploadFile)) {
                        $_POST['naiyou'] = $fileName; // ファイル名をPOSTデータに設定
                    } else {
                        echo '<h2>ファイルのアップロードに失敗しました</h2>';
                        exit;
                    }
                } else {
                    echo '<h2>同じファイル名の画像が既に存在します</h2>';
                    exit;
                }
            }else{
                echo 'if文通ってないよ(´-ω-`)';
            }
            
            // タグ１とタグ2、タグ３すべてにデータがある場合の追加処理
            if (isset($_POST['tag1'], $_POST['tag2'], $_POST['tag3'])) {
                $ads = $pdo->prepare('INSERT INTO Toukou VALUES(null,?,?,?,?,?,?,?,?)');
                $ads->execute([$_POST['title'], $currentDateTime, $_POST['naiyou'], $_POST['setumei'], $_POST['tag1'], $_POST['tag2'], $_POST['tag3'], $_SESSION['user']['user_name']]);
                header("Location: myprofile.php");
                exit;
            } elseif (isset($_POST['tag1'], $_POST['tag2'])) {
                // タグ１とタグ２がある場合の追加処理
                $ads = $pdo->prepare('INSERT INTO Toukou VALUES(null,?,?,?,?,?,?,null,?)');
                $ads->execute([$_POST['title'], $currentDateTime, $_POST['naiyou'], $_POST['setumei'], $_POST['tag1'], $_POST['tag2'], $_SESSION['user']['user_name']]);
                header("Location: myprofile.php");
                exit;
            } elseif (isset($_POST['tag1'])) {
                // タグ１のみがある場合の追加処理
                $ads = $pdo->prepare('INSERT INTO Toukou VALUES(null,?,?,?,?,?,null,null,?)');
                $ads->execute([$_POST['title'], $currentDateTime, $_POST['naiyou'], $_POST['setumei'], $_POST['tag1'], $_SESSION['user']['user_name']]);
                header("Location: myprofile.php");
                exit;
            } else {
                echo '<h2>趣味タグを選択してください</h2>';
            }
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
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/menu.css">
    <title>投稿画面</title>
</head>
<body>
    <h1 class="h1-2">Syumitter</h1>
    <?php
    // ログインしていなければ警告を表示
    if (!isset($_SESSION['user']['user_name'])) {
        echo '<h3>ログインしてから出直してださい(´-ω-`)</h3>';
        echo '<h3>このまま投稿すればエラーが出ます！(。-`ω-)</h3>';
    }
    ?>
    <form action="toukou.php" method="post" enctype="multipart/form-data">
        <div class="toukougazou" id="toukougazou">
            <input type="file" id="fileInput" name="fileInput" accept="image/toukou/*" style="display: none;">
            <button type="button" class="center-button" onclick="document.getElementById('fileInput').click();">写真・動画を選択</button>
        </div>
        <input type="hidden" name="naiyou" id="naiyou"> <!-- 画像ファイル名を保存するための隠しフィールド -->
        <p class="koumoku">タイトル</p>
        <input class="inp" type="text" name="title" maxlength="100" id="title" required>
        <br>
        <?php
        // もし選択された趣味タグがあれば表示する
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['selectedOptions']) && is_array($_POST['selectedOptions'])) {
                $count = 0;
                foreach($_POST['selectedOptions'] as $pow) {
                    $sel = $pdo->prepare('SELECT * FROM Tag WHERE tag_id = ?');
                    $sel->execute([$pow]);
                    foreach($sel as $woe) {
                        $count++;
                        echo '<div style="border:1.2px solid rgb(' . $woe['tag_color1'] . ',' . $woe['tag_color2'] . ',' . $woe['tag_color3'] . '); color:rgb(' . $woe['tag_color1'] . ',' . $woe['tag_color2'] . ',' . $woe['tag_color3'] . ');" class="tag_ln">#' . $woe['tag_mei'] . '</div>';
                        echo '<input type="hidden" name="tag' . $count . '" value="' . $woe['tag_id'] . '">';
                    }
                }
            }
        }
        ?>
        <button class="tagbutton" type="button" onclick="saveFormData(); location.href='tag_sentaku.php';">＃趣味タグ追加</button>
        <p class="koumoku">キャプション</p>
        <textarea class="setumeinp" name="setumei" maxlength="400" id="setumei" required></textarea>
        <br>
        <button class="nextbutton" type="submit" name="toukousuru">投稿</button>
    </form>
    <br><br><br><br>
    <?php require 'menu.php';?>
    <script>
        function saveFormData() {
            var title = document.getElementById('title').value;
            var setumei = document.getElementById('setumei').value;
            var fileInput = document.getElementById('fileInput').files[0];

            localStorage.setItem('title', title);
            localStorage.setItem('setumei', setumei);
            if (fileInput) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    localStorage.setItem('fileInput', e.target.result);
                    localStorage.setItem('fileName', fileInput.name);
                }
                reader.readAsDataURL(fileInput);
            }
        }

        function loadFormData() {
            var title = localStorage.getItem('title');
            var setumei = localStorage.getItem('setumei');
            var fileInput = localStorage.getItem('fileInput');
            var fileName = localStorage.getItem('fileName');

            if (title) {
                document.getElementById('title').value = title;
            }
            if (setumei) {
                document.getElementById('setumei').value = setumei;
            }
            if (fileInput) {
                document.getElementById('toukougazou').style.backgroundImage = 'url(' + fileInput + ')';
            }
            if (fileName) {
                document.getElementById('naiyou').value = fileName;
            }
        }

        document.getElementById('fileInput').addEventListener('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('toukougazou').style.backgroundImage = 'url(' + e.target.result + ')';
                    document.getElementById('naiyou').value = file.name; // 画像の名前をhidden inputに設定
                }
                reader.readAsDataURL(file);
            }
        });

        window.onload = function() {
            loadFormData();
        };
    </script>
</body>
</html>