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
                        <th class="master">タグ名</th>
                        <th>選択</th>
                    </tr>
                </thead>
        <tbody>
                    
            <form method="post" action="tug_complete.php">
            <?php 
            $tug= array('tug1','tug2','tubA','tugB');
            $count=0;
            foreach($tug as $tugs): ?>
            <tr>
            <td><label for="<?php echo $count; ?>"><?php echo htmlspecialchars($tugs, ENT_QUOTES, 'UTF-8'); ?></label></td>
            <td><input type="checkbox" name="tug[]" id="<?php echo $count; ?>" value="<?php echo htmlspecialchars($tugs, ENT_QUOTES, 'UTF-8'); ?>"></td>
            </tr>
            <?php
            $count++;
            endforeach; ?>
        </tbody>
            </table>
            <p align="left">チェックボックスまたはタグ名をクリックし、削除するタグを選択してください。</p>
        </div>
        </div>
    </div>
    <div class="decision">
    <input type="submit" value="作成"class="decision-button">
</div>
</body>
</html>



