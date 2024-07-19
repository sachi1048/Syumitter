<?php
    session_start();
    $_SESSION['account']='deletefreeze';
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
                                <th>選択</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // 凍結されているアカウントのみを表示
                            $count=0;
                                $sql=$pdo->query('select * from Account where freeze_code=1');
                                foreach($sql as $row){
                                    echo '<tr>';
                                    echo '<td>',$row['user_name'],'</td>';
                                    echo '<td>',$row['mail'],'</td>';
                                    // チェックボックスを複数選択可にして配列として次の画面に送る
                                    echo '<td><input type="checkbox" name="deleteoption[]" value="',$row['user_name'],'"></td>';
                                    echo '</tr>';
                                }
                            ?>
                            </tbody>
                    </table>
                    <p align="left">削除する凍結中アカウントを選択してください</p>
                </div>
            </div>
        </div>
        <div class="decision">
            <button class="decision-button" type="submit">削除</button>
        </div>
    </form>
</body>
</html>
