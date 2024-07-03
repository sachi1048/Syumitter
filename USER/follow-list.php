<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/menu.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>フォロー画面</title>
</head>

<body>
    <?php
    $pdo = new PDO($connect, USER, PASS);
    //ユーザーのDBを参照
    $user=$pdo->prepare('select * from Account where user_name=? ');
    $user->execute([$_GET['user_name']]);
    $rr = $user->fetch(PDO::FETCH_ASSOC);
    if($rr){

    $user_name = $rr['user_name'];
    $display_name = $rr['display_name'];
    $aikon = $rr['aikon'];
    $profile = $rr['profile'];

    echo '<h1 class="h1-2">Syumitter</h1>
            <a href="profile.php?user_name=', $user_name, '">
                <span class="btn-mdr2"></span>
            </a>';
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
        <a class="link switch-left" href="follower-list.php?user_name=<?php echo $user_name; ?>">フォロワー</a>
        <a class="link switch2" href="follow-list.php?user_name=<?php echo $user_name; ?>">フォロー</a>
    </div>


    <?php
    //applicant_name=フォロー側　approver_name=フォローされてる側
    //フォロー中のアカウントだけを表示
    $sql=$pdo->prepare('select * from Follow where applicant_name=? order by zyoukyou DESC');
    $sql->execute([$user_name]);
    echo '<table class="table-follow">';
    foreach($sql as $row){
        //フォロー中のアカウントを検索
        $sql2=$pdo->query('select * from Account where user_name="'.$row['approver_name'].'"');
        foreach($sql2 as $row2){
            echo '<tr><td>';
            echo '<div class="aikon">
                    <img src="img/aikon/', $row2['aikon'], '" alt="マイアイコン" class="maru2">
                  </div></td>';
            echo '<td>
                  <a href="profile.php?user_name=', $row2['user_name'], '" style="Text-decoration:none; color:#000000;">        
                    <h2>', $row2['user_name'], '</h2>
                  </a>
                  </td>
                  <td>
                    <div class="btn-follow0">';
                        if($row['applicant_name']==$user_name && $row['approver_name'] == $row2['user_name']){
                            echo '<button id="follow" class="btn-follow1">フォロー中</button>';
                        }else{
                            echo '<button id="follow" class="btn-follow2">フォローする</button>';
                        }
                echo '</div>
                  </td></tr>';


        }

    }
    echo '</table>';
}
    ?>


<script>
    $(document).ready(function(){
        $('#follow').on('click', function(){
             // PHP変数をJavaScript変数に変換
             var approverName = "<?php echo $row['approver_name']; ?>";
            $.ajax({
                url: 'api.php',
                type: 'POST',
                data: {
                    approver_name: approverName
                    // 必要に応じてデータをここに追加
                },
                success: function(response) {
                    // 成功時の処理
                    console.log('API call successful.');
                    console.log(response);
                    
                    // ボタンのテキストとクラスを切り替える
                    $('#follow').toggleClass('btn-follow1 btn-follow2');
                    if($('#follow').hasClass('btn-follow1')) {
                        $('#follow').text('フォロー中');
                    } else {
                        $('#follow').text('フォローする');
                    }
                },
                error: function(xhr, status, error) {
                    // エラー時の処理
                    console.error('API call failed.');
                    console.error(status, error);
                }
            });
        });
    });
</script>

</body>
</html>