<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>グループチャット画面</title>
    <link rel="stylesheet" href="CSS/group_chat.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.0/css/all.css">
</head>
<body>
    <?php
        $pdo = new PDO($connect,USER,PASS);
        $spl=$pdo->query('SELECT gc.group_mei,COUNT(gm.member) AS member_count FROM Group_chat gc LEFT JOIN Group_member gm ON gc.group_id = gm.group_id WHERE gm.group_id = 1 GROUP BY gc.group_mei;');// ←未完成
        $result = $spl->fetch(PDO::FETCH_ASSOC);
        echo '<div class="waku">';
        echo '<table>';
        echo '<tr>';
        echo '<td>';
        echo '<button class="backbutton" onclick="history.back()"><i class="fas fa-caret-left fa-2x" style="color: blue"></i></button>';
        echo '</td>';
        echo '<td>';
        echo $result['group_mei'],'(',$result['member_count'],')';
        echo '</td>';
        echo '<td>';
        echo '<form action="#" method="post">';
        echo '<input type="hidden" name="chat_id" value="1">';
        echo '<button class="menuicon" type="submit"><i class="fas fa-bars fa-2x"></i></button';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
        echo '</table>';
        echo '</div>';
    ?>
</body>
</html>