<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理画面</title>
    <link rel="stylesheet" href="../css/management.css">
</head>

<body>
    <button class="back-button" type="button" onclick="location.href='../main.html'">メインへ戻る</button>

    <div class="center">
        <div class="container">
            <div class="frame">
                
                チャット・画像・動画の削除が完了しました<br>
                <<実行結果>><br><!--POSTかGETで取得した値を表示 例.削除:該当なし　追加：a,b,c-->
                チャット削除：
                <?php
                require '../../USER/db-connect.php';
                $pdo=new PDO($connect,USER,PASS);
                if (isset($_POST['toukou'])&&($_POST['toukoucontent'])) {
                    $toukou=$_POST['toukou'];
                    $toukoucontent=$_POST['toukoucontent'];
                    $counts=0;
                    foreach ($toukou as $count) {
                        echo  htmlspecialchars("{$count}　", ENT_QUOTES, 'UTF-8');
                        echo $toukoucontent[$counts];
                        echo "　";
                        $counts++;
                    }
                } else {
                    echo "該当なし";
                }
                ?>
                <br>
                画像・動画削除：
                <?php
                if (!empty($_POST['content'])) {
                    $content=$_POST['content'];
                    foreach ($content as $count) {
                        echo  htmlspecialchars("{$count}　", ENT_QUOTES, 'UTF-8');
                    }
                } else {
                    echo "該当なし";
                }
                ?>

                </div>
            </div>
        </div>
    </div>

    <div class="decision">
    <button class="decision-button" type="button" onclick="location.href='chat_management.html'">完了</button>
</div>
</body>
</html>
