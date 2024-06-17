<?php
require 'db-connect.php';

session_start();
$current_user_name = isset($_SESSION['user']['user_name']) ? $_SESSION['user']['user_name'] : null; // ログインしているユーザーの名前をセッションから取得



try {
    // データベースに接続
    $pdo = new PDO($connect, USER, PASS);
    // エラーモードを例外モードに設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $like_stmt = $pdo->prepare("
                INSERT INTO Comment (toukou_id, account_mei, comment_type)
                VALUES (:toukou_id, :current_user_name, 1)
            ");
            $like_stmt->bindParam(':toukou_id', $_POST['toukou_id'], PDO::PARAM_INT);
            $like_stmt->bindParam(':current_user_name', $current_user_name, PDO::PARAM_STR);
            $like_stmt->execute();

            header('Location: https://aso2301333.vivian.jp/Syumitter/USER/toukou_disp.php?toukou_id='.$_POST['toukou_id']);

} catch (PDOException $e) {
    // エラー処理
    echo "Error: " . $e->getMessage();
}
?>