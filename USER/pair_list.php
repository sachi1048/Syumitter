<?php 
const SERVER = 'mysql301.phy.lolipop.lan';
const DBNAME = 'LAA1517472-syumitta';
const USER = 'LAA1517472';
const PASS = 'kitagawa';

// MySQLデータベースに接続

try {
    $connect = new PDO('mysql:host='.SERVER.';dbname='.DBNAME.';charset=utf8', USER, PASS);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

//ペアチャットの取得 (PDOを使用)
try {
    $sql = "
    SELECT f1.applicant_name AS user1, f2.approver_name AS user2
    FROM Follow f1
    INNER JOIN Follow f2 ON f1.applicant_name = f2.approver_name AND f1.approver_name = f2.applicant_name
    WHERE f1.zyoukyou = 1;
    ";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
//$connect = new PDO('mysql:host='.SERVER.';dbname='.DBNAME.';charset=utf8', USER, PASS);


 ?>
<?php
//$conn = new mysqli(SERVER, USER, PASS, DBNAME);


//if ($conn->connect_error) {
    //die("Connection failed: " . $conn->connect_error);
//}

//ペアチャットの取得
//$sql = "
//SELECT f1.applicant_name AS user1, f2.approver_name AS user2
//FROM Follow f1
//INNER JOIN Follow f2 ON f1.applicant_name = f2.approver_name AND f1.approver_name = f2.applicant_name
//WHERE f1.zyoukyou = 1;

//";
//AND f2.zyoukyou = 1
//$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <title>Syumitter</title>
    
    
    <title>ペアチャット一覧画面</title>
</head>
<body>
<div class="container">
<div class="header">Syumitter</div>
        <ul class="chat-list">
            <?php while($row = $result->fetch_assoc()): ?>
            <li class="chat-item">
            <li class="chat-item">
            <div class="info">
                <div><?php echo htmlspecialchars($row['user1']); ?> & <?php echo htmlspecialchars($row['user2']); ?></div>
                <div>ペアチャット</div>
            </div>
            </li>
            <?php endwhile; ?>
        </ul>
    </div>
        
</body>
</html>