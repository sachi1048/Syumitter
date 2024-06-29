<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $server = 'mysql301.phy.lolipop.lan';
    $dbname = 'LAA1517472-syumitta';
    $user = 'LAA1517472';
    $pass = 'kitagawa';

    try {
        $pdo = new PDO("mysql:host=$server;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // フォームからのデータを取得
        $username = $_POST['username'];
        $name = $_POST['name'];
        $profile = $_POST['profile'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            echo "パスワードが一致しません。";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // データベースにユーザーを追加
            $stmt = $pdo->prepare("INSERT INTO users (username, name, profile, email, password) VALUES (:username, :name, :profile, :email, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':profile', $profile);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->execute();
            echo "アカウントが作成されました。";
        }
    } catch (PDOException $e) {
        echo "データベース接続失敗: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント新規作成</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            text-align: center;
        }

        .title {
            font-size: 2em;
            font-weight: bold;
        }

        .form-container {
            background: linear-gradient(to bottom, #ff758c, #ff7eb3, #d7b4f3);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: auto;
        }

        .form-title {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .avatar {
            width: 100px;
            height: 100px;
            background-color: white;
            border-radius: 50%;
            margin: 0 auto 20px auto;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            text-align: left;
            margin: 10px 0 5px 0;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        textarea {
            resize: none;
        }

        button[type="submit"] {
            background-color: #ffcc00;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #e6b800;
        }

        .back-button {
            background-color: #ffffff;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 0.9em;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">Syumitter</h1>
        <div class="form-container">
            <h2 class="form-title">アカウント新規作成</h2>
            <div class="avatar">
                <img src="avatar.png" alt="Avatar">
            </div>
            <form method="POST">
                <label for="username">ユーザー名</label>
                <input type="text" id="username" name="username" required>

                <label for="name">名前</label>
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
