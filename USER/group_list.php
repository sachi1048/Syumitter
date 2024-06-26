<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<?php

    $_SESSION['group_id'] = array();

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

    <table style="margin: auto;">

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
                    <a href="group_chat.php?group_id=', $row['group_id'], '" style="Text-decoration:none; color:#000000;">
                    <h2>', $row['group_mei'], '</h2>
                </td>
                <td>

                </td>
            </tr>';
        }

    ?>
    </table>

    <footer><?php include 'menu.php';?></footer>
</body>
</html>

