<?php
require 'db-connect.php';

session_start();
$current_user_name = $_SESSION['user_name']; // ログインしているユーザーの名前をセッションから取得

try {
    // データベースに接続
    $pdo = new PDO($connect, USER, PASS);
    // エラーモードを例外モードに設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // URLパラメータから投稿IDを取得
    if (isset($_GET['toukou_id'])) {
        $toukou_id = $_GET['toukou_id'];
        
        // 投稿情報を取得するクエリを準備
        $stmt = $pdo->prepare("
            SELECT t.*, a.aikon as user_aikon, a.display_name 
            FROM Toukou t
            JOIN Account a ON t.toukou_mei = a.user_name
            WHERE t.toukou_id = :toukou_id
        ");
        $stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // 結果を取得
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($post) {
            // フォロー状態を確認するクエリ
            $follow_stmt = $pdo->prepare("
                SELECT COUNT(*) as is_following
                FROM Follow
                WHERE applicant_name = :current_user_name AND approver_name = :post_user_name AND zyoukyou = 1
            ");
            $follow_stmt->bindParam(':current_user_name', $current_user_name, PDO::PARAM_STR);
            $follow_stmt->bindParam(':post_user_name', $post['user_name'], PDO::PARAM_STR);
            $follow_stmt->execute();
            $follow_status = $follow_stmt->fetch(PDO::FETCH_ASSOC);
            $is_following = $follow_status['is_following'] > 0;
            
            // コメントを取得するクエリ
            $comment_stmt = $pdo->prepare("
                SELECT c.*, a.aikon 
                FROM Comment c
                JOIN Account a ON c.account_mei = a.user_name
                WHERE c.toukou_id = :toukou_id
                ORDER BY c.comment_id
            ");
            $comment_stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
            $comment_stmt->execute();
            $comments = $comment_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <title>投稿表示画面</title>
</head>
<body>
    <h1 class="syumitter1">Syumitter</h1>

    <div class="post-container">
        <div class="user-info">
            <img src="<?php echo htmlspecialchars($post['user_aikon']); ?>" alt="ユーザーアイコン">
            <span><?php echo htmlspecialchars($post['display_name']); ?></span>
            <?php if ($is_following): ?>
                <button>フォロー中</button>
            <?php else: ?>
                <form action="follow.php" method="post">
                    <input type="hidden" name="approver_name" value="<?php echo htmlspecialchars($post['user_name']); ?>">
                    <button type="submit">フォローする</button>
                </form>
            <?php endif; ?>
        </div>
        <?php if (!empty($post['contents'])): ?>
            <div class="post-content">
                <?php
                // 動画または画像の表示
                if (strpos($post['contents'], '.mp4') !== false) {
                    echo '<video controls><source src="' . htmlspecialchars($post['contents']) . '" type="video/mp4"></video>';
                } else {
                    echo '<img src="' . htmlspecialchars($post['contents']) . '" alt="投稿画像">';
                }
                ?>
            </div>
        <?php endif; ?>
        <div class="interaction-buttons">
            <button>いいね <?php echo htmlspecialchars($post['likes']); ?></button>
            <button>コメント <?php echo htmlspecialchars($post['comments']); ?></button>
        </div>
        <div class="post-date">
            <?php
            // 投稿日時の表示
            $timestamp = strtotime($post['toukou_datetime']);
            echo date('Y年m月d日', $timestamp) . ' ' . date('l', $timestamp);
            ?>
        </div>
        <div class="hashtags">
            <?php
            // ハッシュタグの表示
            $tags = array($post['tag_id1'], $post['tag_id2'], $post['tag_id3']);
            foreach ($tags as $tag_id) {
                if ($tag_id) {
                    // タグIDからタグ名を取得して表示
                    $tag_stmt = $pdo->prepare("SELECT tag_name FROM Tag WHERE tag_id = :tag_id");
                    $tag_stmt->bindParam(':tag_id', $tag_id);
                    $tag_stmt->execute();
                    $tag = $tag_stmt->fetch(PDO::FETCH_ASSOC);
                    if ($tag) {
                        echo '#' . htmlspecialchars($tag['tag_name']) . ' ';
                    }
                }
            }
            ?>
        </div>
        <div class="caption">
            <?php echo htmlspecialchars($post['explain']); ?>
        </div>
        
        <div class="comments">
            <h2>コメント</h2>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <div class="comment-user-info">
                        <img src="<?php echo htmlspecialchars($comment['aikon']); ?>" alt="アイコン">
                        <span><?php echo htmlspecialchars($comment['account_mei']); ?></span>
                    </div>
                    <div class="comment-content">
                        <?php echo htmlspecialchars($comment['naiyou']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>

<?php
        } else {
            echo "投稿が見つかりませんでした";
        }
    } else {
        echo "投稿IDが指定されていません";
    }
} catch (PDOException $e) {
    die("エラー: " . $e->getMessage());
}
?>
