<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/menu.css">
    <title>マイプロフィールいいね画面</title>
</head>
<body>
    <h1 class="h1-2">Syumitter</h1>

    <?php

        $pdo = new PDO($connect, USER, PASS);
        $user=$pdo->prepare('select * from Account where user_name=? ');
        $user->execute([$_GET['user_name']]);
        $rr = $user->fetch(PDO::FETCH_ASSOC);
        if($rr){
        
        $user_name = $rr['user_name'];
        $display_name = $rr['display_name'];
        $aikon = $rr['aikon'];
        $profile = $rr['profile'];

        echo '<table style="margin: auto;"><tr><td>';
        echo '<div class="aikon">
                <img src="img/aikon/', $aikon, '" alt="マイアイコン" class="maru">
              </div></td>';

        $sql = $pdo->prepare('SELECT * FROM Toukou WHERE toukou_mei = ?');
        $sql->execute([$user_name]);
        $toukou = $sql->rowCount();

        $sql2 = $pdo->prepare('SELECT * FROM Follow WHERE applicant_name = ?');
        $sql2->execute([$user_name]);
        $follow = $sql2->rowCount();

        $sql3 = $pdo->prepare('SELECT * FROM Follow WHERE approver_name = ?');
        $sql3->execute([$user_name]);
        $follower = $sql3->rowCount();

        echo '<td><table class="table1">
                <tr>
                    <td>', $toukou, '</td>
                    <td>', $follower, '</td>
                    <td>', $follow, '</td>
                </tr>
                <tr>
                    <td>投稿数</td>
                    <td><a href="follower-list.php?user_name=', $user_name, '" class="link">フォロワー</a></td>
                <td><a href="follow-list.php?user_name=', $user_name, '" class="link">フォロー</a></td>
                </tr>
            </table></td></tr></table>';
        echo '<div class="oya">';
        echo '<div class="left1">';
        echo '<h2>', $user_name, '</h2>';
        echo '<h4>', $display_name, '</h4></div>';
        echo '<div class="right">';
        //ここにフォローボタンを作ること
        $ff=$pdo->prepare('select * from Follow where applicant_name=? and approver_name=?');
        $ff->execute([$user_name, $_SESSION['user']['user_name']]);
        if($ff){
            echo '<button id="follow" class="btn-follow1">フォロー中</button>';
        }else{
            echo '<button id="follow" class="btn-follow2">フォローする</button>';   
        }

        echo '</div></div><div>';

        $sql4 = $pdo->prepare('SELECT * FROM User_tag WHERE user_name = ?');
        $sql4->execute([$user_name]);
        foreach($sql4 as $row4){
            $sqltag = $pdo->prepare('SELECT * FROM Tag WHERE tag_id = ?');
            $sqltag->execute([$row4['tag_id']]);
            foreach($sqltag as $tag){
                echo '<div class="s-tag" style="background: rgb(', $tag['tag_color1'], ',', $tag['tag_color2'], ',', $tag['tag_color3'], ')">', $tag['tag_mei'], '</div>';
            }
        }
        echo '</div><div class="profile">';
        echo '<p>', $profile, '</p>';
        echo '</div>';
    ?>

    <div style="width: 100%;">
        <a href="profile.php?user_name=<?php echo $user_name; ?>" style="text-decoration: none;">
            <img class="icon1" src="img/imagebox.png">
        </a>
        <a href="profile2.php?user_name=<?php echo $user_name; ?>" style="text-decoration: none;">
            <img class="icon1 icon2" src="img/heart.png">
        </a>
    </div>
    <hr>
    <table style="padding-bottom: 100px;">
        <?php 
        $sql4 = $pdo->prepare('SELECT * FROM Comment WHERE account_mei = ? AND comment_type = 1');
        $sql4->execute([$user_name]);
        $count = 0;
        echo '<tr>';
        foreach($sql4 as $row4){
            $sql5 = $pdo->prepare('SELECT contents FROM Toukou WHERE toukou_id = ?');
            $sql5->execute([$row4['toukou_id']]);
            if($count % 3 == 0){
                echo '<tr>';
            }
            echo '<td><a href="toukou_disp.php?toukou_id=', $row4['toukou_id'], '"><img src="img/toukou/', $sql5->fetch()['contents'], '" class="size"></a></td>';
            $count++;
            if($count % 3 == 0){
                echo '</tr>';
            }
        }
        if($count % 3 != 0){
            echo '</tr>';
        }
        ?>
    </table>
    <?php } ?>
    <footer><?php include 'menu.php';?></footer>
</body>
</html>
