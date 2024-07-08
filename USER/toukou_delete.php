<?php
session_start();
require 'db-connect.php';

if (isset($_POST['delete_post'])) {
    $toukou_id = $_POST['toukou_id'];

    try {
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // コメントを削除
        $delete_comments_stmt = $pdo->prepare("
            DELETE FROM Comment WHERE toukou_id = :toukou_id
        ");
        $delete_comments_stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
        $delete_comments_stmt->execute();

        // 投稿を削除
        $delete_post_stmt = $pdo->prepare("
            DELETE FROM Toukou WHERE toukou_id = :toukou_id
        ");
        $delete_post_stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
        $delete_post_stmt->execute();

        header("Location: myprofile.php");
        exit();
    } catch (PDOException $e) {
        echo "エラー：" . $e->getMessage();
    }
} else {
    header("Location: myprofile.php");
    exit();
}
?>
