<?php
ob_start();
require 'db-connect.php';

session_start();
$user_name = isset($_SESSION['user']['user_name']) ? $_SESSION['user']['user_name'] : null; // ログインしているユーザーの名前をセッションから取得

// // デバッグ用: セッションのユーザー名を確認
// if ($user_name) {
//     echo "<p>セッションのユーザー名: $user_name</p>";
// } else {
//     echo "<p>セッションのユーザー名が取得できません。</p>";
// }

try {
    // データベースに接続
    $pdo = new PDO($connect, USER, PASS);
    // エラーモードを例外モードに設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // エラー処理
    echo "Error: " . $e->getMessage();
    exit;
}

if (isset($_GET['toukou_id'])) {
    $toukou_id = $_GET['toukou_id'];
} else {
    echo "<p>投稿IDが指定されていません。</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commentsend'])) {
    // // デバッグ用: POSTデータの内容を確認
    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";
    
    if ($user_name && isset($_POST['naiyou'])) {
        $comment = $_POST['naiyou'];
        try {
            $sdl = $pdo->prepare('INSERT INTO Comment (toukou_id, account_mei, naiyou, comment_type) VALUES (?, ?, ?, 0)');
            $sdl->execute([$toukou_id, $user_name, $comment]);
            // 成功メッセージの表示
            echo "<p>コメントが正常に追加されました。</p>";
            // リダイレクト
            header("Location: toukou_comment.php?toukou_id=$toukou_id");
            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "<p>必要なデータが不足しています。</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/comment.css">
    <link rel="stylesheet" href="CSS/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>投稿コメント表示画面</title>
</head>
<body>
    <div class="container">
        <h1 class="h1-2">Syumitter</h1>
        <a href="toukou_disp.php?toukou_id=<?php echo htmlspecialchars($toukou_id, ENT_QUOTES, 'UTF-8'); ?>">
        <a href="javascript:history.back()" class="btn-mdr2"></a>

</a>
        <?php
        $sql = "SELECT t.title, c.naiyou, a.aikon, a.user_name
                FROM Toukou t
                JOIN Comment c ON t.toukou_id = c.toukou_id 
                JOIN Account a ON a.user_name = c.account_mei
                WHERE c.comment_type = 0 AND t.toukou_id = :toukou_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // タイトルのコメントを表示
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<h1>" . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . "のコメント</h1>";
            // 他のコメントを表示
            do {
                echo "<div class='comment'>";
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
        <form action="toukou_comment.php?toukou_id=<?php echo htmlspecialchars($toukou_id, ENT_QUOTES, 'UTF-8'); ?>" method="post">
            <div class="comment-input">
                <input type="text" name="naiyou" placeholder="コメントを入力する" required>
                <button class="sousin" type="submit" name="commentsend">送信</button>
            </div>
        </form>
    </div>
    <br><br><br><bt><br><br>
    <footer><?php require 'menu.php'; ?></footer>
</body>
</html>
