<?php
    session_start();
    require 'db-connect.php';
    $pdo=new PDO($connect,USER,PASS);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['taikai'])) {
            $sdl=$pdo->prepare('delete from Group_member where group_id = ? and member = ?');
            $sdl->execute([$_SESSION['group_id'],$_POST['user_name']]);
        }
        if(isset($_POST['shoutai'])){
            $skl=$pdo->prepare('insert into Group_member values(?,?)');
            $skl->execute([$_SESSION['group_id'],$_POST['user_name']]);
        }
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>グループメンバー編集画面</title>
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/gromem.css">
</head>
<body>
    <a href="group_edit.php"><span class="btn-mdr2"></span></a>
    <h1 class="h1-1">Syumitter</h1>
    <h4 class="meme">メンバー招待・退会</h4>
    <?php
        $hid=$pdo->prepare('select creator_mei from Group_chat where group_id = ?');
        $hid->execute([$_SESSION['group_id']]);
        $result = $hid->fetch(PDO::FETCH_ASSOC);
        if($result['creator_mei'] == $_SESSION['user']['user_name']){
            $sql = $pdo->prepare('SELECT gm.member AS member, ac.display_name AS display_name, ac.aikon AS progazou FROM Group_member AS gm JOIN Account AS ac ON gm.member = ac.user_name WHERE gm.group_id = ? and member <> ?');
            $sql->execute([$_SESSION['group_id'],$_SESSION['user']['user_name']]);
            foreach($sql as $row){
                echo '<form class="narabi" action="group_member.php" method="post">';
                echo '<img class="maru2" src="img/aikon/',$row['progazou'],'" alt="プロフィール画像">';
                echo '　',$row['display_name'],'　　';
                echo '<input type="hidden" name="user_name" value="',$row['member'],'">';
                echo '<button class="exit" type="submit" name="taikai">退会させる</button>';
                echo '</form>';
            }
        }
        $ppp=$pdo->prepare('select * from Account where user_name <> ?');
        $ppp->execute([$_SESSION['user']['user_name']]);
        $flg=false;
        foreach($ppp as $row){
            $peo=$pdo->prepare('select * from Group_member where group_id = ?');
            $peo->execute([$_SESSION['group_id']]);
            foreach($peo as $wow){
                if($wow['member'] == $row['user_name']){
                    $flg=true;
                    break;
                }
            }
            if($flg){
                echo '<div class="narabi">';
                echo '<img class="maru2" src="img/aikon/',$row['aikon'],'" alt="プロフィール画像">';
                echo '<span>　',$row['display_name'],'　　</span>';
                echo '<p class="likebutton">招待済み</p>';
                echo '</div>';
            }else{
                echo '<form class="narabi" action="group_member.php" method="post">';
                echo '<img class="maru2" src="img/aikon/',$row['aikon'],'" alt="プロフィール画像">';
                echo '<span>　',$row['display_name'],'　　</span>';
                echo '<input type="hidden" name="user_name" value="',$row['user_name'],'">';
                echo '<button class="invitation" type="submit" name="shoutai">招待する</button>';
                echo '</form>';
            }
            $flg=false;
        }
    ?>
    <br>
    <form action="group_list.php" method="post">
        <button class="nextbutton" type="submit">決定</button>
    </form>
</body>
</html>