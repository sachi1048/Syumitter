<?php
session_start();
require 'db-connect.php';

if (isset($_POST['user_name']) && isset($_POST['pass'])) {
    $pdo = new PDO($connect, USER, PASS);

    $sql = $pdo->prepare('SELECT * FROM account WHERE user_name = ?');
    $sql->execute([$_POST['user_name']]);
    
    $row = $sql->fetch();

    if ($row && password_verify($_POST['pass'], $row['pass'])) {
        $_SESSION['user']=[
            'user_name'=>$row['user_name'],'display_name'=>$row['display_name'],
            'aikon'=>$row['aikon'],'profile'=>$row['profile'],
            'mail'=>$row['mail'], 'pass' => $row['pass']
        ];

        if (isset($_POST['login'])) {
            $cookie_value = base64_encode(serialize($_SESSION['user']));
            setcookie('login_me_cookie', $cookie_value, time() + (86400 * 30), "/", "", false, true); 
        }

        header("Location: myprofile.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <title>ログイン画面</title>
</head>
<body>
    <h1>Syumitter</h1>
    <div class="frame">
        <p style="font-weight: bold;">login</p>
        <form action="myprofile.php" method="POST">
            <input class="textbox" type="text" name="user"  placeholder="ユーザー名"><br>
            <input class="textbox" type="text" name="pass"  placeholder="パスワード"><br>
            <button class="nextbutton" type="submit">ログイン</button>
        </form>
    </div>

    <p><a href="#">新規作成</a></p>
    <p><a href="#">パスワードを忘れた</p>

</body>
</html>