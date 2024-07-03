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
            SELECT t.*, a.aikon as user_aikon, a.display_name
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
                $tag_stmt = $pdo->prepare("SELECT tag_mei FROM Tag WHERE tag_id = :tag_id");
                $tag_stmt->bindParam(':tag_id', $tag_id, PDO::PARAM_INT);
                $tag_stmt->execute();
                $tag = $tag_stmt->fetch(PDO::FETCH_ASSOC);
                return $tag ? htmlspecialchars($tag['tag_mei']) : null; // タグが見つからなかった場合はnullを返す
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
    <!-- <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css"> -->
    <title>投稿表示画面</title>


</head>
<body>
<footer><?php require 'menu.php'; ?></footer>
<h1 class="h1-2">Syumitter</h1>  
<a href="profile.php?user_name=<?php echo htmlspecialchars($current_user_name, ENT_QUOTES, 'UTF-8'); ?>">
    <span class="btn-mdr2"></span>
</a>

    <?php

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
                        ?>
    <?php if (!empty($post)): ?>
    <div class="post-container">
        <div class="user-info">
            <div class="aikon">
                <img src="<?php echo 'img/aikon/' . htmlspecialchars($post['user_aikon']); ?>" alt="アイコン" class="user-icon">
            </div>
            <span><?php echo htmlspecialchars($post['display_name']); ?></span>
            <?php if ($current_user_name === $post['toukou_mei']): ?>
                <form action="toukou_delete.php" method="post" class="user-action-form">
                    <input type="hidden" name="toukou_id" value="<?php echo htmlspecialchars($post['toukou_id']); ?>">
                    <button type="submit" name="delete_post" class="delete-button">×削除する</button>
                </form>
            <?php else: ?>
               
                <div class="btn-follow0">
                <?php 
                    $user_name = $_SESSION['user']['user_name'];
                    $tt=$pdo->prepare('select toukou_mei from Toukou where toukou_id=?');
                    $tt->execute([$_GET['toukou_id']]);
                    foreach($tt as $ttt){

                    $sqlll=$pdo->prepare('select * from Follow where applicant_name=?');
                    $sqlll->execute([$user_name]);
                    foreach($sqlll as $ff){
                        if($ff['applicant_name']==$user_name && $ff['approver_name'] == $ttt['toukou_mei']){
                            echo '<button id="follow" class="btn-follow1">フォロー中</button>';
                        }else{
                            echo '<button id="follow" class="btn-follow2">フォローする</button>';
                        }
                    }

                    }

                ?>
                </div>

            <?php endif; ?>
        </div>
        <?php endif; ?>

        

                <?php
                if (isset($_POST['comment'])) {
                    $post['comments']++;
                }
                ?>

           <!-- cc -->

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
                            <button type="submit" name="like" class="like-button">
                                <i class="far fa-heart"></i><?php echo htmlspecialchars($like_count); ?>
                                
        
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
                <?php
                $sql4=$pdo->prepare('select * from User_tag where user_name=?');
    $sql4->execute([$current_user_name]);
    foreach($sql4 as $row4){
        $sqltag=$pdo->prepare('select * from Tag where tag_id=?');
        $sqltag->execute([$row4['tag_id']]);
        foreach($sqltag as $tag){
            echo '<div class="s-tag" style="background: rgb(', $tag['tag_color1'], ',', $tag['tag_color2'], ',', $tag['tag_color3'], '">', $tag['tag_mei'], '</div>';
        }
        
    }
    ?>
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
<div>    <?php var_dump($tt); ?>
    </div>
    <div>
    <?php var_dump($ttt); ?>
    </div>
</body>

<script>
$(document).ready(function(){
        $('#follow').on('click', function(){
             // PHP変数をJavaScript変数に変換
             var approverName = "<?php echo $ttt['toukou_mei']; ?>";
            $.ajax({
                url: 'api.php',
                type: 'POST',
                data: {
                    approver_name: approverName
                    // 必要に応じてデータをここに追加
                },
                success: function(response) {
                    // 成功時の処理
                    console.log('API call successful.');
                    console.log(response);
                    
                    // ボタンのテキストとクラスを切り替える
                    $('#follow').toggleClass('btn-follow1 btn-follow2');
                    if($('#follow').hasClass('btn-follow1')) {
                        $('#follow').text('フォロー中');
                    } else {
                        $('#follow').text('フォローする');
                    }
                },
                error: function(xhr, status, error) {
                    // エラー時の処理
                    console.error('API call failed.');
                    console.error(status, error);
                }
            });
        });
    });

</script>
</html>
