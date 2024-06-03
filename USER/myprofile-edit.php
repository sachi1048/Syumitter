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
    </script>
    <title>マイプロフィール編集画面</title>
</head>
<body>
    <h1 class="h1-1">Syumitter</h1>

    <div class="frame">
        <h2>プロフィール編集</h2>
        <form action="mypofile" method="POST">
        <div class="aikon">
            <lable label="file_label">
                <!-- <p class="maru" style="background-image: url('img/top18.jpg');"></p> -->
                <img src="img/top18.jpg" class="maru">
                <input type="file" name="aikon">
            </lable>
            <p class="file_none">変更なし</p>
        </div>
        <table style="margin:auto;">
            <tr>
                <td>ユーザー名</td>
                <td><input class="textbox" type="textbox" name="user" placeholder=""></td>
            </tr>
            <tr>
                <td>名前</td>
                <td><input class="textbox" type="textbox" name="display" placeholder=""></td>
            </tr>
            <tr>
                <td>プロフィール</td>
                <td><input class="textbox" type="textbox" name="profile" placeholder=""></td>
            </tr>
            <tr>
                <td>アドレス</td>
                <td><input class="textbox" type="textbox" name="mail" placeholder=""></td>
            </tr>
            <tr>
                <td>パスワード</td>
                <td><input class="textbox" type="textbox" placeholder=""></td>
            </tr>
            <tr>
                <td>確認パスワード</td>
                <td><input class="textbox" type="textbox" name="pass" placeholder=""></td>
            </tr>
        </table>

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