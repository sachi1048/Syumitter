<?php require 'db-connect.php'; ?>
<?php
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// グループチャットの取得
$sql = "
    SELECT g.group_id, g.group_mei, g.aikon, COUNT(gm.member) as member_count
    FROM `Group` g
    LEFT JOIN Group_member gm ON g.group_id = gm.group_id
    GROUP BY g.group_id, g.group_mei, g.aikon
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <title>Syumitter</title>
    
    
    <title>グループチャット一覧画面</title>
</head>
<body>
<div class="container">
<div class="header">Syumitter</div>
        <ul class="chat-list">
            <?php while($row = $result->fetch_assoc()): ?>
            <li class="chat-item">
                <img src="<?php echo htmlspecialchars($row['aikon']); ?>" alt="Group Icon">
                <div class="info">
                    <div><?php echo htmlspecialchars($row['group_mei']); ?></div>
                    <div>メンバー: <?php echo $row['member_count']; ?></div>
                </div>
            </li>
            <?php endwhile; ?>
        </ul>
    </div>
        
</body>
</html>