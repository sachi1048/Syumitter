<?php
ob_start();
session_start();
require 'db-connect.php';

$current_user_name = isset($_SESSION['user']['user_name']) ? $_SESSION['user']['user_name'] : null; // ログインしているユーザーの名前をセッションから取得
$aikon = $_SESSION['user']['aikon'];

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['toukou_id'])) {
        $toukou_id = $_GET['toukou_id'];

        // いいね数の取得
        $like_stmt = $pdo->prepare("SELECT COUNT(*) as like_count FROM Comment WHERE toukou_id = :toukou_id AND comment_type = 1");
        $like_stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
        $like_stmt->execute();
        $like_result = $like_stmt->fetch(PDO::FETCH_ASSOC);
        $like_count = $like_result ? $like_result['like_count'] : 0;

        // 投稿の取得
        $stmt = $pdo->prepare("
            SELECT t.*, a.aikon as user_aikon, a.user_name
            FROM Toukou t
            JOIN Account a ON t.toukou_mei = a.user_name
            WHERE t.toukou_id = :toukou_id
        ");
        $stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        // コメント数の取得
        $comment_count_query = "SELECT COUNT(*) AS comment_count FROM Comment WHERE toukou_id = :toukou_id AND comment_type = 0";
        $comment_stmt = $pdo->prepare($comment_count_query);
        $comment_stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT);
        $comment_stmt->execute();
        $comment_count_result = $comment_stmt->fetch(PDO::FETCH_ASSOC);
        $comment_count = $comment_count_result ? $comment_count_result['comment_count'] : 0;

        // タグ名の取得
        function getTagName($pdo, $tag_id) {
            if ($tag_id) {
                $tag_stmt = $pdo->prepare("SELECT tag_mei, tag_color1, tag_color2, tag_color3 FROM Tag WHERE tag_id = :tag_id");
                $tag_stmt->bindParam(':tag_id', $tag_id, PDO::PARAM_INT);
                $tag_stmt->execute();
                $tag = $tag_stmt->fetch(PDO::FETCH_ASSOC);
                if ($tag) {
                    return [
                        'tag_mei' => htmlspecialchars($tag['tag_mei']),
                        'tag_color1' => $tag['tag_color1'],
                        'tag_color2' => $tag['tag_color2'],
                        'tag_color3' => $tag['tag_color3']
                    ];
                }
            }
            return null;
        }
        
        // タグ名の取得
        $tags = [
            getTagName($pdo, isset($post['tag_id1']) ? $post['tag_id1'] : null),
            getTagName($pdo, isset($post['tag_id2']) ? $post['tag_id2'] : null),
            getTagName($pdo, isset($post['tag_id3']) ? $post['tag_id3'] : null)
        ];

        
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
    <link rel="stylesheet" href="CSS/menu.css">
    <link rel="stylesheet" href="CSS/toukou_disp2.css">
    <link rel="stylesheet" href="CSS/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <title>投稿表示画面</title>
<script>
$(document).ready(function(){
    $(document).on('click', '.follow-button', function(e){
        e.preventDefault();
        var button = $(this);
        var approverName = button.data('approver-name'); // ボタンのデータ属性からフォロー相手のユーザー名を取得

        $.ajax({
            url: 'api.php',
            type: 'POST',
            data: {
                approver_name: approverName
                // 必要に応じてデータをここに追加
            },
            success: function(response) {
                console.log('APIコール成功');
                console.log(response);

                // ボタンのテキストとクラスを切り替える
                if (button.hasClass('following')) {
                    button.removeClass('following').text('フォローする');
                } else {
                    button.addClass('following').text('フォロー中');
                }
            },
            error: function(xhr, status, error) {
                console.error('APIコール失敗');
                console.error(status, error);
            }
        });
    });
});

</script>


</head>
<body>
<h1 class="h1-2">Syumitter</h1>  
<a href="javascript:history.back()" class="btn-mdr2"></a>


    <?php
    foreach($stmt as $row){
        $sql2=$pdo->query('select * from Account where user_name="'.$row['applicant_name'].'"');
        foreach($sql2 as $row2){
           
                        if($row['zyoukyou'] == 1){
                            echo '<button id="follow" class="btn-follow1">フォロー中</button>';
                        }else{
                            echo '<button id="follow" class="btn-follow2">フォローする</button>';
                        }

        }

    }
    if (!empty($post)) {
        $follow_stmt = $pdo->prepare("
            SELECT COUNT(*) as is_following 
            FROM Follow 
            WHERE applicant_name = :current_user_name AND approver_name = :toukou_mei
        ");
        $follow_stmt->bindParam(':current_user_name', $current_user_name, PDO::PARAM_STR);
        $follow_stmt->bindParam(':toukou_mei', $post['toukou_mei'], PDO::PARAM_STR);
        $follow_stmt->execute();
        $follow_result = $follow_stmt->fetch(PDO::FETCH_ASSOC);
        $is_following = $follow_result ? $follow_result['is_following'] : 0;
    }

      // いいねの状態を取得
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
                        ?>
    <?php if (!empty($post)): ?>
    <div class="post-container">
        <div class="user-info">
            <div class="aikon">
                <img src="<?php echo 'img/aikon/' . htmlspecialchars($post['user_aikon']); ?>" alt="アイコン" class="user-icon">
            </div>
            <!-- <span><?php echo htmlspecialchars($post['display_name']); ?></span> -->
            <?php echo '<a href="profile.php?user_name=', $post['user_name'], '" style="Text-decoration:none; color:#000000;">
                <h2>', $post['user_name'], '</h2>
                </a>';
            ?>
            <?php if ($current_user_name === $post['toukou_mei']): ?>
                <form action="toukou_delete.php" method="post" class="user-action-form">
                    <input type="hidden" name="toukou_id" value="<?php echo htmlspecialchars($post['toukou_id']); ?>">
                    <button type="submit" name="delete_post" class="delete-button">×削除する</button>
                </form>
            <?php else: ?>
                <form action="" method="post" class="user-action-form">
                    <button type="submit" name="follow" class="follow-button <?php echo $is_following ? 'following' : ''; ?>">
                        <?php echo $is_following ? 'フォロー中' : 'フォローする'; ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        

                <?php
                if (isset($_POST['comment'])) {
                    $post['comments']++;
                }
                ?>


            <?php if (!empty($post['contents'])): ?>
                <div class="post-content">
                    <?php if (strpos($post['contents'], '.mp4') !== false): ?>
                        <video controls><source src="img/toukou/<?php echo htmlspecialchars($post['contents']); ?>" type="video/mp4"></video>
                    <?php else: ?>
                        <img src="img/toukou/<?php echo htmlspecialchars($post['contents']); ?>" alt="投稿画像">
                    <?php endif; ?>
                    <div class="interaction-buttons">
                        <form action="touroku_like.php" method="post" class="like-form">
                            <input type="hidden" name="toukou_id" value="<?php echo $post['toukou_id']; ?>">
                            <button type="submit" name="like" class="like-button <?php echo $liked ? 'liked' : ''; ?>">
                                <i class="fas fa-heart"></i><?php echo htmlspecialchars($like_count); ?>
                                
        
                            </button>
                        </form>
                    
                    
                        <form action="toukou_comment.php" method="get" class="comment-form">
                        <input type="hidden" name="toukou_id" value="<?php echo $post['toukou_id']; ?>">
                            <button type="submit" name="comment" class="comment-button">
                            <i class="fas fa-comment"></i>
                            <span class="comment-count"><?php echo htmlspecialchars($comment_count); ?></span>
                            </button>
                        </form>

                    </div>
                </div>

            

            <div class="post-details">
                <div class="post-title">
                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                </div>
                <div class="post-tags">
    <?php foreach ($tags as $tag): ?>
        <?php if ($tag): ?>
            <div class="s-tag" style="background: rgb(<?php echo $tag['tag_color1']; ?>, <?php echo $tag['tag_color2']; ?>, <?php echo $tag['tag_color3']; ?>);">
                <?php echo $tag['tag_mei']; ?>
            </div>
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

    </br>
    </br>
    </br>
    </br>
    </br>
    <footer><?php require 'menu.php'; ?></footer>
</body>
</html>