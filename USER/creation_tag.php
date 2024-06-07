<?php require 'db-connect.php'; ?>
<?php
    session_start();
    $pdo = new PDO($connect, USER, PASS);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/checkbox.css">
    <title>新規趣味タグ選択画面</title>
    <!-- ここから↓ -->
    <style>
        #notification {
            display: none;
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #ffeb3b;
            padding: 10px;
            border: 1px solid #cddc39;
            border-radius: 5px;
            z-index: 1000;
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
        }
        #notification.hide {
            opacity: 0;
        }
    </style>
    <script>
        window.onload = function() {
            limitCheckboxSelection();
            var notification = document.getElementById('notification');
            if (notification.innerText !== '') {
                notification.style.display = 'block';
                setTimeout(function() {
                    notification.classList.add('hide');
                }, 1500);
                setTimeout(function() {
                    notification.style.display = 'none';
                }, 2000);
            }
        };
    </script>
    <!-- ここまではチャットGPTにしかわかりません -->
</head>
<body>
    <h1 class="h1-1">Syumitter</h1>
    <p>気になる<span class="tag_syumi">＃趣味</span>を選択</p><br>
    <!-- 趣味タグ一覧を表示 -->
    <form action="account_creation.php" method="POST">
        <div>
            <?php
               $sql = $pdo->query('SELECT * FROM Tag');
               $count = 1;
               foreach ($sql as $row) {
                    echo '<input type="checkbox" id="option', $count, '" name="selectedOptions[]" value="', $row['tag_id'], '">';
                    echo '<label for="option', $count, '" style="border:1.2px solid rgb(',$row['tag_color1'],',',$row['tag_color2'],',',$row['tag_color3'],'); color:rgb(',$row['tag_color1'],',',$row['tag_color2'],',',$row['tag_color3'],');" class="selectable">#', $row['tag_mei'], '</label>';
                    if($count%3 == 0){
                        echo '<br>';
                    }
                    $count++;
                }
            ?>
        </div>
        <button class="lastbutton" type="submit">決定</button>
    </form>
    <button class="backbutton" onclick="history.back()"><span class="aokusitai">◀</span> 戻る</button>
</body>
</html>