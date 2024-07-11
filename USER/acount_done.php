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
        $user_name = $_POST['username'];
        $display_name = $_POST['name'];
        $profile = $_POST['profile'];
        $mail = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            echo "パスワードが一致しません。";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // データベースにユーザーを追加
            $stmt = $pdo->prepare("INSERT INTO Account (user_name, display_name, profile, mail, pass) VALUES (:user_name, :display_name, :profile, :mail, :pass)");
            $stmt->bindParam(':user_name', $user_name);
            $stmt->bindParam(':display_name', $display_name);
            $stmt->bindParam(':profile', $profile);
            $stmt->bindParam(':mail', $mail);
            $stmt->bindParam(':pass', $hashed_password);
            $stmt->execute();
            echo "";
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
    <title>新規作成完了</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
        }
        .container {
            text-align: center;
        }
        a {
            color: #00aaff;
            text-decoration: none;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <p>アカウントが作成されました</p>
        <a href="login.php">ログイン画面へ</a>
    </div>
</body>
</html>
