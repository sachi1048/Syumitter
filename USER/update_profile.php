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

    // パスワードが一致するかチェック
    if ($pass1 !== $pass2) {
        header('Location: myprofile-edit.php');
        exit();
    }

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
    $stmt->execute([$new_user_name, $display_name, $profile, $mail, $pass1, $aikon_path, $user_name]);

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
} else {
    header('Location: myprofile-edit.php');
    exit();
}
?>