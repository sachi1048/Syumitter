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

                タグの削除・追加が完了しました<br>
                <<実行結果>><br>
                削除：
                <?php
                require '../../USER/db-connect.php';
                $pdo=new PDO($connect,USER,PASS);
                if (isset($_POST['tug'])&&($_POST['tugname'])) {
                    $tugid=$_POST['tug'];
                    $tugname=$_POST['tugname'];
                    $counts=0;
                    $sql=$pdo->prepare('delete from Tag where tag_id=?');
                    foreach ($tugname as $count) {
                    if($sql->execute([$tugid[$counts]])) {
                        $counts++;
                        echo "・";
                        echo  htmlspecialchars("{$count}　", ENT_QUOTES, 'UTF-8');
                    }
                    else{
                        echo '削除に失敗しました。';
                    }
                    }
                } else {
                    echo "該当なし";
                }
                ?>
                <br><!--POSTかGETで取得した値を表示 例.削除:該当なし　追加：a,b,c-->
                追加：

                <?php
                if (!empty($_POST['newtugname'])) {
                    $sql=$pdo->prepare('insert into Tag(tag_mei,tag_color1,tag_color2,tag_color3) values (?,?,?,?)');
                if ($sql->execute([$_POST['newtugname'],$_POST['color1'],$_POST['color2'],$_POST['color3']])){
                        $newtugname=$_POST['newtugname'];
                        echo "・";
                        echo $newtugname;
                }else{
                        echo '<font color="red">追加に失敗しました。</font>';
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
    <button class="decision-button" type="button" onclick="location.href='tug_management.html'">完了</button>
</div>
</body>
</html>
