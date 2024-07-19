<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.file__label input[type=file]').on('change', function () {
                var file = $(this).prop('files')[0];
                $('.file__none').text(file.name);
            });
        });

        document.getElementById('passwordForm').addEventListener('submit', function(event) {
            var password1 = document.getElementById('password1').value;
            var password2 = document.getElementById('password2').value;
            var errorMessage = document.getElementById('errorMessage');

            if (password1 !== password2) {
                event.preventDefault(); // フォームの送信を止める
                errorMessage.textContent = 'パスワードが一致しません。';
                errorMessage.style.display = 'block'; // エラーメッセージを表示する
            } else {
                errorMessage.style.display = 'none'; // エラーメッセージを非表示にする
            }
        });
    </script>
    <title>マイプロフィール編集画面</title>
</head>
<body>
    <?php 
        $pdo = new PDO($connect, USER, PASS);
        $user_name = $_SESSION['user']['user_name'];
        $display_name = $_SESSION['user']['display_name'];
        $aikon = $_SESSION['user']['aikon'];
        $profile = $_SESSION['user']['profile'];
        $mail = $_SESSION['user']['mail'];

        $sql = $pdo->prepare('SELECT * FROM Account WHERE user_name = ?');
        $sql->execute([$user_name]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        
        // エラーメッセージの取得
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['error_message']);
    ?>
    <h1 class="h1-1">Syumitter</h1>

    <div class="frame">
        <h2>プロフィール編集</h2>
        <?php if ($error_message): ?>
            <p class="error"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form id="passwordForm" action="update_profile.php" method="POST" enctype="multipart/form-data">
            <div class="aikon">
                <label label="file_label">
                    <img src="img/aikon/<?php echo htmlspecialchars($aikon, ENT_QUOTES, 'UTF-8'); ?>" class="maru">
                    <input type="file" name="aikon">
                </label>
            </div>
            <table style="margin:auto; font-size:small;">
                <tr>
                    <td>ユーザー名</td>
                    <td><input type="text" name="user" maxlength="20" value="<?php echo htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                </tr>
                <tr>
                    <td>名前</td>
                    <td><input type="text" name="display" maxlength="20" value="<?php echo htmlspecialchars($row['display_name'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                </tr>
                <tr>
                    <td>プロフィール</td>
                    <td><textarea class="textbox" name="profile" maxlength="250"><?php echo htmlspecialchars($row['profile'], ENT_QUOTES, 'UTF-8'); ?></textarea></td>
                </tr>
                <tr>
                    <td>アドレス</td>
                    <td><input type="text" name="mail" maxlength="30" value="<?php echo htmlspecialchars($row['mail'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                </tr>
                <tr>
                    <td>パスワード</td>
                    <td><input type="password" id="password1" name="pass1" maxlength="12" required></td>
                </tr>
                <tr>
                    <td>確認パスワード</td>
                    <td><input type="password" id="password2" name="pass2" maxlength="12" required></td>
                </tr>
            </table>
            <p id="errorMessage" class="error" style="display:none;">パスワードが一致しません。</p>
            <button class="nextbutton" type="submit">編集</button>
        </form>
    </div>
    <br>
    <a href="myprofile.php" class="btn-mdr">
        <span class="dli-caret-left"></span>
            戻る
    </a>
</body>
</html>
