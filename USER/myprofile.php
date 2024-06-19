<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<?php
    if(!isset($_SESSION['user'])){
        $_SESSION['user']=['user_name'=>$_POST['user_name'],'pass'=>$_POST['pass']];
    }
    $pdo=new PDO($connect,USER,PASS);
    $sql=$pdo->prepare('select * from user where user_name=? and pass=?');
    $sql->execute([$_SESSION['user']['user_name'],$_SESSION['user']['pass']]);
    $count = $sql -> rowCount();
    if($count == 0 && !isset($_SESSION['user']['user_name'])){
        unset($_SESSION['user']);
        header("Location: login.php");
        exit;
    }else{
        unset($_SESSION['user']);
        foreach ($sql as $row){
            $_SESSION['user']=[
                'user_name'=>$row['user_name'],'display_name'=>$row['display_name'],
                'aikon'=>$row['aikon'],'profile'=>$row['profile'],
                'mail'=>$row['mail'], 'pass' => $row['pass']
            ];
            if (isset($_POST['login'])) {
                $cookie_value = serialize($_SESSION['user']);
                setcookie('login_me_cookie', $cookie_value, time() + (86400 * 30), "/"); // 30日間のクッキーを設定
            }
        
        }
    
?>
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
    $user_name = $_SESSION['user']['user_name'];
    $display_name = $_SESSION['user']['display_name'];
    $aikon = $_SESSION['user']['aikon'];
    $profile = $_SESSION['profile'];

    echo '<img src="', $aikon, '" alt="マイアイコン">';
    
    $sql=$pdo->query('select * from toukou where toukou_mei = '.$user_name);
    $toukou = 0; //投稿数
    foreach($sql as $row){
        $toukou++;
    }
    $sql2=$pdo->query('select * form follow where applicant_name = '.$user_name);
    $follow = 0;
    foreach($sql2 as $row2){
        $follow++;
    }
    $sql3=$pdo->query('select * form follow where approver_name = '.$user_name);
    $follower = 0;
    foreach($sql3 as $row3){
        $follower++;
    }
    echo '<table>
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
        <table>';

    echo '<h2>', $user_name, '<h2>';
    echo '<h4>', $display_name, '</h4>';
    echo '<div>', $profile, '<div>';

?>
    <form action="myprofile-edit.php" method="POST">
    </form> 
    <botton>□</button>
    <button>♡</button>

<?php
    }
?>
    
</body>
</html>