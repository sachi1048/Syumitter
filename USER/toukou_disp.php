<?php
require 'db-connect.php';

try {
    // データベースに接続
    $pdo = new PDO($connect, USER, PASS);
    // エラーモードを例外モードに設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // URLパラメータから投稿IDを取得
    $toukou_id = $_GET['toukou_id'];
    
    // 投稿情報を取得するクエリを準備
    $stmt = $pdo->prepare("SELECT * FROM Toukou WHERE toukou_id = :toukou_id");
    $stmt->bindParam(':toukou_id', $toukou_id, PDO::PARAM_INT); // もしくはPDO::PARAM_STR
    $stmt->execute();

    
    // 結果を取得
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($post) {
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
            <img src="<?php echo $post['avatar']; ?>" alt="ユーザーアバター">
            <span><?php echo $post['toukou_mei']; ?></span>
            <button>フォロー</button>
        </div>
        <?php if (!empty($post['contents'])): ?>
            <div class="post-content">
                <?php
                // 動画または画像の表示
                if (strpos($post['contents'], '.mp4') !== false) {
                    echo '<video controls><source src="' . $post['contents'] . '" type="video/mp4"></video>';
                } else {
                    echo '<img src="' . $post['contents'] . '" alt="投稿画像">';
                }
                ?>
            </div>
        <?php endif; ?>
        <div class="interaction-buttons">
            <button>いいね <?php echo $post['likes']; ?></button>
            <button>コメント <?php echo $post['comments']; ?></button>
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
                // タグIDからタグ名を取得して表示
                $tag_stmt = $pdo->prepare("SELECT tag_name FROM Tag WHERE tag_id = :tag_id");
                $tag_stmt->bindParam(':tag_id', $tag_id);
                $tag_stmt->execute();
                $tag = $tag_stmt->fetch(PDO::FETCH_ASSOC);
                if ($tag) {
                    echo '#' . $tag['tag_name'] . ' ';
                }
            }
            ?>
        </div>
        <div class="caption">
            <?php echo $post['explain']; ?>
        </div>
    </div>

</body>
</html>

<?php
    } else {
        echo "投稿が見つかりませんでした";
    }
} catch (PDOException $e) {
    die("エラー: " . $e->getMessage());
}
?>
