<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Syumitter - 検索結果</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="CSS/searchList_v.css">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="header-container">
        <div class="back-button" onclick="history.back()"></div>
        <h1 class="h1-2">Syumitter</h1>
        <button class="search-button"><?php echo $_GET['hobby'];?></button>
    </div>
    <div class="results-container">
        <?php foreach ($results as $row) { ?>
        <a href="profile.php?user_name=<?php echo $row['user_name']; ?>">
            <div class="user-card">
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
        </a>
        <?php } ?>
    </div>
    <br><br><br><br><br><br>
    <footer><?php include 'menu.php';?></footer>

    <script>
        // 任意のJavaScriptコードをここに追加
    </script>
</body>
</html>