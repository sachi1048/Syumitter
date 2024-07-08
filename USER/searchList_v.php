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
        <div class="user-card">
        <?php foreach ($results as $row) { ?>
            <div class="user-icon">
                <img src="<?php echo "img/aikon/".$row['aikon']?>" alt="User 1">
            </div>
            <div class="user-info">
                <h3><?php echo $row['user_name']; ?></h3>
                <p>
                    <a class="tag-uranai">#<?php echo $row['tag_mei']; ?></a>
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