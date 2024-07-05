<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Syumitter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="CSS/menu.css">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/search_v.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <h1 class="h1-2">Syumitter</h1>

    <nav>
        <label>
            <input type="radio" name="nav" value="user.php" onclick="navigate(this.value);">
            ユーザー
        </label>
        <label>
            <input type="radio" name="nav" value="post.php" onclick="navigate(this.value);">
            投稿
        </label>
        <label>
            <input type="radio" name="nav" value="group_chat.php" onclick="navigate(this.value);">
            グループチャット
        </label>
    </nav>

    <div class="search-container">
        <form method="get" action="search_hobby.php" onsubmit="return searchHobby();">
            <input type="text" name="hobby" placeholder="#趣味を検索する...">
            <button type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
        <div class="trending-tags">
            <a href="search_hobby.php?hobby=#占い" class="tag tag-uranai">#占い</a>
            <a href="search_hobby.php?hobby=#釣り" class="tag tag-tsuri">#釣り</a>
            <a href="search_hobby.php?hobby=#J-POP" class="tag tag-jpop">#J-POP</a>
            <a href="search_hobby.php?hobby=#カフェ巡り" class="tag tag-cafe">#カフェ巡り</a>
        </div>
    </div>

    <footer>
        <?php require 'menu.php'; ?>
    </footer>

    <script>
        function searchHobby() {
            var input = document.querySelector('.search-container input[type="text"]');
            if (input.value.trim() === "") {
                return false; // 空の入力は無視
            }
            return true; // 検索を実行
        }

        function navigate(url) {
            window.location.href = url;
        }
    </script>
</body>

</html>
