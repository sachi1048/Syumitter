<?php
session_start();
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_SESSION['user']['user_name'];
    $new_user_name = $_POST['user'];
    $display_name = $_POST['display'];
    $profile = $_POST['profile'];
    $mail = $_POST['mail'];
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    $aikon = $_FILES['aikon'];

    // エラーメッセージの初期化
    $error_message = '';

    // パスワードが一致するかチェック
    if ($pass1 !== $pass2) {
        $error_message = 'パスワードが一致しません。';
    } else {
        // ユーザー名の重複チェック
        $sql = $pdo->prepare('SELECT * FROM Account WHERE user_name = ? AND user_name != ?');
        $sql->execute([$new_user_name, $user_name]);
        if ($sql->fetch()) {
            $error_message = 'このユーザー名はすでに使用されています。';
        } else {
            // メールアドレスの重複チェック
            $sql = $pdo->prepare('SELECT * FROM Account WHERE mail = ? AND mail != ?');
            $sql->execute([$mail, $_SESSION['user']['mail']]);
            if ($sql->fetch()) {
                $error_message = 'このアドレスはすでに使用されています。';
            } else {
                // ファイルアップロードの処理
                if ($aikon['error'] === UPLOAD_ERR_OK) {
                    $aikon_name = basename($aikon['name']);
                    $upload_dir = 'img/aikon/';
                    $upload_file = $upload_dir . $aikon_name;

                    if (move_uploaded_file($aikon['tmp_name'], $upload_file)) {
                        $aikon_path = $aikon_name;
                    } else {
                        $aikon_path = $_SESSION['user']['aikon'];
                    }
                } else {
                    $aikon_path = $_SESSION['user']['aikon'];
                }

                // データベースの更新
                $sql = 'UPDATE Account SET user_name = ?, display_name = ?, profile = ?, mail = ?, pass = ?, aikon = ? WHERE user_name = ?';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$new_user_name, $display_name, $profile, $mail, password_hash($pass1, PASSWORD_DEFAULT), $aikon_path, $user_name]);

                // セッションの更新
                $_SESSION['user']['user_name'] = $new_user_name;
                $_SESSION['user']['display_name'] = $display_name;
                $_SESSION['user']['profile'] = $profile;
                $_SESSION['user']['mail'] = $mail;
                $_SESSION['user']['pass'] = $pass1;
                $_SESSION['user']['aikon'] = $aikon_path;

                // 成功時にリダイレクト
                header('Location: myprofile.php');
                exit();
            }
        }
    }

    // エラーがある場合、エラーメッセージをセッションに保存し、フォーム画面にリダイレクト
    $_SESSION['error_message'] = $error_message;
    header('Location: myprofile-edit.php');
    exit();
} else {
    header('Location: myprofile-edit.php');
    exit();
}
?>
