<?php
// DB接続＆SESSIONの使用を宣言
    session_start();
    const SERVER = 'mysql301.phy.lolipop.lan';
    const DBNAME = 'LAA1517472-syumitta';
    const USER = 'LAA1517472';
    const PASS = 'kitagawa';

    $connect = 'mysql:host='.SERVER.';dbname='.DBNAME.';charset=utf8';
    $pdo=new PDO($connect,USER,PASS);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理画面</title>
    <link rel="stylesheet" href="../css/management.css">
</head>

<body>
    <button class="back-button" type="button" onclick="location.href='../main.php'">メインへ戻る</button>
    <div class="center">
        <div class="container">
            <div class="frame">
                アカウントの凍結・削除が完了しました<br>
                <<実行結果>><br>
                <?php
                // `凍結処理だったら
                if(isset($_POST['zyogen'])){
                    echo '凍結：';
                    for($i=1;$i<=$_POST['zyogen'];$i++){
                        $sql=$pdo->prepare('update Account set freeze_code = 1 where user_name = ?');
                        $sql->execute([$_POST[$i]]);
                        $sss=$pdo->prepare('select * from Account where user_name = ?');
                        $sss->execute([$_POST[$i]]);
                        $result = $sss->fetch(PDO::FETCH_ASSOC);
                        echo '',$result['user_name'];
                        if($i != $_POST['zyogen']){
                            echo ',';
                        }
                    }
                    echo '<br>';
                    echo '削除：該当なし';
                // 凍結削除だったら
                }else if(isset($_POST['deleteoption'])){
                    echo '凍結：該当なし<br>';
                    echo '削除：';
                    foreach($_POST['deleteoption'] as $rrw){
                        $ses=$pdo->prepare('update Account set freeze_code = 0 where user_name = ?');
                        $ses->execute([$rrw]);
                        $osi=$pdo->prepare('select * from Account where user_name = ?');
                        $osi->execute([$rrw]);
                        $result = $osi->fetch(PDO::FETCH_ASSOC);
                        echo '',$result['user_name'],',';
                    }
                }
                unset($_SESSION['account']);
                ?>
            </div>
        </div>
    </div>

    <div class="decision">
    <button class="decision-button" type="button" onclick="location.href='account_management.php'">完了</button>
</div>
</body>
</html>