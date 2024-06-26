<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syumitter - パスワード再設定</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <h1>Syumitter</h1>
        <div class="reset-box">
            <h2>パスワード再設定</h2>
                <form action="" method="post">
                <div class="form-group">
                    <label for="email">アドレス</label>
                    <input type="email" id="email" name="email">     
                </div>
                <div class="form-group">
                    <label for="new_password">新規パスワード</label>
                    <input type="password" id="new-password" name="new_password">
                </div>
                <div class="form-group">
                    <label for="confirm_password">確認パスワード</label>
                    <input type="password" id="confirm-password" name="confirm_password">
                </div>
                <button type="submit">設定</button>
            </form>
        </div>
        <button class="backbutton" onclick="history.back()">
            <i class="fas fa-caret-left fa-2x"></i>戻る
        </button>

    </div>
</body>
</html>
