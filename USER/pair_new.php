<?php session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);
$user_name = $_SESSION['user']['user_name'];
$display_name = $_SESSION['user']['display_name'];

$member = $_POST['member'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    if ($member) {
        try {
            // トランザクションの開始
            $pdo->beginTransaction();

            // 既存のペアチャットを確認
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM Pair_chat WHERE user1 = ? AND user2 = ?');
            $stmt->execute([$user_name, $member]);
            $existingCount = $stmt->fetchColumn();

            if ($existingCount > 0) {
                throw new Exception('既にペアチャットを作っている相手です。');
            }

            // テーブルのデータ件数を取得
            $stmtt = $pdo->query('SELECT COUNT(*) FROM Pair_chat');
            $count = $stmtt->fetchColumn();

            // データ件数を利用して新しい値を計算
            $newValue = $count / 2;

            // 1つ目のペアチャット挿入
            $stmt = $pdo->prepare('INSERT INTO Pair_chat VALUES (?, ?, ?,default)');
            $stmt->execute([$newValue, $user_name, $member]);

            // 2つ目のペアチャット挿入
            $stmt2 = $pdo->prepare('INSERT INTO Pair_chat VALUES (?, ?, ?,default)');
            $stmt2->execute([$newValue, $member, $user_name]);

            // トランザクションのコミット
            $pdo->commit();

            header('Location: pair_list.php'); // 成功時にリダイレクト
            exit;
        } catch (Exception $e) {
            // トランザクションのロールバック
            $pdo->rollBack();
            echo $e->getMessage();
        }
    } else {
        echo 'ペアを選択してください。';
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/menu.css">
    <title>新規ペアチャット作成画面</title>
</head>
<body>
    <h1 class="h1-2">Syumitter</h1>
        <a href="pair_list.php">
            <span class="btn-mdr2"></span>
        </a>

    <div class="frame">
        <h2>新規ペアチャット作成</h2>
        <br>
        <?php if ($member): ?>
            <?php
                $stmt = $pdo->prepare('SELECT * FROM Account WHERE user_name = ?');
                $stmt->execute([$member]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row):
            ?>
                <div class="aikon">
                    <img src="img/aikon/<?php echo htmlspecialchars($row['aikon'], ENT_QUOTES, 'UTF-8'); ?>" alt="アイコン" class="maru2">
                    <br>
                    <span><?php echo htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <form action="pair_new.php" method="post" enctype="multipart/form-data">
                <input class="btn-tag" type="button" onclick="location.href='member-sentaku2.php'" value="ペア選択">
            </form>
        <?php endif; ?>
        <br>
        <?php if ($member): ?>
            <form action="pair_new.php" method="post">
                <input type="hidden" name="member" value="<?php echo htmlspecialchars($member, ENT_QUOTES, 'UTF-8'); ?>">
                <button class="nextbutton" type="submit" name="create">作成</button>
            </form>
        <?php endif; ?>
    </div>
        
</body>
</html>

