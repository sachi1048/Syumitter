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
    <button class="back-button" type="button" onclick="history.back()">戻る</button>
    <form action="account_complete.php" method="post">
    <div class="center">
        <div class="container">
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>ユーザ名</th>
                        <th class="master">メールアドレス</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // 凍結したいアカウントの情報を前画面からuser_nameとして一つずつに分けて受け取り、その都度名前を付けてhiddenで送信
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (isset($_POST['selectedOptions']) && is_array($_POST['selectedOptions'])) {
                                // user_nameに１つずつ名前を付けるための変数count
                                $count=0;
                                // 一つずつpowに格納＆出力＆送信
                                foreach($_POST['selectedOptions'] as $pow){
                                    $sel=$pdo->prepare('select * from Account where user_name = ?');
                                    $sel->execute([$pow]);
                                    foreach($sel as $woe){
                                        $count++;
                                        echo '<tr>';
                                        echo '<td>',$woe['user_name'],'</td>';
                                        echo '<td>',$woe['mail'],'</td>';
                                        echo '</tr>';
                                        // 送信
                                        echo '<input type="hidden" name="',$count,'" value="',$woe['user_name'],'">';
                                    }
                                    echo '<input type="hidden" name="',$count,'" value="',$pow,'">';
                                }
                            }
                            // 次の画面で、凍結したいアカウントの数をループの上限として送信
                            echo '<input type="hidden" name="zyogen" value="',$count,'">';
                        }
                    ?>
                </tbody>
            </table>
            <p align="left">凍結を押下すると上記アカウントすべてに凍結が実行されます</p>
        </div>
        </div>
    </div>
    <div class="decision">
    <button class="decision-button" type="submit">凍結</button>
</div>
</form>
</body>
</html>


