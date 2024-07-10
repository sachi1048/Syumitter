


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規情報入力画面</title>
    <link rel="stylesheet" href="CSS/creation.css">
</head>
<body>
    <div class="container">
        <h1 class="title">Syumitter</h1>
        <div class="form-container">
            <h2 class="form-title">アカウント新規作成</h2>
            <div class="avatar">
                
            </div>
            <form action="acount_done.php" method="POST">
                <label for="username">ユーザー名</label>
                <input type="text" id="username" name="username" required>

                <label for="displayname">名前</label>
                <input type="text" id="name" name="name" required>

                <label for="profile">プロフィール</label>
                <textarea id="profile" name="profile" rows="4" required></textarea>

                <label for="email">アドレス</label>
                <input type="email" id="email" name="email" required>

                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm_password">確認パスワード</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <button type="submit">作成</button>
            </form>
            <button class="back-button"># 趣味を選び直す</button>
        </div>
    </div>
</body>
</html>
