<?php require 'db-connect.php'; ?>
<?php
    session_start();
    $pdo = new PDO($connect, USER, PASS);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['tagmei'])) {
            $num1 = random_int(0,255);
            $num2 = random_int(0,255);
            $num3 = random_int(0,255);
            $ssl = $pdo->prepare('INSERT INTO Tag VALUES(null, ?,?,?,?)');
            $ssl->execute([$_POST['tagmei'],$num1,$num2,$num3]);
            $_SESSION['message'] = '趣味タグを追加しました';
        }
    }

    $message = '';
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/checkbox.css">
    <title>趣味タグ選択画面</title>
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
        // JavaScript function to limit checkbox selection to 3
        function limitCheckboxSelection() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const limit = 1;
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    const checkedCount = document.querySelectorAll('input[type="checkbox"]:checked').length;
                    if (checkedCount > limit) {
                        checkbox.checked = false;
                    }
                });
            });
        }
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
    <p><span class="tag_syumi">＃趣味</span>の追加・削除</p>
    <div id="notification"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <!-- 趣味タグの追加 -->
    <form action="" method="post">
        <input class="tag_inp" type="text" name="tagmei" maxlength="13" placeholder="　新規タグ追加" required>　
        <button class="nizibutton" type="submit">追加</button>
        <p>１つまで選択可能</p>
    </form><br>
    <!-- 趣味タグ一覧を表示 -->
    <form action="group_new.php" method="POST">
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
    <!-- ここから先はチャットGPTに仕様を聞いてください -->
    <?php if ($message): ?>
        <script>
            window.onload = function() {
                var notification = document.getElementById('notification');
                notification.style.display = 'block';
                setTimeout(function() {
                    notification.classList.add('hide');
                }, 1500);
                setTimeout(function() {
                    notification.style.display = 'none';
                }, 2000);
            };
        </script>
    <?php endif; ?>
</body>
</html>