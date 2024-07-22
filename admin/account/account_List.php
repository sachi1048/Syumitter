<?php
// DB接続
    session_start();
    $_SESSION['account']='freeze';
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
    <form action="account_invalid.php" method="post">
        <div class="center">
            <div class="container">
                <div class="table">
                    <table>
                    <p align="left">ユーザ名、メールアドレス、チェックボックスをクリックし、凍結したいアカウントを選択してください</p>
                        <thead>
                            <tr>
                                <th>ユーザ名</th>
                                <th class="master">メールアドレス</th>
                                <th>選択</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // 凍結されていないアカウントをすべて表示
                        $sql = $pdo->query('SELECT * FROM Account WHERE freeze_code <> 1');
                        $count = 0;
                        foreach ($sql as $row) {
                            echo '<tr>';
                            echo '<td><label for="', $count, '">', htmlspecialchars($row['user_name']), '</label></td>';
                            echo '<td><label for="', $count, '">', htmlspecialchars($row['mail']), '</label></td>';
                            echo '<td><input type="checkbox" id="', $count, '" name="selectedOptions[]" value="', htmlspecialchars($row['user_name']), '"></td>';
                            echo '</tr>';
                            $count++;
                        }
                        ?>

                        </tbody>
                    </table>
        
        <div class="decision2">
        <button class="decision-button" type="submit">次へ</button>
        </div>

        
                </div>
            </div>
        </div>
    </form>
</body>
</html>