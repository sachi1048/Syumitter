<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<?php

    unset($_SESSION['chat_id']);
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

    <a href="pair_new.php">
        <img src="img/newchat" class="newchat" alt="新規ペアチャット">
    </a>

    <br>
    <div class="switch">
        <a class="link switch-left" href="group_list.php">グループチャット</a>
        <a class="link switch2" href="pair_list.php">ペアチャット</a>
    </div>

    <table class="table-chat">

    <?php
        //コピペっただけ　相互の人を表示
        $sql=$pdo->prepare('select * from Pair_chat where user1=? and delete_code <> 1');
        $sql->execute([$_SESSION['user']['user_name']]);
        foreach($sql as $row){
            $sql2=$pdo->prepare('select * from Account where user_name=?');
            $sql2->execute([$row['user2']]);
            foreach($sql2 as $row2)
            echo '<tr>
                <td>
                    <div class="aikon">
                    <img src="img/aikon/', $row2['aikon'], '" alt="チャットアイコン" class="maru">
                    </div>
                </td>
                <td>
                    <a href="pair_chat.php?pair_chat="',$row['chat_id'], '" class="chat-mei">', $row2['display_name'], '</a><br>';
            echo '</td>';
            //通知
            $zzz=$pdo->prepare('select count(*) as con from Pair_Rireki where chat_id = ? and sender <> ?');
            $zzz->execute([$row['chat_id'],$_SESSION['user']['user_name']]);
            $kekka=$zzz->fetch(PDO::FETCH_ASSOC);
            $lol=$pdo->prepare('select count(*) as com from Pair_Kidoku where user_name = ? and chat_id = ?');
            $lol->execute([$_SESSION['user']['user_name'],$row['chat_id']]);
            $sa=$lol->fetch(PDO::FETCH_ASSOC);
            $tuuti=$kekka['con']-$sa['com'];
            if($tuuti == 0){
                echo '<td></td>';
            }else{
                echo '<td>
                    <div class="chat-action">
                        ',$tuuti,'
                    </div>
                </td>';
            }
            echo '</tr>';
        }

    ?>
    </table>



    <footer><?php include 'menu.php';?></footer>
</body>
</html>

