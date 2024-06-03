<?php
const SERVER = 'mysql301.phy.lolipop.lan';
const DBNAME = 'LAA1517472-syumitta';
const USER = 'LAA1517472';
const PASS = 'kitagawa';

// MySQLデータベースに接続
$connect = new PDO('mysql:host='.SERVER.';dbname='.DBNAME.';charset=utf8', USER, PASS);


?>
<?php
    try {
        $conn = new PDO($connect, USER, PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // グループチャットの取得
        $sql = "
        SELECT g.group_id, g.group_mei, g.aikon, COUNT(gm.member) AS member_count
        FROM `Group` g
        LEFT JOIN Group_member gm ON g.group_id = gm.group_id
        LEFT JOIN Follow f ON g.group_id = f.follow_id
        WHERE f.zyoukyou = 1
        GROUP BY g.group_id, g.group_mei, g.aikon;
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/group_list.css">
    <title>Syumitter</title>
    
    <title>グループチャット一覧画面</title>
</head>
<body>
<div class="container">
        <div class="syumitter1">Syumitter</div>
        <ul class="chat-list">
            <?php foreach ($result as $row): ?>
            <li class="chat-item">
                <img src="<?php echo htmlspecialchars($row['aikon']); ?>" alt="Group Icon">
                <div class="info">
                    <div><?php echo htmlspecialchars($row['group_mei']); ?></div>
                    <div>メンバー: <?php echo $row['member_count']; ?></div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>

<?php
$conn = null; // データベース接続を閉じる
?>