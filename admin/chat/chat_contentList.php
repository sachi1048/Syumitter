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
        <div class="container2">
        <div class="content">
        <p align="center">削除する動画・画像を選択してください</p>

        <ul>
            <form method="post" action="chat_complete.php">
            <li>
            
            <?php
            require '../../USER/db-connect.php';
            $pdo=new PDO($connect,USER,PASS);
            $count=0;
                $linecount=0;
                foreach($sql=$pdo->query('SELECT toukou_id,contents FROM Toukou WHERE contents!="delete.png"') as $row): ?>
                <input type="checkbox" name="content[]" id="<?php echo $count; ?>" value="<?php echo htmlspecialchars($row['toukou_id'], ENT_QUOTES, 'UTF-8'); ?>" onclick="updateHiddenField(this)">
                <label for="<?php echo $count; ?>"><img src="../../USER/img/toukou/<?php echo htmlspecialchars($row['contents'], ENT_QUOTES, 'UTF-8'); ?>" width="100" height="100"></label>
                <input type="hidden" name="toukoucontent[]" id="hidden_<?php echo $count; ?>" value="<?php echo htmlspecialchars($row['contents'], ENT_QUOTES, 'UTF-8'); ?>">
                <?php
                $count++; 
                $linecount++;
                if($linecount>=4){
                echo "</li><li>";
                $linecount=0;
                }
            ?>
            <?php endforeach; ?>
            </li>
        </ul>
</div>

<div class="decisions">
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