<?php
    session_start();
    require 'db-connect.php';
    $pdo=new PDO($connect,USER,PASS);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['sakuzyo'])) {
            $sql=$pdo->prepare('update Pair_chat set delete_code = 1 where chat_id = ?');
            $sql->execute([$_SESSION['chat_id']]);
            header("Location: pair_chat.php");
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ペアチャット編集画面</title>
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/gromem.css">
</head>
<body>
    <?php
    echo '<a href="pair_chat.php"><span class="btn-mdr2"></span></a>';
    ?>
    <h1 class="h1-1">Syumitter</h1>
    <form action="pair_edit.php" method="post">
        <button class="taikai" type="submit" name="sakuzyo">チャット削除</button>
    </form>
</body>
</html>