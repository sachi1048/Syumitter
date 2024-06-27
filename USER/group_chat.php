<?php session_start();?>
<?php
    require 'db-connect.php';
    $pdo = new PDO($connect,USER,PASS);
    if(!isset($_SESSION['groupid'])){
        $_SESSION['groupid']=$_GET['group_id'];
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['messagesend'])) {
            $currentDateTime = date('Y-m-d H:i:s');
            $sdl=$pdo->prepare('insert into Group_Rireki values(null,?,?,?,?,default)');
            $sdl->execute([$_POST['groupchatname'],$_SESSION['user']['user_name'],$_POST['message'],$currentDateTime]);
        }
    }
    // 既読機能（諦めるかも）
    $wsq=$pdo->prepare('select * from Group_Rireki where chat_id = ? and sender <> ?');
    $wsq->execute([$_SESSION['groupid'],$_SESSION['user']['user_name']]);
    foreach($wsq as $rol){
        $sot=$pdo->prepare('select * from Group_Kidoku where user_name = ? and rireki_id = ?');
        $sot->execute([$_SESSION['user']['user_name'],$rol['rireki_id']]);
        $result = $sot->fetch(PDO::FETCH_ASSOC);
        if($result === false){// データがないことを確認
            $sss=$pdo->prepare('insert into Group_Kidoku values(?,?)');
            $sss->execute([$_SESSION['user']['user_name'],$rol['rireki_id']]);
        }
        $woo=$pdo->prepare('select count(*) as "count" from Group_Kidoku where rireki_id=?');
        $woo->execute([$rol['rireki_id']]);
        $count=$woo->fetch();
        $cou=$pdo->prepare('update Group_Rireki set kidoku=? where rireki_id=?');
        $cou->execute([$count['count'],$rol['rireki_id']]);
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>グループチャット画面</title>
    <link rel="stylesheet" href="CSS/group_chat.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <script>
        window.onload = function() {
            window.scrollTo(0, document.body.scrollHeight);
        }
        // ページを30秒ごとにリロードする
        setInterval(function() {
            location.reload();
        }, 20000); // 20000ミリ秒 = 20秒
    </script>
</head>
<body class="boda">
    <?php
        // このチャットの名前と所属人数を取り出す
        $spl=$pdo->query('SELECT gc.group_mei,COUNT(gm.member) AS member_count FROM Group_chat gc LEFT JOIN Group_member gm ON gc.group_id = gm.group_id WHERE gm.group_id = 1 GROUP BY gc.group_mei;');// ←未完成
        $result = $spl->fetch(PDO::FETCH_ASSOC);
        // 上の戻るボタンとグループ名（所属人数）、メニューボタン
        echo '<div class="waku">';
        echo '<a href="myprofile.php"><span class="btn-mdr2"></span></a>';
        echo '<div class="tablename">',$result['group_mei'],'(',$result['member_count'],')</div>';
        echo '<form action="#" method="post">';
        echo '<input type="hidden" name="chat_id" value="1">';
        echo '<button class="menuicon" type="submit"><i class="fas fa-bars fa-2x"></i></button>';
        echo '</form>';
        echo '</div>';
        echo '<br>';
        $sql=$pdo->prepare('select * from Group_Rireki where chat_id = ?');
        $sql->execute([$_SESSION['groupid']]);
        // 最初の一回だけ日付を表示させるためにboolean型の変数flgにtrueを入れる
        $flg = true;
        echo '<div class="chatcontainer" style="margin-top:60px;">';
        foreach($sql as $row){
            // 最初のループでは変数hozdayに日付の比較データを渡します。
            if($flg){
                $hozday = date('Y-m-d', strtotime($row['send_time']));
            }
            // 作成済み！ー日付が変わっていたら日付を表示
            $time_only = date('H:i', strtotime($row['send_time']));
            $day = date('Y年m月d日', strtotime($row['send_time']));
            if($hozday!=$day || $flg){
                $date = new DateTime($row['send_time']);
                // 曜日を取得（日本語で表示）
                $dayOfWeek = $date->format('w'); // 0 (日曜日) から 6 (土曜日)
                $days = ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'];
                $dayName = $days[$dayOfWeek];
                echo '<div class="datedisplay">',$day,'　',$dayName,'</div>';
                $hozday=$day;
                if($flg){
                    $flg = false;
                }
            }
            // 送り主が自分であるのなら
            if($_SESSION['user']['user_name']==$row['sender']){
                $sol=$pdo->prepare('select * from Account where user_name = ?');
                $sol->execute([$row['sender']]);
                $kekka=$sol->fetch(PDO::FETCH_ASSOC);
                echo '<div class="message self">';
                echo '<div class="details">既読',$row['kidoku'],'<br>',$time_only,'</div>';
                echo '<div class="content">',$row['naiyou'],'</div>';
                echo '<img class="icon" src="img/aikon/',$kekka['aikon'],'" alt="自分のアイコン画像">';
                echo '</div>';
            // 送り主が相手なら
            }else{
                $sol=$pdo->prepare('select * from Account where user_name = ?');
                $sol->execute([$row['sender']]);
                $kekka=$sol->fetch(PDO::FETCH_ASSOC);
                echo '<div class="message other">';
                echo '<img class="icon" src="img/aikon/',$kekka['aikon'],'" alt="自分のアイコン画像">';
                echo '<div class="content">',$kekka['display_name'],'<br>',$row['naiyou'],'</div>';
                echo '<div class="timestamp">',$time_only,'</div>';
                echo '</div>';
            }
        }
        echo '</div>';
    ?>
    <form action="group_chat.php" method="post">
        <div class="sendmessage">
            <input class="messages" inputmode="text" name="message" placeholder="メッセージを入力" required>
            <input type="hidden" name="groupchatname" value="<?= $_SESSION['groupid'] ?>"><!-- ここは動くようになってから直しましょうかね -->
            <button class="sousin" type="submit" name="messagesend"><i class="fab fa-telegram-plane fa-2x"></i></button>
        </div>
    </form>
</body>
</html>