<?php
session_start();
require 'db-connect.php';

$current_user_name = $_SESSION['user']['user_name']; // ログインしているユーザーの名前をセッションから取得
$aikon = $_SESSION['user']['aikon'];

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

            // アンフォローボタンが押下された場合
            if (isset($_POST['unfollow'])) {
                // フォロー情報を削除
                $delete_follow_stmt = $pdo->prepare("
                    DELETE FROM Follow
                    WHERE applicant_name = :applicant_name AND approver_name = :approver_name
                ");
                $delete_follow_stmt->bindParam(':applicant_name', $current_user_name, PDO::PARAM_STR);
                $delete_follow_stmt->bindParam(':approver_name', $post['toukou_mei'], PDO::PARAM_STR);
                $delete_follow_stmt->execute();

                // 投稿主のフォロワー数を1減らす
                $update_follower_count_stmt = $pdo->prepare("
                    UPDATE Account
                    SET follower_count = follower_count - 1
                    WHERE user_name = :user_name
                ");
                $update_follower_count_stmt->bindParam(':user_name', $post['toukou_mei'], PDO::PARAM_STR);
                $update_follower_count_stmt->execute();

                // ページをリロードする
                header("Location: {$_SERVER['REQUEST_URI']}");
                exit();
            }

            // 投稿の削除
            if (isset($_POST['delete_post'])) {
                // 投稿と関連するコメントを削除
                $delete_comments_stmt = $pdo->prepare("
                    DELETE FROM Comment WHERE toukou_id = :toukou_id
                ");
                $delete_comments_stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
                $delete_comments_stmt->execute();

                $delete_post_stmt = $pdo->prepare("
                    DELETE FROM Toukou WHERE toukou_id = :toukou_id
                ");
                $delete_post_stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
                $delete_post_stmt->execute();

                // リダイレクト
                header("Location: user_posts.php"); // ユーザーの投稿一覧にリダイレクト
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
        }
    }

} catch (PDOException $e) {
    // エラーメッセージを表示して、デバッグを容易にする
    echo "エラー：" . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/toukou_disp2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>投稿表示画面</title>
</head>
<body>
    <h1 class="syumitter1">Syumitter</h1>

    <?php if (!empty($post)): ?>
        <div class="post-container">
            <div class="user-info">
                <div class="aikon">
                    <img src="<?php echo 'img/aikon/' . htmlspecialchars($post['user_aikon']); ?>" alt="アイコン" class="user-icon">
                </div>
                <span><?php echo htmlspecialchars($post['display_name']); ?></span>
                <?php if ($current_user_name === $post['toukou_mei']): ?>
                    <form action="" method="post" class="user-action-form">
                        <button type="submit" name="delete_post" class="delete-button">×削除する</button>
                    </form>
                <?php else: ?>
                    <form action="" method="post" class="user-action-form">
                        <button type="submit" name="follow" class="follow-button">
                            <?php echo $is_following ? 'フォロー中' : 'フォローする'; ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <?php if (!empty($post['contents'])): ?>
                <div class="post-content">
                    <?php if (strpos($post['contents'], '.mp4') !== false): ?>
                        <video controls><source src="img/toukou/<?php echo htmlspecialchars($post['contents']); ?>" type="video/mp4"></video>
                    <?php else: ?>
                        <img src="img/toukou/<?php echo htmlspecialchars($post['contents']); ?>" alt="投稿画像">
                    <?php endif; ?>
                    <div class="interaction-buttons">
    <form action="" method="post" class="like-form">
        <input type="hidden" name="post_id" value="<?php echo $post['toukou_id']; ?>">
        <button type="button" name="like" class="like-button">
        <i class="far fa-heart"></i>
        </button>
        <div class="like-count"><?php echo htmlspecialchars($post['like_count']); ?></div>
    </form>
    <button class="comment-button">
                <i class="fas fa-comment"></i>
                <div class="comment-count"><?php echo htmlspecialchars($post['comments']); ?></div>
            </button>
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

            <div class="post-date-right">
                <?php
                $weekdays = ["日", "月", "火", "水", "木", "金", "土"];
                $timestamp = strtotime($post['toukou_datetime']);
                $dayOfWeek = $weekdays[date('w', $timestamp)];
                echo date('Y年m月d日', $timestamp) . ' (' . $dayOfWeek . ')';
                ?>
            </div>

            <div class="explain">
                <?php echo htmlspecialchars($post['setumei']); ?>
            </div>

            <div class="comments">
                <?php
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
                            <img src="<?php echo 'img/aikon/' . htmlspecialchars($comment['aikon']); ?>" alt="アイコン" class="user-icon">
                            <span><?php echo htmlspecialchars($comment['account_mei']); ?></span>
                        </div>
                        <div class="comment-content">
                            <?php echo htmlspecialchars($comment['naiyou']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <p>投稿が見つかりませんでした</p>
    <?php endif; ?>
</body>
</html>
