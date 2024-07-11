<?php session_start();
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);
$user_name = $_SESSION['user']['user_name'];
$display_name = $_SESSION['user']['display_name'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/menu.css">
    <title>新規グループチャットのメンバー選択画面</title>
</head>
<style>
    label {
        margin-right: 5px; /* ボタン同士の間隔 */
    }
    label input {
        display: none; /* デフォルトのinputは非表示にする */
    }
    label span {
        color: #333; /* 文字色を黒に */
        font-size: 14px; /* 文字サイズを14pxに */
        border: 1px solid #333; /* 淵の線を指定 */
        border-radius: 20px; /* 角丸を入れて、左右が丸いボタンにする */
        padding: 5px 20px; /* 上下左右に余白をトル */
    }
    label input:checked + span {
        color: #FFF; /* 文字色を白に */
        background: #FBB; /* 背景色を薄い赤に */
        border: 1px solid #FBB; /* 淵の線を薄い赤に */
    }
</style>
<body>
    <h1 class="h1-1">Syumitter</h1>
    <a href="pair_new.php">
        <span class="btn-mdr2"></span>
    </a>

    <p style="color: #747474;">メンバー招待</p>
    
    <form action="pair_new.php" method="POST">
<?php
    if ($user_name) {
        $stmt = $pdo->prepare('SELECT * FROM Follow WHERE applicant_name = ?');
        $stmt->execute([$user_name]);
        echo '<table class="table-follow">';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stmt2 = $pdo->prepare('SELECT * FROM Account WHERE user_name = ?');
            $stmt2->execute([$row['approver_name']]);
            while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr><td>';
                echo '<div class="aikon">
                        <img src="img/aikon/', htmlspecialchars($row2['aikon']), '" alt="マイアイコン" class="maru2">
                      </div></td>';
                echo '<td>
                        <a href="profile.php?user_name=', htmlspecialchars($row2['user_name']), '" style="Text-decoration:none; color:#000000;">
                        <h2>', htmlspecialchars($row2['user_name']), '</h2>
                      </td>
                      <td>
                        <label><input type="radio" name="member" value="', htmlspecialchars($row2['user_name']), '"><span>選択する</span></label>
                      </td></tr>';
            }
        }
        echo '</table>';
    }
?>
        <button class="nextbutton" type="submit">決定</button>
    </form>
</body>
</html>