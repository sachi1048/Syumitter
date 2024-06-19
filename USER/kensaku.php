<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Syumitter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fafafa;
            margin: 0;
            padding: 0;
        }
        .header-container {
            text-align: center;
            padding: 20px;
            font-family: "Pacifico", cursive;
            font-size: 36px;
            margin-bottom: 20px;
        }
        .header-container h1 {
            margin: 0;
            background: -webkit-linear-gradient(#ffb380, #ff80bf, #d884e4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        nav {
            background-color: #b0b0b0;
            overflow: hidden;
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }
        nav a {
            display: block;
            color: #000;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            font-size: 16px;
            margin: 0 10px;
            font-weight: bold;
        }
        nav a:hover {
            background-color: #faa0d6;
            color: #333;
        }
        .search-container {
            text-align: center;
            margin-top: 20px;
        }
        .search-container form {
            display: inline-flex;
            align-items: center;
            width: 80%;
        }
        .search-container input[type=text] {
            padding: 10px;
            margin: 5px 0 5px 5px;
            width: 100%;
            border: 2px solid;
            border-image-slice: 1;
            border-width: 2px;
            border-image-source: linear-gradient(to right, #ff9db4, #ff69b4, #ff9db4, #75c4eb);
            border-radius: 5px;
            font-size: 16px;
            color: #333;
            background-color: #fff;
            box-sizing: border-box;
            padding-right: 40px;
        }
        
        .search-container button {
            position: relative;
            right: 35px;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 20px;
            color: #b0b0b0;
        }
        .search-container button i {
            margin: 0;
        }
        .trending-tags {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 10px;
        }
        .trending-tags .tag {
            display: inline-block;
            padding: 10px 15px;
            margin: 5px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            background-color: #fff; /* 背景を白に設定 */
            border: 2px solid; /* 枠線を追加 */
            transition: background 0.3s ease, color 0.3s ease; /* スムーズな色の変化 */
        }
        .tag-uranai {
            color: #ff80bf;
            border-color: #ff80bf; /* 枠線の色を設定 */
        }
        .tag-uranai:hover {
            background-color: #ff80bf;
            color: #fff;
        }
        .tag-tsuri {
            color: #75c4eb;
            border-color: #75c4eb;
        }
        .tag-tsuri:hover {
            background-color: #75c4eb;
            color: #fff;
        }
        .tag-jpop {
            color: #ffd700;
            border-color: #ffd700;
        }
        .tag-jpop:hover {
            background-color: #ffd700;
            color: #fff;
        }
        .tag-cafe {
            color: #ff69b4;
            border-color: #ff69b4;
        }
        .tag-cafe:hover {
            background-color: #ff69b4;
            color: #fff;
        }
        .footer-container {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-image: linear-gradient(to bottom right, #ffb380, #ff80bf, #d884e4, #add8e6);
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
        }
        .footer-container a {
            display: block;
            color: #fff;
            text-align: center;
            text-decoration: none;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            font-size: 24px;
            font-weight: bold;
            padding: 5px;
        }
        .footer-container a:hover {
            background-color: #faa0d6;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <h1>Syumitter</h1>
    </div>

    <nav>
        <a href="user.php">ユーザー</a>
        <a href="post.php">投稿</a>
        <a href="group_chat.php">グループチャット</a>
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

    <footer><?php require 'menu.php';?></footer>

    <script>
        function searchHobby() {
            var input = document.querySelector('.search-container input[type="text"]');
            if (input.value.trim() === "") {
                return false; // 空の入力は無視
            }
            return true; // 検索を実行
        }
    </script>
</body>
</html>
