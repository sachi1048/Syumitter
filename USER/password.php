<?php
session_start(); // セッション開始
require 'db-connect.php'; // 共有のデータベース接続情報を読み込み

// データベース接続情報を用いて新たに$pdoを定義
$connect = 'mysql:host='.SERVER.';dbname='.DBNAME.';charset=utf8';

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベース接続失敗: " . $e->getMessage());
}

$message = ''; // メッセージ用の変数を初期化

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // パスワードの一致を確認
    if ($newPassword !== $confirmPassword) {
        $message = "パスワードが一致しません。";
    } else {
        // メールアドレスが存在するかを確認
        $stmt = $pdo->prepare("SELECT * FROM Account WHERE mail = :mail");
        $stmt->bindParam(':mail', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // パスワードを更新
            $updateStmt = $pdo->prepare("UPDATE Account SET pass = :pass WHERE mail = :mail");
            $updateStmt->bindParam(':pass', $newPassword);
            $updateStmt->bindParam(':mail', $email);
            $updateStmt->execute();

            // ログイン画面にリダイレクト
            header('Location: login.php');
            exit;
        } else {
            $message = "そのメールアドレスは存在しません。";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syumitter - パスワード再設定</title>
    <link rel="stylesheet" href="CSS/style.css">
    <style>
        .message {
            color: red;
            font-size: 14px; /* 必要に応じてサイズを調整 */
            margin-top: 10px; /* メッセージとフォームの間にスペースを追加 */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Syumitter</h1>
        <div class="reset-box">
            <h2>パスワード再設定</h2>
            <?php if ($message): ?>
                <p class="message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="email">アドレス</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="new_password">新規パスワード</label>
                    <input type="password" id="new-password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">確認パスワード</label>
                    <input type="password" id="confirm-password" name="confirm_password" required>
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
