<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理画面</title>
    <link rel="stylesheet" href="css/management.css">
</head>

<body>
    <button class="back-button" type="button" onclick="history.back()">戻る</button>
    <div class="center">
        <div class="container2">
        <div class="content">
            <!--<ul>
                <li><input type="checkbox">
                    <img src="icons.png"width="100" height="100" >
                    <input type="checkbox">
                    <img src="icons.png"width="100" height="100" >
                    <input type="checkbox">
                    <img src="icons.png"width="100" height="100" >
                    <input type="checkbox">
                    <img src="icons.png"width="100" height="100" ></li>
                <li><img src="icons.png"width="100" height="100"></li>
            </ul>--><!--DBから取得した画像を配列に格納。ループ表示し、カウント４につき改行する-->
            <ul>
            <li>
                <?php 
                $contents = array('icons.png','icons.jpg','icons.png','icons.png','icons.png','icons.png','icons.jpg','icons.png','icons.jpg','icons.png');
                $count=0;
                foreach($contents as $content): ?>
                    <input type="checkbox">
                    <img src="<?php echo $content; ?>" width="100" height="100">
                    <?php $count++;
                    if($count>=4){
                    echo "</li><li>";
                    $count=0;
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
    <button class="decision-button" type="button" onclick="location.href='chat_complete.html'">削除</button>
</div>
</body>
</html>