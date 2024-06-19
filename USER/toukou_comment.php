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
    <link rel="stylesheet" href="CSS/comment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>投稿コメント表示画面</title>
</head>
<body>
    <div class="container">
    <h1 class="syumitter1">Syumitter</h1>
   <?php  
   if (isset($_GET['toukou_id'])) {
    $toukou_id = $_GET['toukou_id'];}
   $sql = "SELECT t.title, c.naiyou,a.aikon,a.user_name
            FROM Toukou t
            JOIN Comment c ON t.toukou_id = c.toukou_id 
            JOIN Account a ON a.user_name =t.toukou_mei
            WHERE c.comment_type=0  AND t.toukou_id = :toukou_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount()>0) {
        // タイトルのコメントを表示
        $row =$stmt->fetch(PDO::FETCH_ASSOC);
        echo "<h1>" . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . "のコメント</h1>";
        // 他のコメントを表示
        do {
            echo "<div class ='comment'>";
            echo "<img src='img/aikon/" . htmlspecialchars($row['aikon'], ENT_QUOTES, 'UTF-8') . "' alt='アイコン' class='user-icon'>";
            echo "<p class='username'>" . htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p class='naiyou'>" . htmlspecialchars($row['naiyou'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "</div>";
    } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
} else {
    // 結果が存在しない場合の処理
    echo "<p>コメントが見つかりませんでした。</p>";
}
   

?>
<form action="" method="post">
<div class="comment-input">
    <input type="text" name="naiyou"
    placeholder="新しいコメントを入力する">
    <button>送信</button>
</form>

</div>
</div>
</body>
</html>