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

    echo '<div class="profile1"><img src="', $aikon, '" alt="マイアイコン"></div>';
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
    echo '<div class="profile2"><table>
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
        <table></div>';
    echo '<h2>', $user_name, '<h2>';
    echo '<h4>', $display_name, '</h4>';
    echo '<div>', $profile, '<div>';

?>
    <form action="myprofile-edit.php" method="POST">
    </form> 
    <botton>□</button>
    <button>♡</button>

 
</body>
</html>