<?php
session_start();
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $profile = $_POST['profile'];
    $email = $_POST['email'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
    $aikon = $_FILES['aikon'];
    $selectedOptions = isset($_POST['selectedOptions']) ? explode(',', $_POST['selectedOptions']) : [];

    // パスワードの一致をチェック
    if ($password1 !== $password2) {
        header('Location: acount_creation.php?error=パスワードが一致しません。');
        exit();
    }

    // メールアドレスの重複チェック
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Account WHERE mail = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        header('Location: acount_creation.php?error=このメールアドレスは既に使用されています。');
        exit();
    }

    // ユーザー名の重複チェック
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Account WHERE user_name = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        header('Location: acount_creation.php?error=このユーザー名は既に使用されています。');
        exit();
    }

    // アイコンのチェック
    if ($aikon['error'] === UPLOAD_ERR_NO_FILE) {
        header('Location: acount_creation.php?error=アイコンを選択してください。');
        exit();
    }

    // ファイルアップロードの処理
    $aikon_path = '';
    if ($aikon['error'] === UPLOAD_ERR_OK) {
        $aikon_name = basename($aikon['name']);
        $upload_dir = 'img/aikon/';
        $upload_file = $upload_dir . $aikon_name;

        if (move_uploaded_file($aikon['tmp_name'], $upload_file)) {
            $aikon_path = $aikon_name;
        } else {
            $_SESSION['error_message'] = "アイコンのアップロードに失敗しました。";
            header('Location: acount_creation.php');
            exit();
        }
    }

    // プレーンテキストパスワードを使用してユーザーをデータベースに追加
    $stmt = $pdo->prepare("INSERT INTO Account (user_name, display_name, profile, mail, pass, aikon) VALUES (:username, :name, :profile, :email, :password, :aikon_path)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':profile', $profile);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password1); // プレーンテキストパスワードを直接使用
    $stmt->bindParam(':aikon_path', $aikon_path);
    $stmt->execute();

    // User_tagに選択したタグを追加
    foreach ($selectedOptions as $tag_id) {
        if (!empty($tag_id)) { // タグIDが空でないことを確認
            $stmt = $pdo->prepare("INSERT INTO User_tag (user_name, tag_id) VALUES (:username, :tag_id)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':tag_id', $tag_id);
            $stmt->execute();
        }
    }

    // セッションの更新
    $_SESSION['user'] = [
        'user_name' => $username,
        'display_name' => $name,
        'profile' => $profile,
        'mail' => $email,
        'aikon' => $aikon_path
    ];

    // アカウント作成成功メッセージ
    echo '
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>アカウント作成成功</title>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                font-family: Arial, sans-serif; /* フォントの変更 */
                background-color: #f5f5f5; /* 背景色 */
            }
            .message {
                text-align: center;
                padding: 20px;
                border-radius: 8px;
                background-color: #fff;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                font-size: 18px; /* フォントサイズの調整 */
            }
        </style>
    </head>
    <body>
        <div class="message">
            アカウントが作成されました。<br>
            <a href="login.php">ログイン画面</a>へ移動してください。
        </div>
    </body>
    </html>
    ';
}
?>
