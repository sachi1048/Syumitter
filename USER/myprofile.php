<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <title>マイプロフィール画面</title>
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
            <lable label="file_label">
                <img src="img/aikon/', $aikon, '" alt="マイアイコン" class="maru">
            </lable>
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
    echo '<td><table>
            <tr>
                <td>', 
                //投稿数
                $toukou, 
                '</td>
                <td>', 
                //フォロー数
                $follow,
                '</td>
                <td>',
                //フォロワー数
                $follower,
                '</td>
            </tr>
            <tr>
                <td>投稿数</td>
                <td>フォロー数</td>
                <td>フォロワー数</td>
            </tr>
        </table></td></table>';
    echo '<div class="left1">'; //後で変更
    echo '<h2>', $user_name, '</h2>';
    echo '<h4>', $display_name, '</h4>';
    echo '</div>';
    echo '<br><br><br><br>';
    echo '<div class="profile">';
    echo '<p>', $profile, '</p>';
    echo '</div>';

?>
    <form action="myprofile-edit.php" method="POST">
    </form> 
    <div style="width: 100%;">
        <img class="icon1" src="img/imagebox.png">
        <img class="icon1" src="img/heart.png">
    </div>
    <hr>

 
</body>
</html>