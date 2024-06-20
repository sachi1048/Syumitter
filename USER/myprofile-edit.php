<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <script>
        $(function() {
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

        $sql=$pdo->prepare('select * from Account where user_mei=?');
        $sql->execute([$user_name]);
    ?>
    <h1 class="h1-1">Syumitter</h1>

    <div class="frame">
        <h2>プロフィール編集</h2>
        <form action="mypofile" method="POST">
        <div class="aikon">
            <lable label="file_label">
                <img src="img/aikon/<?php echo $aikon; ?>" class="maru">
                <input type="file" name="aikon">
            </lable>
            <p class="file_none">変更なし</p>
        </div>
        <table style="margin:auto;">
            <tr>
                <td>ユーザー名</td>
                <td><input class="textbox" type="textbox" name="user" placeholder="<?php echo $sql['user_name']; ?>"></td>
            </tr>
            <tr>
                <td>名前</td>
                <td><input class="textbox" type="textbox" name="display" placeholder="<?php echo $sql['display_name']; ?>"></td>
            </tr>
            <tr>
                <td>プロフィール</td>
                <td><textarea class="textbox" type="textbox" name="profile" placeholder="<?php echo $sql['profile']; ?>"></td>
            </tr>
            <tr>
                <td>アドレス</td>
                <td><input class="textbox" type="textbox" name="mail" placeholder="<?php echo $sql['mail']; ?>"></td>
            </tr>
            <tr>
                <td>パスワード</td>
                <td><input class="textbox" type="password" id="password1" name="pass1" placeholder="<?php echo $sql['pass']; ?>" required></td>
            </tr>
            <tr>
                <td>確認パスワード</td>
                <td><input class="textbox" type="password" id="password2" name="pass2" required></td>
            </tr>
        </table>
        <p id="errorMessage" class="error">パスワードが一致しません。</p>
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