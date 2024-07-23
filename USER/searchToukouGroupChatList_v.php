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
    <title>グループチャット検索一覧画面</title>
</head>
<body>
    <h1 class="h1-2">Syumitter</h1>

    <a href="pair_new.php">
        <img src="img/newchat" class="newchat" alt="新規ペアチャット">
    </a>

    <br>

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
                    if($_SESSION['user']['user_name']==$member['member']){
                        $doJoinGlg=1;
                        break;
                    }else{
                        $doJoinGlg=0;
                    }
                    echo $member['display_name'], ' ';
                }

        echo '</td>';
        echo '<td>';
            if ($doJoinGlg==1){
                echo "<form method='get' action='group_chat.php'>";
                echo "<input type='hidden' name='group_id' value='" . $groupId . "'>";
                echo "<button type='submit'>参加中</button>";
                echo "</form>";
            } else {
                echo "<form method='post' action='chatGroupApi.php'>";
                echo "<input type='hidden' name='group_id' value='" . $groupId . "'>";
                echo "<input type='hidden' name='user_name' value='" . $user_name . "'>";
                echo "<button type='submit'>参加する</button>";
                echo "</form>";
            }
        echo '</td>';
        echo '</tr>';
        }
    ?>
    </table>
    <footer><?php include 'menu.php';?></footer>
</body>
</html>

