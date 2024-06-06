<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/menu.css">
    <title>フォロー画面</title>
</head>
<script>
    const follow{

    }
</script>
<body>
    <h1 class="h1-2">Syumitter</h1>
    <a href="myprofile.php">
        <span class="btn-mdr2"></span>
    </a>
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
    <br>
    <div class="switch">
        <a class="link switch-left" href="follower-list.php">フォロワー</a>
        <a class="link switch2" href="follow-list.php">フォロー</a>
    </div>


    <?php
    $sql=$pdo->prepare('select * from Follow where applicant_name=? order by zyoukyou DESC');
    $sql->execute([$user_name]);
    echo '<table>';
    foreach($sql as $row){
        $sql2=$pdo->query('select * from Account where user_name="'.$row['approver_name'].'"');
        foreach($sql2 as $row2){
            echo '<tr><td>';
            echo '<div class="aikon">
                    <img src="img/aikon/', $row2['aikon'], '" alt="マイアイコン" class="maru2">
                  </div></td>';
            echo '<td>
                    <h2>', $row2['user_name'], '</h2>
                  </td>
                  <td>
                    <div>';
                        if($row['zyoukyou'] == 1){
                            echo '<input type="button" value="フォロー中" onclick="follow1">';
                        }else{
                            echo '<input type="button" value="フォローする" onclick="follow2">';
                        }
                echo '</div>
                  </td></tr>';


        }

    }
    echo '</table>';
    ?>



</body>
</html>