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
                        <th class="master">チャットID</th>
                        <th>選択</th>
                    </tr>
                </thead>
                <tbody>
                <form method="post" action="chat_complete.php">
                <?php 
                $chat= array('1','2','3','4');
                $count=0;
                foreach($chat as $chats): ?>
                <tr>
                <td><label for="<?php echo $count; ?>"><?php echo htmlspecialchars($chats, ENT_QUOTES, 'UTF-8'); ?></label></td>
                <td><input type="checkbox" name="chat[]" id="<?php echo $count; ?>" value="<?php echo htmlspecialchars($chats, ENT_QUOTES, 'UTF-8'); ?>"></td>
                </tr>
                <?php
                $count++;
                endforeach; ?>

                </tbody>
            </table>
            <p align="left">チェックボックスまたはチャットIDをクリックし、削除するチャットを選択してください。</p>
        </div>
        </div>
    </div>
    <div class="decision">
    <input type="submit" value="作成"class="decision-button">
</div>
</body>
</html>