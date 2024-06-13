<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<?php echo "完了"

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
$b = $_POST['approver_name']; // フォローボタンを押されたユーザー

// AがBをフォローしているか確認
$sql = "SELECT * FROM Follow WHERE applicant_name = ? AND approver_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $a, $b);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // AがBをフォローしている場合、削除する
    $deleteSql = "DELETE FROM Follow WHERE applicant_name = ? AND approver_name = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("ss", $a, $b);
    $deleteStmt->execute();
    
    // BがAをフォローしているか確認
    $checkSql = "SELECT * FROM Follow WHERE applicant_name = ? AND approver_name = ? AND zyoukyou = 1";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ss", $b, $a);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        // BがAをフォローしている場合、zyoukyouを0に更新
        $updateSql = "UPDATE Follow SET zyoukyou = 0 WHERE applicant_name = ? AND approver_name = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ss", $b, $a);
        $updateStmt->execute();
    }
} else {
    // AがBをフォローしていない場合
    $checkBSql = "SELECT * FROM Follow WHERE applicant_name = ? AND approver_name = ?";
    $checkBStmt = $conn->prepare($checkBSql);
    $checkBStmt->bind_param("ss", $b, $a);
    $checkBStmt->execute();
    $checkBResult = $checkBStmt->get_result();
    
    if ($checkBResult->num_rows > 0) {
        // BがAをフォローしている場合、AがBをフォローし、BのAに対するzyoukyouを1に更新
        $insertSql = "INSERT INTO Follow (applicant_name, approver_name, zyoukyou) VALUES (?, ?, 1)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ss", $a, $b);
        $insertStmt->execute();
        
        $updateBSql = "UPDATE Follow SET zyoukyou = 1 WHERE applicant_name = ? AND approver_name = ?";
        $updateBStmt = $conn->prepare($updateBSql);
        $updateBStmt->bind_param("ss", $b, $a);
        $updateBStmt->execute();
    } else {
        // BがAをフォローしていない場合、AがBをフォローし、zyoukyouを0に設定
        $insertSql = "INSERT INTO Follow (applicant_name, approver_name, zyoukyou) VALUES (?, ?, 0)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ss", $a, $b);
        $insertStmt->execute();
    }
}

} else {
    // POSTリクエスト以外の場合の処理
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}



?>