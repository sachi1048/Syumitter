<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Syumitter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- レスポンシブ対応のためのviewport設定 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome CDNリンク -->
    <style>
        /* スタイルを追加 */
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fafafa; /* 背景色を設定 */
            margin: 0; /* 余白を0に設定 */
            padding: 0; /* 余白を0に設定 */
        }
        .header-container {
            text-align: center;
            padding: 20px; /* ヘッダーの余白を設定 */
            font-family: "Pacifico", cursive; /* カーソルなフォントを設定 */
            font-size: 36px; /* フォントサイズを設定 */
            margin-bottom: 20px; /* ヘッダーの下に余白を追加 */
            background: linear-gradient(to right, #ffb380, #ff80bf, #d884e4); /* ピンクの前に薄いオレンジを追加 */
            -webkit-background-clip: text;
            color: transparent; /* グラデーション効果を文字に適用 */
        }
        nav {
            background-color: #b0b0b0; /* 灰色の背景色を設定 */
            overflow: hidden;
            display: flex;
            justify-content: center;
            padding: 10px 0; /* ナビゲーションの余白を設定 */
        }
        nav a {
            display: block;
            color: #000; /* テキスト色を黒に設定 */
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; /* デフォルトのフォントを設定 */
            font-size: 16px; /* フォントサイズを設定 */
            margin: 0 10px; /* リンク間の余白を設定 */
            font-weight: bold; /* 文字を太字に設定 */
        }
        nav a:hover {
            background-color: #faa0d6; /* ホバー時の背景色を設定 */
            color: #333; /* ホバー時のテキスト色を設定 */
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
            width: 100%; /* 幅を100%に設定 */
            border: 2px solid;
            border-image-slice: 1; /* グラデーションの枠のスライスを設定 */
            border-width: 2px; /* 枠の幅を設定 */
            border-image-source: linear-gradient(to right, #ff9db4, #ff69b4, #ff9db4, #75c4eb); /* グラデーションの色を明るく調整 */
            border-radius: 5px; /* 角を丸く */
            font-size: 16px; /* フォントサイズを設定 */
            color: #333; /* テキスト色を設定 */
            background-color: #fff; /* 背景色を白に設定 */
            box-sizing: border-box; /* ボックスのサイズを調整 */
            padding-right: 40px; /* アイコンのスペースを確保 */
        }
        .search-container button {
            position: relative;
            right: 35px; /* アイコンの位置を調整 */
            border: none;
            background: none;
            cursor: pointer;
            font-size: 20px; /* フォントサイズを大きく設定 */
            color: #b0b0b0; /* アイコンの色を灰色に設定 */
        }
        .search-container button i {
            margin: 0; /* アイコンに余白を設定しない */
        }
        .footer-container {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-image: linear-gradient(to bottom right, #ffb380, #ff80bf, #d884e4, #add8e6); /* 左上から右下に向かってグラデーションを設定 */
            display: flex;
            justify-content: space-around;
            padding: 10px 0; /* フッターの余白を設定 */
        }
        .footer-container a {
            display: block;
            color: #fff; /* テキスト色を白に設定 */
            text-align: center;
            text-decoration: none;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; /* デフォルトのフォントを設定 */
            font-size: 24px; /* フォントサイズを設定 */
            font-weight: bold; /* 文字を太字に設定 */
            padding: 5px; /* 余白を追加 */
        }
        .footer-container a:hover {
            background-color: #faa0d6; /* ホバー時の背景色を設定 */
            color: #333; /* ホバー時のテキスト色を設定 */
        }
    </style>
</head>
<body>
    <div class="header-container">
        <h1>Syumitter</h1>
    </div>


    <nav>
        <a href="user.php">ユーザー</a> <!-- ユーザー画面に遷移するリンク -->
        <a href="post.php">投稿</a> <!-- 投稿画面に遷移するリンク -->
        <a href="group_chat.php">グループチャット</a> <!-- グループチャット画面に遷移するリンク -->
    </nav>

    <div class="search-container">
        <form method="get" action="search_hobby.php" onsubmit="return searchHobby();">
            <input type="text" name="hobby" placeholder="#趣味を検索する...">
            <button type="submit">
                <i class="fas fa-search"></i> <!-- Font Awesomeの虫眼鏡アイコン -->
            </button>
        </form>
    </div>

    <div class="footer-container">
        <a href="post.php"><i class="fa fa-plus-square "></i> </a> <!-- 投稿画面に遷移するアイコン -->
        <a href="search.php"><i class="fas fa-search"></i></a> <!-- 検索画面に遷移するアイコン -->
        <a href="group_chat_list.php"><i class="fas fa-comments"></i></a> <!-- グループチャット一覧画面に遷移するアイコン -->
        <a href="profile.php"><i class="fas fa-user"></i></a> <!-- マイプロフィール画面に遷移するアイコン -->
    </div>

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