<?php require 'db-connect.php'; ?>
<?php 

$pdo = new PDO($connect, USER, PASS);

$group_id = $_POST['group_id'];
$member = $_POST['user_name'];

$sql = "INSERT INTO group_member (group_id, member) VALUES (:group_id, :member);";
$stmt = $pdo->prepare($sql);

$stmt->bindParam(':group_id', $group_id);
$stmt->bindParam(':member', $member);
$stmt->execute();

// GETパラメータを設定する配列
$params = array(
    'group_id' => $group_id
);
// クエリストリングを構築
$query_string = http_build_query($params);

// リダイレクト先のURL
$url = "group_chat.php?$query_string";

// リダイレクトを実行
header("Location: $url");
exit();



?>