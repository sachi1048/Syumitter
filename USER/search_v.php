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
            <input type="radio" name="nav" value="user" onclick="setNavValue(this.value);">
            ユーザー
        </label>
        <label>
            <input type="radio" name="nav" value="post" onclick="setNavValue(this.value);">
            投稿
        </label>
        <label>
            <input type="radio" name="nav" value="group_chat" onclick="setNavValue(this.value);">
            グループチャット
        </label>
    </nav>

    <div class="search-container">
        <form method="get" action="search_c.php" onsubmit="return searchHobby();">
            <input type="hidden" name="nav" id="navInput">
            <input type="text" name="hobby" placeholder="#趣味を検索する...">
            <button type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
        <div class="trending-tags">
            <a href="#" data-hobby="占い" class="tag tag-uranai" onclick="navigateToHobby(this)">#占い</a>
            <a href="#" data-hobby="釣り" class="tag tag-tsuri" onclick="navigateToHobby(this)">#釣り</a>
            <a href="#" data-hobby="J-POP" class="tag tag-jpop" onclick="navigateToHobby(this)">#J-POP</a>
            <a href="#" data-hobby="カフェ巡り" class="tag tag-cafe" onclick="navigateToHobby(this)">#カフェ巡り</a>
        </div>
    </div>

    <footer>
        <?php require 'menu.php'; ?>
    </footer>

    <script>
        let navValue = '';

        function setNavValue(value) {
            navValue = value;
            document.getElementById('navInput').value = value;
        }

        function navigateToHobby(link) {
            if (!navValue) {
                alert("ナビゲーションを選択してください。");
                return false;
            }
            const hobby = link.getAttribute('data-hobby');
            const url = `search_c.php?method=index&hobby=${hobby}&nav=${navValue}`;
            window.location.href = url;
        }

        function searchHobby() {
            const input = document.querySelector('.search-container input[type="text"]');
            if (input.value.trim() === "") {
                return false; // 空の入力は無視
            }
            if (!navValue) {
                alert("検索種別を選んでください");
                return false; // ナビゲーションが選択されていない場合は検索を無視
            }
            document.getElementById('navInput').value = navValue;
            return true; // 検索を実行
        }
    </script>
</body>
</html>
