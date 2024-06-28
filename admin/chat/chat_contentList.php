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
            <ul>
            <form method="post" action="chat_complete.php">
            <li>
            <?php 
                $contents = array('icons.png','icons.jpg','test.jpg','icons.png','test.jpg','icons.png','icons.png','test.jpg','icons.png','icons.jpg','icons.png','icons.jpg','icons.png','imgs.jpg');
                $count=0;
                $linecount=0;
                foreach($contents as $content): ?>
                <input type="checkbox" name="content[]" id="<?php echo $count; ?>" value="<?php echo htmlspecialchars($content, ENT_QUOTES, 'UTF-8'); ?>">
                <label for="<?php echo $count; ?>"><img src="../img/<?php echo htmlspecialchars($content, ENT_QUOTES, 'UTF-8'); ?>" width="100" height="100"></label>
                
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
            <p align="center">削除する動画・画像を選択してください</p>
        </div>
        </div>
    </div>

    <div class="decision">
    <input type="submit" value="削除"class="decision-button">
    </form>
</div>
</body>
</html>