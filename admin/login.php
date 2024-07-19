<?php
// DB接続＆SESSIONの使用を宣言
    session_start();
    const SERVER = 'mysql301.phy.lolipop.lan';
    const DBNAME = 'LAA1517472-syumitta';
    const USER = 'LAA1517472';
    const PASS = 'kitagawa';

    $connect = 'mysql:host='.SERVER.';dbname='.DBNAME.';charset=utf8';
    $pdo=new PDO($connect,USER,PASS);
    // もし、管理者アドレスとパスワードを入力の上、この画面に飛んで来たら、ログイン処理を実行
    if(isset($_POST['user'],$_POST['pass'])){
        $sql=$pdo->prepare('select * from Admin_account where account_name = ? and pass = ?');
        $sql->execute([$_POST['user'],$_POST['pass']]);
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        // 管理者アドレスとパスワードが合致するアカウントがあるかつ、それが入力内容と一致するかを再度確認
        if(isset($result['account_name']) && $result['account_name'] == $_POST['user']){
            // 正しいアカウントであれば、main画面に遷移する
            header("Location: main.php");
            exit;
        }else{
            // 誤りの場合、エラーを表示する
            echo '<h2>アドレスorパスワードが間違っています！';
        }
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>管理側ログイン画面</title>
</head>
<body>
    <div class="frame">
        <!-- 情報入力＆その後自分の画面に再度遷移してログイン処理を誘発する -->
        <form action="login.php" method="POST">
            管理者アドレス<br>
            <input class="textbox" type="text" name="user" required><br>
            パスワード<br>
            <input class="textbox" type="text" name="pass" required><br>
            <button class="nextbotton" type="submit">ログイン</button>
        </form>
    </div>
</body>
</html>
