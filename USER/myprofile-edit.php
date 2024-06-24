<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
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
                errorMessage.style.display = 'block'; // エラーメッセージを表示する
            } else {
                errorMessage.style.display = 'none'; // エラーメッセージを非表示にする
                // ここでフォームを送信します
                window.location.href = 'success.html'; // パスワードが一致した場合に遷移する画面
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

        $sql=$pdo->prepare('select * from Account where user_name=? ');
        $sql->execute([$user_name]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        if($row){
    ?>
    <h1 class="h1-1">Syumitter</h1>

    <div class="frame">
        <h2>プロフィール編集</h2>
        <form id="passwordForm" action="update_profile.php" method="POST" enctype="multipart/form-data">
        <div class="aikon">
            <lable label="file_label">
                <img src="img/aikon/<?php echo $aikon; ?>" class="maru">
                <input type="file" name="aikon">
            </lable>
        </div>
        <table style="margin:auto;">
            <tr>
                <td>ユーザー名</td>
                <td><input type="textbox" name="user" value="<?php echo htmlspecialchars($row['user_name']); ?>"></td>
            </tr>
            <tr>
                <td>名前</td>
                <td><input type="textbox" name="display" value="<?php echo htmlspecialchars($row['display_name']); ?>"></td>
            </tr>
            <tr>
                <td>プロフィール</td>
                <td><textarea class="textbox" type="textbox" name="profile"><?php echo htmlspecialchars($row['profile']); ?></textarea></td>
            </tr>
            <tr>
                <td>アドレス</td>
                <td><input type="textbox" name="mail" value="<?php echo htmlspecialchars($row['mail']); ?>"></td>
            </tr>
            <tr>
                <td>パスワード</td>
                <td><input type="password" id="password1" name="pass1" value="<?php echo htmlspecialchars($row['pass']); ?>" required></td>
            </tr>
            <tr>
                <td>確認パスワード</td>
                <td><input type="password" id="password2" name="pass2" required></td>
            </tr>
        </table>
        <p id="errorMessage" class="error">パスワードが一致しません。</p>
        <button class="nextbutton" type="submit">編集</button>
        </form>
    </div>
    <?php } ?>
    <br>
    <a href="myprofile.php" class="btn-mdr">
        <span class="dli-caret-left"></span>
            戻る
    </a>
</body>
</html>