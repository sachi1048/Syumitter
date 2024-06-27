<?php
require 'db-connect.php';

session_start();
$current_user_name = isset($_SESSION['user']['user_name']) ? $_SESSION['user']['user_name'] : null; // ログインしているユーザーの名前をセッションから取得

try {
    // データベースに接続
    $pdo = new PDO($connect, USER, PASS);
    // エラーモードを例外モードに設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // デバッグ: スクリプトの開始を確認
    error_log("Script started");

    // フォームデータの確認
    if (isset($_POST['toukou_id']) && !empty($_POST['toukou_id'])) {
        $toukou_id = $_POST['toukou_id'];
        error_log("toukou_id: " . $toukou_id);
    } else {
        error_log("toukou_idが設定されていません");
        die('toukou_idが設定されていません');
    }

    if (empty($current_user_name)) {
        error_log('ユーザー名が設定されていません');
        die('ユーザー名が設定されていません');
    }

    // いいねの状態を確認するクエリ
    $check_like_stmt = $pdo->prepare("
        SELECT COUNT(*) as liked
        FROM Comment
        WHERE toukou_id = :toukou_id AND account_mei = :current_user_name AND comment_type = 1
    ");
    $check_like_stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
    $check_like_stmt->bindParam(':current_user_name', $current_user_name, PDO::PARAM_STR);
    $check_like_stmt->execute();
    $like_status = $check_like_stmt->fetch(PDO::FETCH_ASSOC);
    $liked = $like_status['liked'] > 0;

    // デバッグ: いいねの状態を確認
    error_log("Liked status: " . ($liked ? "true" : "false"));

    if ($liked) {
        // すでにいいねしている場合、いいねを取り消す
        $unlike_stmt = $pdo->prepare("
            DELETE FROM Comment
            WHERE toukou_id = :toukou_id AND account_mei = :current_user_name AND comment_type = 1
        ");
        $unlike_stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
        $unlike_stmt->bindParam(':current_user_name', $current_user_name, PDO::PARAM_STR);
        $unlike_stmt->execute();
        error_log("Unliked");
    } else {
        // まだいいねしていない場合、いいねを追加する
        $like_stmt = $pdo->prepare("
            INSERT INTO Comment (toukou_id, account_mei, comment_type)
            VALUES (:toukou_id, :current_user_name, 1)
        ");
        $like_stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
        $like_stmt->bindParam(':current_user_name', $current_user_name, PDO::PARAM_STR);
        $like_stmt->execute();
        error_log("Liked");
    }

    // リダイレクト
    header('Location: https://aso2301333.vivian.jp/Syumitter/USER/toukou_disp.php?toukou_id=' . $toukou_id);
    exit();
} catch (PDOException $e) {
    // エラー処理
    error_log("Error: " . $e->getMessage());
    echo "Error: " . $e->getMessage();
}
?>
