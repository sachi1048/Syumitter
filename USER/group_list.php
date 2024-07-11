<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<?php
    
    unset($_SESSION['group_id']);
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
    
    <title>グループチャット一覧画面</title>
</head>
<body>
    <h1 class="h1-2">Syumitter</h1>

    <a href="group_new.php">
        <img src="img/newchat" class="newchat" alt="新規グループチャット">
    </a>

    <br>
    <div class="switch">
        <a class="link switch2" href="group_list.php">グループチャット</a>
        <a class="link switch-right" href="pair_list.php">ペアチャット</a>
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
                        echo $row4['display_name'], ' ';
                    }


            echo '</td>';
            //通知
            $zzz=$pdo->prepare('select count(*) as con from Group_Rireki where chat_id = ? and sender <> ?');
            $zzz->execute([$row['group_id'],$_SESSION['user']['user_name']]);
            $kekka=$zzz->fetch(PDO::FETCH_ASSOC);
            $lol=$pdo->prepare('select count(*) as com from Group_Kidoku where user_name = ? and group_id = ?');
            $lol->execute([$_SESSION['user']['user_name'],$row['group_id']]);
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

