<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
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
    <a href="group_new.php">
        <span class="btn-mdr2"></span>
    </a>

    <p style="color: #747474;">メンバー招待</p>
    
    <form action="group_new.php" method="POST">
<?php
    $pdo = new PDO($connect, USER, PASS);
    $user_name = $_SESSION['user']['user_name'];
    $display_name = $_SESSION['user']['display_name'];

    $sql=$pdo->prepare('select * from Follow where applicant_name=?');
    $sql->execute([$user_name]);
    echo '<table class="table-follow">';
    foreach($sql as $row){
        $sql2=$pdo->query('select * from Account where user_name="'.$row['approver_name'].'"');
        foreach($sql2 as $row2){
            echo '<tr><td>';
            echo '<div class="aikon">
                    <img src="img/aikon/', $row2['aikon'], '" alt="マイアイコン" class="maru2">
                  </div></td>';
            echo '<td>
                    <a href="profile.php?user_name=', $row2['user_name'], '" style="Text-decoration:none; color:#000000;">
                    <h2>', $row2['user_name'], '</h2>
                  </td>
                  <td>
                    <label><input type="checkbox" name="members[]" value="', $row2['user_name'], '"><span>招待する</span></label>
                  </td></tr>';
        }
    }
    echo '</table>';
?>
        <button class="nextbutton" type="submit">決定</button>
    </form>
</body>
</html>