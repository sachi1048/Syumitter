<?php
    session_start();
    require 'db-connect.php';
    $pdo=new PDO($connect,USER,PASS);
    $group_id=$_POST['chat_id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['name_edit'])) {
            $sdl=$pdo->prepare('update Group_chat set group_mei = ? where group_id = ?');
            $sdl->execute([$_POST['group_mei'],$_POST['group_id']]);
        }
        if(isset($_POST['taikai'])){
            $skl=$pdo->prepare('delete from Group_member where member = ? and group_id = ?');
            $skl->execute([$_SESSION['user']['user_name'],$_POST['group_id']]);
        }
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>グループチャット編集画面</title>
    <link rel="stylesheet" href="CSS/main.css">
</head>
<body>
    <?php
    echo '<a href="group_chat.php?group_id=',$group_id,'"><span class="btn-mdr2"></span></a>';
    ?>
    <h1 class="h1-1">Syumitter</h1>
    <div class="frame">
        チャット編集<br>
        <?php
            $sql=$pdo->prepare('select * from Group_chat where group_id = ?');
            $sql->execute([$group_id]);
            $result = $sql->fetch(PDO::FETCH_ASSOC);
            echo '<img class="maru" src="img/aikon/',$result['aikon'],'" alt="グループチャットアイコン">';
            echo '<form action="group_edit.php" method="post">';
            echo '<input type="text" name="group_mei" value="',$result['group_mei'],'" required>';
            echo '<input type="hidden" name="group_id" value="',$group_id,'">';
            echo '<button class="nextbutton" type="submit name="name_edit">編集</button>';
            echo '</form>';
        ?>
    </div><br>
    <div class="frame">
        メンバー一覧<br>
        <?php
            $ssl=$pdo->prepare('select * from Group_member where group_id = ?');
            $ssl->execute([$group_id]);
            foreach($ssl as $row){
                $sss=$pdo->prepare('select * from Account where user_name = ?');
                $sss->execute([$row['member']]);
                foreach($sss as $roo){
                    echo '<img class="maru2" src="img/aikon/',$roo['aikon'],'" alt="アカウントアイコン">';
                    echo '　',$roo['display_name'];
                }
            }
            echo '<form action="group_member.php" method="post">';
            echo '<input type="hidden" name="group_id" value="',$group_id,'">';
            echo '<button type="submit">メンバーの招待・退会</button>';
            echo '</form>';
        ?>
    </div>
    <form action="group_edit.php" method="post">
        <input type="hidden" name="group_id" value="<?= $group_id ?>">
        <button type="submit" name="taikai">チャット退会</button>
    </form>
</body>
</html>