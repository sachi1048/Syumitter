<?php
ob_start();
session_start();
require 'db-connect.php';

$current_user_name = $_SESSION['user']['user_name'];
$aikon = $_SESSION['user']['aikon'];

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['toukou_id'])) {
        $toukou_id = $_GET['toukou_id'];

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

        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post) {
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

            if (isset($_POST['follow'])) {
                $insert_follow_stmt = $pdo->prepare("
                    INSERT INTO Follow (applicant_name, approver_name, zyoukyou)
                    VALUES (:applicant_name, :approver_name, 1)
                ");
                $insert_follow_stmt->bindParam(':applicant_name', $current_user_name, PDO::PARAM_STR);
                $insert_follow_stmt->bindParam(':approver_name', $post['toukou_mei'], PDO::PARAM_STR);
                $insert_follow_stmt->execute();

                $update_follower_count_stmt = $pdo->prepare("
                    UPDATE Account
                    SET follower_count = follower_count + 1
                    WHERE user_name = :user_name
                ");
                $update_follower_count_stmt->bindParam(':user_name', $post['toukou_mei'], PDO::PARAM_STR);
                $update_follower_count_stmt->execute();

                header("Location: {$_SERVER['REQUEST_URI']}");
                exit();
            }

            if (isset($_POST['unfollow'])) {
                $delete_follow_stmt = $pdo->prepare("
                    DELETE FROM Follow
                    WHERE applicant_name = :applicant_name AND approver_name = :approver_name
                ");
                $delete_follow_stmt->bindParam(':applicant_name', $current_user_name, PDO::PARAM_STR);
                $delete_follow_stmt->bindParam(':approver_name', $post['toukou_mei'], PDO::PARAM_STR);
                $delete_follow_stmt->execute();

                $update_follower_count_stmt = $pdo->prepare("
                    UPDATE Account
                    SET follower_count = follower_count - 1
                    WHERE user_name = :user_name
                ");
                $update_follower_count_stmt->bindParam(':user_name', $post['toukou_mei'], PDO::PARAM_STR);
                $update_follower_count_stmt->execute();

                header("Location: {$_SERVER['REQUEST_URI']}");
                exit();
            }

            if (isset($_POST['delete_post'])) {
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

                header("Location: user_posts.php");
                exit();
            }

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
    echo "エラー：" . $e->getMessage();
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/toukou_disp2.css">
    <link rel="stylesheet" href="CSS/all.min.css">

    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
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
                            <input type="hidden" name="toukou_id" value="<?php echo $post['toukou_id']; ?>">
                            <button type="submit" name="like" class="like-button">
                                <i class="far fa-heart"></i>
                                <span class="like-count"><?php echo htmlspecialchars($post['like_count']); ?></span>
                            </button>
                        </form><form action="toukou_comment.php" method="post" class="comment-form">
    <input type="hidden" name="toukou_id" value="<?php echo $post['toukou_id']; ?>">
    <button type="submit" name="comment" class="comment-button">
        <i class="fas fa-comment"></i>
        <span class="comment-count"><?php echo htmlspecialchars($post['comments']); ?></span>
    </button>
</form>

                    </div>
                </div>

                <!-- <?php -->
                // if (isset($_POST['comment'])) {
                    // $post['comments']++;
                // }
                // ?>

                <?php
                if (isset($_POST['like'])) {
                    $liked_stmt = $pdo->prepare("
                    SELECT COUNT(*) as liked
                    FROM Comment
                    WHERE toukou_id = :toukou_id AND account_mei = :current_user_name AND comment_type = 1
                ");
                $liked_stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
                $liked_stmt->bindParam(':current_user_name', $current_user_name, PDO::PARAM_STR);
                $liked_stmt->execute();
                $liked_status = $liked_stmt->fetch(PDO::FETCH_ASSOC);
                $liked = $liked_status['liked'] > 0;
                    if ($liked) {
                        $unlike_stmt = $pdo->prepare("
                            DELETE FROM Comment
                            WHERE toukou_id = :toukou_id AND account_mei = :current_user_name AND comment_type = 1
                        ");
                        $unlike_stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
                        $unlike_stmt->bindParam(':current_user_name', $current_user_name, PDO::PARAM_STR);
                        $unlike_stmt->execute();
                        $post['like_count']--;
                    } else {
                        $like_stmt = $pdo->prepare("
                            INSERT INTO Comment (toukou_id, account_mei, comment_type)
                            VALUES (:toukou_id, :current_user_name, 1)
                        ");
                        $like_stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
                        $like_stmt->bindParam(':current_user_name', $current_user_name, PDO::PARAM_STR);
                        $like_stmt->execute();
                        $post['like_count']++;
                    }
                }
                ?>

                <?php
                if (isset($_POST['comment'])) {
                    $post['comments']++;
                }
                ?>

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

            
        </div>
    <?php else: ?>
        <p>投稿が見つかりませんでした</p>
    <?php endif; ?>
</body>
</html>
