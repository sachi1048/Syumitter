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
    <div class="center">
        <div class="container">
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>ユーザ名</th>
                        <th class="master">メールアドレス</th>
                        <th>選択</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <form method="post" action="account_invalid.php">
                            <?php 
                            $users=[["user1","a@a.co.jp","1"],["user2","b@b.co.jp","2"],["user3","c@c.co.jp","3"]];
                            $id;
                            $count=0;
                            $datacount=0;
                            foreach($users as $row){ 
                            foreach($row as $data){ ?>
                            <td><label for="<?php echo $count; ?>"><?php echo htmlspecialchars($data, ENT_QUOTES, 'UTF-8'); ?></label></td>
                            
                        <?php
                        $datacount++;
                        if($datacount>1){
                            $datacount=0;
                            break;
                        }
                        }
                        ?>
                        <td><input type="checkbox" name="accounts[]" id="<?php echo $count; ?>" value="<?php echo htmlspecialchars($users[$count][2], ENT_QUOTES, 'UTF-8'); ?>"></td>
                        <?php
                        $count++;
                        ?>
                        </tr>
                        <?php
                        }
                        ?>
                </tbody>
            </table>
            <p align="left">凍結したいアカウントを選択してください</p>
        </div>
        </div>
    </div>
    <div class="decision">
    <input type="submit" value="次へ"class="decision-button">
    </form>
</div>
</body>
</html>