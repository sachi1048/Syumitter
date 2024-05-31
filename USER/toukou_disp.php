<?php
require 'db-connect.php';

session_start();
$current_user_name = $_SESSION['user']['user_name']; // ログインしているユーザーの名前をセッションから取得

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
            SELECT t.*, a.aikon as user_aikon, a.display_name, 
                   (SELECT COUNT(*) FROM Comment c WHERE c.toukou_id = t.toukou_id AND c.comment_type = 1) as like_count, 
                   (SELECT COUNT(*) FROM Comment c WHERE c.toukou_id = t.toukou_id) as comments
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
            $follow_stmt->bindParam(':post_user_name', $post['toukou_mei'], PDO::PARAM_STR);
            $follow_stmt->execute();
            $follow_status = $follow_stmt->fetch(PDO::FETCH_ASSOC);
            $is_following = $follow_status['is_following'] > 0;
            
            // フォローするボタンが押下された場合
            if (isset($_POST['follow'])) {
                // フォロー情報を追加
                $insert_follow_stmt = $pdo->prepare("
                    INSERT INTO Follow (applicant_name, approver_name, zyoukyou)
                    VALUES (:applicant_name, :approver_name, 1)
                ");
                $insert_follow_stmt->bindParam(':applicant_name', $current_user_name, PDO::PARAM_STR);
                $insert_follow_stmt->bindParam(':approver_name', $post['toukou_mei'], PDO::PARAM_STR);
                $insert_follow_stmt->execute();
                
                // 投稿主のフォロワー数を1増やす
                $update_follower_count_stmt = $pdo->prepare("
                    UPDATE Account
                    SET follower_count = follower_count + 1
                    WHERE user_name = :user_name
                ");
                $update_follower_count_stmt->bindParam(':user_name', $post['toukou_mei'], PDO::PARAM_STR);
                $update_follower_count_stmt->execute();
                
                // ページをリロードする
                header("Location: {$_SERVER['REQUEST_URI']}");
                exit();
            }

            // タグ名の取得クエリ
            function getTagName($pdo, $tag_id) {
                if ($tag_id) {
                    $tag_stmt = $pdo->prepare("SELECT tag_mei FROM Tag WHERE tag_id = :tag_id");
                    $tag_stmt->bindParam(':tag_id', $tag_id, PDO::PARAM_INT);
                    $tag_stmt->execute();
                    $tag = $tag_stmt->fetch(PDO::FETCH_ASSOC);
                    return $tag ? htmlspecialchars($tag['tag_mei']) : null;
                }
                return null;
            }

            $tags = [
                getTagName($pdo, $post['tag_id1']),
                getTagName($pdo, $post['tag_id2']),
                getTagName($pdo, $post['tag_id3'])
            ];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/toukou_disp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>投稿表示画面</title>
</head>
<body>
    <h1 class="syumitter1">Syumitter</h1>

    <div class="post-container">
        <div class="user-info">
            <img src="<?php echo htmlspecialchars($post['user_aikon']); ?>" alt="ユーザーアイコン" class="user-icon">
            <span><?php echo htmlspecialchars($post['display_name']); ?></span>
            <?php if ($is_following): ?>
                <button class="follow-button" disabled>フォロー中</button>
            <?php else: ?>
                <form action="" method="post">
                    <button type="submit" name="follow" class="follow-button">フォローする</button>
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
                <div class="interaction-buttons">
                    <button class="like-button">
                        <i class="fas fa-heart"></i>
                        <div class="like-count"><?php echo htmlspecialchars($post['like_count']); ?></div>
                    </button>
                    <button class="comment-button">
                        <i class="fas fa-comment"></i>
                        <div class="comment-count"><?php echo htmlspecialchars($post['comments']); ?></div>
                    </button>
                </div>
            </div>
        <?php endif; ?>
        <div class="post-details">
            <div class="post-title">
                <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            </div>
            <div class="post-tags">
                <?php foreach ($tags as $tag): ?>
                    <?php if ($tag): ?>
                        <span class="tag">#<?php echo $tag; ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="post-date">
            <?php
            // 日本語の曜日を表示するための配列
            $weekdays = ["日", "月", "火", "水", "木", "金", "土"];
            $timestamp = strtotime($post['toukou_datetime']);
            $dayOfWeek = $weekdays[date('w', $timestamp)];
            echo date('Y年m月d日', $timestamp) . ' (' . $dayOfWeek . ')';
            ?>
        </div>
        <div class="explain">
            <?php echo htmlspecialchars($post['explain']); ?>
        </div>
        <div class="comments">
            <!-- <h2>コメント</h2> -->
            <?php
            // コメントの表示クエリ
            $comment_stmt = $pdo->prepare("
    SELECT c.*, a.aikon, a.display_name as account_mei
    FROM Comment c
    JOIN Account a ON c.account_mei = a.user_name
    WHERE c.toukou_id = :toukou_id
");

            $comment_stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
            $comment_stmt->execute();
            $comments = $comment_stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($comments as $comment): ?>
                <div class="comment">
                    <div class="comment-user-info">
                        <img src="<?php echo htmlspecialchars($comment['aikon']); ?>" alt="アイコン" class="comment-icon">
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
        echo"投稿IDが指定されていません";
    }
} catch (PDOException $e) {
    die("エラー: " . $e->getMessage());
}
?>
