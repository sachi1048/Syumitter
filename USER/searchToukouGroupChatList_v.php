<?php session_start(); ?>
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
    foreach ($groupedResults as $groupId => $groupData) {
        echo '<tr>
            <td>
                <div class="aikon">
                <img src="img/chat/', $groupData['group_aikon'], '" alt="チャットアイコン" class="maru">
                </div>
            </td>
            <td>
                <a href="group_chat.php?group_id=', $groupId, '" class="chat-mei">', $groupData['group_mei'], '</a><br>';
                echo '<div class="s-tag" style="background: rgb(', $groupData['tag_color1'] , ',', $groupData['tag_color1'] , ',', $groupData['tag_color2'] , '">', $groupData['tag_mei'] , '</div><br>';
                foreach ($groupData['members'] as $member) {
                    echo $member['display_name'], ' ';
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

