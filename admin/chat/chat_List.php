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
        <p align="left">チェックボックス・投稿ID・投稿内容をクリックし、削除する投稿を選択してください。</p>
        <form method="post" action="chat_complete.php">
            <table>
                <thead>
                    <tr>
                        <th>投稿ID</th>
                        <th class="master">投稿内容</th>
                        <th>選択</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                require '../../USER/db-connect.php';
                $pdo=new PDO($connect,USER,PASS);
                $count=0;
                foreach($sql=$pdo->query('SELECT toukou_id,setumei FROM Toukou WHERE setumei!="管理者によりこの投稿は削除されました。"') as $row): ?>
                <tr>
                <td><label for="<?php echo $count; ?>"><?php echo htmlspecialchars($row['toukou_id'], ENT_QUOTES, 'UTF-8'); ?></label></td>
                <td><label for="<?php echo $count; ?>"><?php echo htmlspecialchars($row['setumei'], ENT_QUOTES, 'UTF-8'); ?></label></td>
                <td><input type="checkbox" name="toukou[]" id="<?php echo $count; ?>" value="<?php echo htmlspecialchars($row['toukou_id'], ENT_QUOTES, 'UTF-8'); ?>" onclick="updateHiddenField(this)"></td>
                </tr>
                <input type="hidden" name="toukoucontent[]" id="hidden_<?php echo $count; ?>" value="<?php echo htmlspecialchars($row['setumei'], ENT_QUOTES, 'UTF-8'); ?>">
                <?php
                $count++;
                endforeach; ?>

                </tbody>
        </table>

    <div class="decision2">
    <p><input type="submit" value="削除"class="decision-button"></p>
    </div>
    </form>
  
    </div>
    </div>
    </div>



    <script>
        function updateHiddenField(checkbox) {
            const hiddenField = document.getElementById(`hidden_${checkbox.id}`);
            if (checkbox.checked) {
                hiddenField.disabled = false;
            } else {
                hiddenField.disabled = true;
            }
        }

        window.onload = function() {
            const hiddenFields = document.querySelectorAll('input[type="hidden"]');
            hiddenFields.forEach(field => field.disabled = true);
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
        };
    </script>
</body>
</html>