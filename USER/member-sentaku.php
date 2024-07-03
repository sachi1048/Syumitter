<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/menu.css">
    <title>新規グループチャットのメンバー選択画面</title>
</head>
<body>
    <h1 class="h1-2">Syumitter</h1>
    <a href="group_new.php">
        <span class="btn-mdr2"></span>
    </a>
    
    <form action="group_new.php" method="POST">
<?php
    $pdo = new PDO($connect, USER, PASS);
    $user_name = $_SESSION['user']['user_name'];
    $display_name = $_SESSION['user']['display_name'];


    $sql=$pdo->prepare('select * from Follow where approver_name=? order by zyoukyou DESC');
    $sql->execute([$user_name]);
    echo '<table  class="table-follow">';
    foreach($sql as $row){
        $sql2=$pdo->query('select * from Account where user_name="'.$row['applicant_name'].'"');
        foreach($sql2 as $row2){
            echo '<tr><td>';
            echo '<div class="aikon">
                    <img src="img/aikon/', $row2['aikon'], '" alt="マイアイコン" class="maru2">
                  </div></td>';

                  //チェックボックスでメンバーを選択できるようにする
                  //ボタンのような見た目でみだり側に来るようにしたらいけるかも
            echo '<td>
                    <a href="profile.php?user_name=', $row2['user_name'], '" style="Text-decoration:none; color:#000000;">
                    <h2>', $row2['user_name'], '</h2>
                  </td>
                  <td>

                  </td></tr>';


        }

    }
    echo '</table>';
    ?>
        <button class="lastbutton" type="submit">決定</button>
    </form>

</body>
</html>