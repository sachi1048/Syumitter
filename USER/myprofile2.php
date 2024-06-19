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
    <h1 class="syumitter1">Syumitter</h1>

<?php
    $pdo = new PDO($connect, USER, PASS);
    $user_name = $_SESSION['user']['user_name'];
    $display_name = $_SESSION['user']['display_name'];
    $aikon = $_SESSION['user']['aikon'];
    $profile = $_SESSION['user']['profile'];

    echo '<table style="margin: auto;"><tr><td>';
    echo '<div class="aikon">
            <img src="img/aikon/', $aikon, '" alt="マイアイコン" class="maru">
          </div></td>';


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
                <td><a href="follower-list.php" class="link">フォロワー</a></td>
                <td><a href="follow-list.php" class="link">フォロー</td>
            </tr>
        </table></td><tr></table>';
    echo '<div class="left1">'; //後で変更
    echo '<h2>', $user_name, '</h2>';
    echo '<h4>', $display_name, '</h4>';
    echo '</div>';
    echo '<br><br><br><br>';
    echo '<div class="profile">';
    echo '<p>', $profile, '</p>';
    echo '</div>';
    echo '<a href="myprofile-edit" class="btn-profile">プロフィール編集</a>';

?>
    <form action="myprofile-edit.php" method="POST">
    </form> 
    <div style="width: 100%;">
        <a href="myprofile" style="text-decoration: none;">
            <img class="icon1" src="img/imagebox.png">
        </a>
        <a href="myprofile2" style="text-decoration: none;">
            <img class="icon1 icon2" src="img/heart.png">
        </a>
    </div>
    <hr>
    <table>
        <?php 
         $sql4=$pdo->prepare('select * from Comment where account_mei=? and comment_type=1');
         $sql4->execute([$user_name]);
         $count = 1;
         echo '<tr>';
         foreach($sql4 as $row4){
            $sql5=$pdo->prepare('select contents from Toukou where toukou_id=?');
            $sql5->execute([$row4['toukou_id']]);
            if($count % 3 == 0){
                echo '<tr>';
            }
            echo '<td><div>',
                    // $row4['contents'],
                    //試しの画像
                    '<img src="img/aikon/', $sql5, '">';
            echo '</div></td>';
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
    <footer><?php require 'menu.php';?></footer>
</body>
</html>