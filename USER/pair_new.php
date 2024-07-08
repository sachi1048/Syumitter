<?php session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);
$user_name = $_SESSION['user']['user_name'];
$display_name = $_SESSION['user']['display_name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member = $_POST['member'] ?? null;
    if ($member) {
        try {
            // トランザクションの開始
            $pdo->beginTransaction();

            // 1つ目のペアチャット挿入
            $stmt = $pdo->prepare('INSERT INTO Pair_chat (user1, user2) VALUES (?, ?)');
            $stmt->execute([$user_name, $member]);

            // 直前の挿入で生成されたchat_idを取得
            $chat_id = $pdo->lastInsertId();

            // 2つ目のペアチャット挿入
            $stmt2 = $pdo->prepare('INSERT INTO Pair_chat (chat_id, user1, user2) VALUES (?, ?, ?)');
            $stmt2->execute([$chat_id, $member, $user_name]);

            // トランザクションのコミット
            $pdo->commit();

            header('Location: pair_list.php'); // 成功時にリダイレクト
            exit;
        } catch (PDOException $e) {
            // トランザクションのロールバック
            $pdo->rollBack();
            echo '既にペアチャットを作っている相手です。' . $e->getMessage();
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
        <a href="group_list.php">
            <span class="btn-mdr2"></span>
        </a>

    <div class="frame">
        <h2>新規ペアチャット作成</h2>
        <br>
        <form action="pair_list.php" method="post" enctype="multipart/form-data">

        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($member)) {
                $member = $_POST['member'] ?? null;
                if ($member) {
                    $stmt = $pdo->prepare('SELECT * FROM Account WHERE user_name = ?');
                    $stmt->execute([$member]);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($row) {
                        echo '<tr><td>';
                        echo '<div class="aikon">
                                <img src="img/aikon/', htmlspecialchars($row['aikon']), '" alt="アイコン" class="maru2">
                            </div></td>';
                        echo '<td><span>', htmlspecialchars($row['user_name']), '</span></td>';
                        echo '</tr>';
                    }
                }
            }
        ?>

            <br>
            <button class="btn-tag" type="button" onclick="location.href='member-sentaku2.php'">ペア選択</button>
            <br>
            <button class="nextbutton" type="submit">作成</button>
        </form>
    </div>

</body>
</html>

//選択した後、pair_listにとんでしまう。dbにあるのにlistに載らない