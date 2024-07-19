<?php
session_start();
session_unset();
require 'db-connect.php';

if (isset($_POST['user_name']) && isset($_POST['pass'])) {
    $pdo = new PDO($connect, USER, PASS);

    $sql = $pdo->prepare('SELECT * FROM Account WHERE user_name = ? and pass = ?');
    $sql->execute([$_POST['user_name'], $_POST['pass']]);
    
    $row = $sql->fetch();
    $count = $sql -> rowCount();
    if ($count == 1) {
        $_SESSION['user']=[
            'user_name'=>$row['user_name'],
            'display_name'=>$row['display_name'],
            'aikon'=>$row['aikon'],
            'profile'=>$row['profile'],
            'mail'=>$row['mail'], 
            'pass' => $row['pass']
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
    <h1 class="h1-1">Syumitter</h1>
    <div class="frame">
        <p style="font-weight: bold;">login</p>
        <form action="login.php" method="POST">
            <input class="textbox" type="text" name="user_name" maxlength="20" placeholder="ユーザー名"><br>
            <input class="textbox" type="text" name="pass" maxlength="12" placeholder="パスワード"><br>
            <p><input type = "checkbox" name = "login">
                ログイン状態を保持する</p>

            <button class="nextbutton" type="submit">ログイン</button>
        </form>
    </div>

    <p><a href="creation_tag.php">新規作成</a></p>
    <p><a href="password.php">パスワードを忘れた</p>

</body>
</html>