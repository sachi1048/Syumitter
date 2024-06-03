<?php
require 'db-connect.php';

session_start();
$current_user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null; // ログインしているユーザーの名前をセッションから取得

try {
    // データベースに接続
    $pdo = new PDO($connect, USER, PASS);
    // エラーモードを例外モードに設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   
} catch (PDOException $e) {
    // エラー処理
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/toukou_disp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>投稿コメント表示画面</title>
</head>
<body>
    <h1 class="syumitter1">Syumitter</h1>

    
   <?php  $sql = "SELECT t.title, c.naiyou
            FROM Toukou t
            JOIN Comment c ON t.toukou_id = c.toukou_id";
    $stmt = $pdo->query($sql);

    // 結果を表示する
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<h1>" . $row['title'] . "のコメントです。</h1>";
       
    }
    ?>
</body>
</html>
