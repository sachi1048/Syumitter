<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/menu.css">
    <title>フォロワー画面</title>
</head>
<body>
    <h1 class="h1-2">Syumitter</h1>
    <?php
    $pdo = new PDO($connect, USER, PASS);
    $user_name = $_SESSION['user']['user_name'];
    $display_name = $_SESSION['user']['display_name'];
    $aikon = $_SESSION['user']['aikon'];
    $profile = $_SESSION['user']['profile'];

    echo '<table style="margin: auto;"><tr><td>';
    echo '<div class="aikon" style="margin: 0 10px;">
            <img src="img/aikon/', $aikon, '" alt="マイアイコン" class="maru">
          </div></td>';
    echo '<td><table class="table1">
            <tr>
                <td>
                    <h2 style="margin: 5px;">', $user_name, '</h2>
                </td>
            </tr>
            <tr>
                <td>
                    <h4 style="margin: 5px;">', $display_name, '</h4>
                </td>
            </tr>
          </table>';
    echo '</td><tr></table>';
    ?>

    <div>
        <a href="follow-list.php">フォロワー</a>
        <a href="follower-list.php">フォロー</a>
    </div>

</body>
</html>