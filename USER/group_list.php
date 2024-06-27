<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<?php
    
    unset($_SESSION['group_id']);
    $pdo = new PDO($connect, USER, PASS);
    $user_name = $_SESSION['user']['user_name'];
    $display_name = $_SESSION['user']['display_name'];
    $aikon = $_SESSION['user']['aikon'];
    // try {
    //     $conn = new PDO($connect, USER, PASS);
    //     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //     // グループチャットの取得
    //     $sql = "
    //     SELECT g.group_id, g.group_mei, g.aikon, COUNT(gm.member) AS member_count
    //     FROM `Group` g
    //     LEFT JOIN Group_member gm ON g.group_id = gm.group_id
    //     LEFT JOIN Follow f ON g.group_id = f.follow_id
    //     WHERE f.zyoukyou = 1
    //     GROUP BY g.group_id, g.group_mei, g.aikon;
    //     ";
    //     $stmt = $conn->prepare($sql);
    //     $stmt->execute();
    //     $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // } catch (PDOException $e) {
    //     echo "Connection failed: " . $e->getMessage();
    // }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/menu.css">
    <link rel="stylesheet" href="CSS/group_list.css">
    <title>Syumitter</title>
    
    <title>グループチャット一覧画面</title>
</head>
<body>
    <h1 class="h1-2">Syumitter</h1>
    <br>
    <div class="switch">
        <a class="link switch2" href="group_list.php?user_name=<?php echo $user_name; ?>">グループチャット</a>
        <a class="link switch-right" href="pair_list.php?user_name=<?php echo $user_name; ?>">ペアチャット</a>
    </div>
    
    <table class="table-chat">

    <?php
        $sql=$pdo->query('select * from Group_chat');
        foreach($sql as $row){
            echo '<tr>
                <td>
                    <div class="aikon">
                    <img src="img/chat/', $row['aikon'], '" alt="チャットアイコン" class="maru">
                    </div>
                </td>
                <td>
                    <a href="group_chat.php?group_id=', $row['group_id'], '" class="chat-mei">', $row['group_mei'], '</a><br>';
                    //趣味タグ表示
                    $sql2=$pdo->prepare('select * from Tag where tag_id=?');
                    $sql2->execute([$row['tag_id']]);
                    foreach($sql2 as $row2){
                        echo '<div class="s-tag" style="background: rgb(', $row2['tag_color1'], ',', $row2['tag_color2'], ',', $row2['tag_color3'], '">', $row2['tag_mei'], '</div><br>';
                    }
                    //メンバー名表示
                    $sql3=$pdo->prepare('select * from Group_member where group_id=?');
                    $sql3->execute([$row['group_id']]);
                    foreach($sql3 as $row3){
                        $sql4=$pdo->prepare('select display_name from Account where user_name=?');
                        $sql4->execute([$row3['member']]);
                        $row4 = $sql4->fetch(PDO::FETCH_ASSOC);
                        echo $row4['display_name'];
                    }


            echo '</td>
                <td>
                    <div class="chat-action">
                        99
                    </div>
                </td>
            </tr>';
        }

    ?>
    </table>



    <footer><?php include 'menu.php';?></footer>
</body>
</html>

