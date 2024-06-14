<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<?php 

$pdo = new PDO($connect, USER, PASS);
$user_name = $_SESSION['user']['user_name'];


// <!-- フォローするとき -->
//     <!--　相互フォロー -->
//     <!-- 片方フォロー -->

// <!-- フォロー外す時 -->
//     <!-- 片方フォロー -->
//     <!-- 相互フォローでない -->

// AがBをフォロー  している時
// →applicant_nameの名前があるかどうか？


// if (AがBをフォローしてるとき){
//     delete AがB
//     if(相互かどうか)
//     update BのデータAを1→0
// }else{
//     if (BがAをフォロー中){
//         INSERT AがBを1
//         UPDATE approver_name applicant_name 0→1
//     }else{
//         INSERT AがBを0
//     }

// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

$a = $user_name; // フォローボタンを押したユーザー
$b = $_POST['approver_name']; // フォローされたユーザー

// AがBをフォローしているか確認
$sql = "SELECT * FROM Follow WHERE applicant_name = ? AND approver_name = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$a, $b]);
$result = $stmt->fetch();

if ($result !== false) {
    // AがBをフォローしている場合、削除する
    $deleteSql = "DELETE FROM Follow WHERE applicant_name = ? AND approver_name = ?";
    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteStmt->execute([$a, $b]);
    
    // BがAをフォローしているか確認
    $checkSql = "SELECT * FROM Follow WHERE applicant_name = ? AND approver_name = ? AND zyoukyou = 1";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$b, $a]);
    $checkResult = $checkStmt->fetch();
    
    if ($checkResult !== false) {
        // BがAをフォローしている場合、zyoukyouを0に更新
        $updateSql = "UPDATE Follow SET zyoukyou = 0 WHERE applicant_name = ? AND approver_name = ?";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([$b, $a]);
    }
} else {
    // AがBをフォローしていない場合
    $checkBSql = "SELECT * FROM Follow WHERE applicant_name = ? AND approver_name = ?";
    $checkBStmt = $pdo->prepare($checkBSql);
    $checkBStmt->execute([$b, $a]);
    $checkBResult = $checkBStmt->fetch();
    
    if ($checkBResult !== false) {
        // BがAをフォローしている場合、AがBをフォローし、BのAに対するzyoukyouを1に更新
        $insertSql = "INSERT INTO Follow (applicant_name, approver_name, zyoukyou) VALUES (?, ?, 1)";
        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->execute([$a, $b]);
        
        $updateBSql = "UPDATE Follow SET zyoukyou = 1 WHERE applicant_name = ? AND approver_name = ?";
        $updateBStmt = $pdo->prepare($updateBSql);
        $updateBStmt->execute([$b, $a]);
    } else {
        // BがAをフォローしていない場合、AがBをフォローし、zyoukyouを0に設定
        $insertSql = "INSERT INTO Follow (applicant_name, approver_name, zyoukyou) VALUES (?, ?, 0)";
        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->execute([$a, $b]);
    }
}

} else {
    // POSTリクエスト以外の場合の処理
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}



?>