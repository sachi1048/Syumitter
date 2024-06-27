<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<?php

    $_SESSION['group_id'] = array();

    $pdo = new PDO($connect, USER, PASS);
    $user_name = $_SESSION['user']['user_name'];
    $display_name = $_SESSION['user']['display_name'];
    $aikon = $_SESSION['user']['aikon'];
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
    
    <title>ペアチャット一覧画面</title>
</head>
<body>
    <h1 class="h1-2">Syumitter</h1>
    <br>
    <div class="switch">
        <a class="link switch2" href="group_list.php">グループチャット</a>
        <a class="link switch-right" href="pair_list.php">ペアチャット</a>
    </div>

    <table class="table-chat">

    <?php
        //コピペっただけ　相互の人を表示
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
            echo '</td>';
            //通知
            echo '<td>
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

