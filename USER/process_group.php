<?php session_start(); ?>
<?php require 'db-connect.php'; ?>

<?php
$pdo = new PDO($connect, USER, PASS);
$user_name = $_SESSION['user']['user_name'];
$display_name = $_SESSION['user']['display_name'];
$aikon = $_SESSION['user']['aikon'];

// フォームデータを受け取る
$group_name = $_POST['group_name'];
$members = $_POST['members'] ?? [];

try {
    // トランザクション開始
    $pdo->beginTransaction();

    // グループの追加
    $stmt = $pdo->prepare('INSERT INTO Groups (group_name, created_by) VALUES (?, ?)');
    $stmt->execute([$group_name, $user_name]);

    // 新しいグループのIDを取得
    $group_id = $pdo->lastInsertId();

    // メンバーの追加
    $stmt = $pdo->prepare('INSERT INTO GroupMembers (group_id, user_name) VALUES (?, ?)');
    foreach ($members as $member) {
        $stmt->execute([$group_id, $member]);
    }

    // トランザクション完了
    $pdo->commit();

    // 成功時のリダイレクト
    header('Location: group_new.php');
    exit();
} catch (Exception $e) {
    // エラー時のロールバック
    $pdo->rollBack();
    echo "エラーが発生しました: " . $e->getMessage();
}

//Insertするものを記入する
