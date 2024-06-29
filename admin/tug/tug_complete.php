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

                タグの削除・追加が完了しました<br>
                <<実行結果>><br>
                削除：
                <?php
                if (!empty($_POST['tug'])) {
                    $tugs=$_POST['tug'];
                    foreach ($tugs as $count) {
                        echo  htmlspecialchars("{$count}　", ENT_QUOTES, 'UTF-8');
                    }
                } else {
                    echo "該当なし";
                }
                ?>
                <br><!--POSTかGETで取得した値を表示 例.削除:該当なし　追加：a,b,c-->
                追加：

                <?php
                if (!empty($_POST['newtugname'])) {
                    $newtugname=$_POST['newtugname'];
                    echo $newtugname;

                } else {
                    echo "該当なし";
                }
                ?>
                </div>
            </div>
        </div>
    </div>

    <div class="decision">
    <button class="decision-button" type="button" onclick="location.href='tug_management.html'">完了</button>
</div>
</body>
</html>
