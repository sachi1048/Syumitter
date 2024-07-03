<?php
    session_start();
    require 'db-connect.php';
    $pdo=new PDO($connect,USER,PASS);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['name_edit'])) {
            $sdl=$pdo->prepare('update Group_chat set group_mei = ? where group_id = ?');
            $sdl->execute([$_POST['group_mei'],$_SESSION['group_id']]);
        }
        if(isset($_POST['taikai'])){
            $skl=$pdo->prepare('delete from Group_member where member = ? and group_id = ?');
            $skl->execute([$_SESSION['user']['user_name'],$_SESSION['group_id']]);
            header("Location: myprofile.php");
            exit;
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
    <link rel="stylesheet" href="CSS/gromem.css">
</head>
<body>
    <?php
    echo '<a href="group_chat.php?group_id=',$_SESSION['group_id'],'"><span class="btn-mdr2"></span></a>';
    ?>
    <h1 class="h1-1">Syumitter</h1>
    <div class="frame">
        <p class="midasi">チャット編集</p>
        <?php
            $sql=$pdo->prepare('select * from Group_chat where group_id = ?');
            $sql->execute([$_SESSION['group_id']]);
            $result = $sql->fetch(PDO::FETCH_ASSOC);
            echo '<img class="maru" src="img/aikon/',$result['aikon'],'" alt="グループチャットアイコン">';
            echo '<form action="group_edit.php" method="post">';
            echo '<input type="text" name="group_mei" placeholder="',$result['group_mei'],'" required>';
            echo '<input type="hidden" name="group_id" value="',$_SESSION['group_id'],'">';
            echo '<button class="nextbutton" type="submit" name="name_edit">編集</button>';
            echo '</form>';
        ?>
    </div><br>
    <div class="frame">
        <p class="midasi">メンバー一覧<p>
        <?php
            $ssl=$pdo->prepare('select * from Group_member where group_id = ?');
            $ssl->execute([$_SESSION['group_id']]);
            foreach($ssl as $row){
                $sss=$pdo->prepare('select * from Account where user_name = ?');
                $sss->execute([$row['member']]);
                foreach($sss as $roo){
                    echo '<div class="members">';
                    echo '<img class="maru2" src="img/aikon/',$roo['aikon'],'" alt="アカウントアイコン">';
                    echo '　',$roo['display_name'];
                    echo '</div>';
                }
            }
            echo '<form action="group_member.php" method="post">';
            echo '<input type="hidden" name="group_id" value="',$_SESSION['group_id'],'">';
            echo '<button class="invitationbutton" type="submit">メンバーの招待・退会</button>';
            echo '</form>';
        ?>
    </div><br>
    <form action="group_edit.php" method="post">
        <input type="hidden" name="group_id" value="<?= $_SESSION['group_id'] ?>">
        <button type="submit" class="taikai" name="taikai">チャット退会</button>
    </form>
</body>
</html>