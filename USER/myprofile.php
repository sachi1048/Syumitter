<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/menu.css">
    <title>マイプロフィール画面</title>
</head>
<body>
    <h1 class="h1-2">Syumitter</h1>

<?php
    $pdo = new PDO($connect, USER, PASS);
    $user_name = $_SESSION['user']['user_name'];
    $display_name = $_SESSION['user']['display_name'];
    $aikon = $_SESSION['user']['aikon'];
    $profile = $_SESSION['user']['profile'];

    echo '<table style="margin: auto;"><tr><td>';
    echo '<img src="img/aikon/', $aikon, '" alt="マイアイコン" class="maru">
          </td>';


    $sql=$pdo->prepare('select * from Toukou where toukou_mei=?');
    $sql->execute([$user_name]);
    $toukou = 0; //投稿数
    foreach($sql as $row){
        $toukou++;
    }
    $sql2=$pdo->prepare('select * from Follow where applicant_name=?');
    $sql2->execute([$user_name]);
    $follow = 0;
    foreach($sql2 as $row2){
        $follow++;
    }
    $sql3=$pdo->prepare('select * from Follow where approver_name=?');
    $sql3->execute([$user_name]);
    $follower = 0;
    foreach($sql3 as $row3){
        $follower++;
    }
    echo '<td><table class="table1">
            <tr>
                <td>', 
                //投稿数
                $toukou, 
                '</td>
                <td>', 
                //フォロワー数
                $follower,
                '</td>
                <td>',
                //フォロー数
                $follow,
                '</td>
            </tr>
            <tr>
                <td>投稿数</td>
                <td><a href="myfollower-list.php" class="link">フォロワー</a></td>
                <td><a href="myfollow-list.php" class="link">フォロー</td>
            </tr>
        </table></td><tr></table>';
    echo '<div class="left1">'; //後で変更
    echo '<h2>', $user_name, '</h2>';
    echo '<h4>', $display_name, '</h4>';
    echo '</div><br><br><br><br><br><div>';
    //趣味タグ表示
    $sql4=$pdo->prepare('select * from User_tag where user_name=?');
    $sql4->execute([$user_name]);
    foreach($sql4 as $row4){
        $sqltag=$pdo->prepare('select * from Tag where tag_id=?');
        $sqltag->execute([$row4['tag_id']]);
        foreach($sqltag as $tag){
            echo '<div class="s-tag" style="background: rgb(', $tag['tag_color1'], ',', $tag['tag_color2'], ',', $tag['tag_color3'], '">', $tag['tag_mei'], '</div>';
        }
        
            
    }
    echo '<a href="myprofile-tag.php">
            <img src="img/puramai.png" class="puramai">
          </a>';
    echo '</div><div class="profile">';
    echo '<p>', $profile, '</p>';
    echo '</div>';
    echo '<a href="myprofile-edit" class="btn-profile">プロフィール編集</a>';

?>
    <form action="myprofile-edit.php" method="POST">
    </form> 
    <div style="width: 100%;">
        <a href="myprofile" style="text-decoration: none;">
            <img class="icon1 icon2" src="img/imagebox.png">
        </a>
        <a href="myprofile2" style="text-decoration: none;">
            <img class="icon1" src="img/heart.png">
        </a>
    </div>
    <hr>
    <table style="padding-bottom: 100px; margin: auto;">
        <?php 
         $sql5=$pdo->prepare('select * from Toukou where toukou_mei=?');
         $sql5->execute([$user_name]);
         $count = 0;
         echo '<tr>';
         foreach($sql5 as $row5){
            if($count == 0){
            }else if($count % 3 == 0){
                echo '<tr>';
            }
            echo '<td>',
                    '<a href="toukou_disp.php?toukou_id=', $row5['toukou_id'], '"><img src="img/toukou/', $row5['contents'], '" class="size">';
            echo '</td>';
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
    <footer><?php include 'menu.php';?></footer>
</body>
</html>
