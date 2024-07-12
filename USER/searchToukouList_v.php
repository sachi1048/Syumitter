<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Syumitter - 検索結果</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="CSS/searchList_v.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="header-container">
        <div class="back-button" onclick="history.back()"></div>
        <h1>Syumitter</h1>
        <button class="search-button">検索</button>
    </div>
    <div class="results-container">
    <?php foreach ($results as $row) { ?>
        <div class="user-card">
            <div class="user-icon">
                <img src="<?php echo "img/toukou/".$row['contents']?>" alt="User 1">
            </div>
            <div class="user-info">
                <h3><?php echo $row['toukou_mei']; ?></h3>
                <h4><?php echo $row['setumei']; ?></h4>
                <p>
                    <a class="tag-uranai" style="background: rgb(<?php echo $row['tag1_color1']; ?>,<?php echo $row['tag1_color2']; ?>,<?php echo $row['tag1_color3']; ?>);">#<?php echo $row['tag_mei1']; ?></a>
                    <a class="tag-uranai" style="background: rgb(<?php echo $row['tag2_color1']; ?>,<?php echo $row['tag2_color2']; ?>,<?php echo $row['tag2_color3']; ?>);">#<?php echo $row['tag_mei2']; ?></a>
                    <a class="tag-uranai" style="background: rgb(<?php echo $row['tag3_color1']; ?>,<?php echo $row['tag3_color2']; ?>,<?php echo $row['tag3_color3']; ?>);">#<?php echo $row['tag_mei3']; ?></a>
                </p>
            </div>
        </div>
        <?php } ?>
    </div>

    <div class="footer-container">
        <a href="post.php"><i class="fa fa-plus-square "></i></a>
        <a href="search_v.php"><i class="fas fa-search"></i></a>
        <a href="group_chat_list.php"><i class="fas fa-comments"></i></a>
        <a href="profile.php"><i class="fas fa-user"></i></a>
    </div>

    <script>
        // 任意のJavaScriptコードをここに追加
    </script>
</body>
</html>