<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Syumitter - 検索結果</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 20px;
            font-family: "Pacifico", cursive;
            font-size: 36px;
            color: #000;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            background-color: white;
            border-bottom: 2px solid #000;
        }
        .back-button {
            position: absolute;
            left: 40px;
            width: 0;
            height: 0;
            border-top: 18px solid transparent;
            border-bottom: 18px solid transparent;
            border-right: 18px solid #0000ff;
            cursor: pointer;
        }
        .back-button:hover {
            border-right-color: #00008b;
        }
        .header-container h1 {
            margin: 0;
            background: -webkit-linear-gradient(#ffb380, #ff80bf, #d884e4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .search-button {
            margin-top: 10px;
            background-color: white;
            color: black;
            border: 2px solid transparent;
            background-image: linear-gradient(white, white), linear-gradient(to bottom right, #ffb380, #ff80bf, #d884e4, #add8e6);
            background-origin: border-box;
            background-clip: padding-box, border-box;
            padding: 10px 30px;
            font-size: 20px;
            border-radius: 20px; /* 角をさらに丸く */
            cursor: pointer;
            font-weight: bold;
        }
        .search-button:hover {
            background-color: #f0f0f0;
        }
        .results-container {
            padding-top: 165px; /* ヘッダーの高さを考慮 */
            overflow-y: auto;
            height: calc(100vh - 200px); /* フッターの高さを考慮して調整 */
            background-color: white;
            padding-bottom: 60px; /* フッターの高さを考慮して余白を追加 */
        }
        .user-card {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            transition: background 0.3s ease;
            color: #333;
            margin-top: 20px; /* ユーザーカードの上部にマージンを追加 */
        }
        .user-card:hover {
            background-color: #f0f0f0;
        }
        .user-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 20px;
            overflow: hidden;
            border: 2px solid #000;
        }
        .user-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .user-card .user-info {
            display: flex;
            flex-direction: column;
        }
        .user-card .user-info h3 {
            margin: 0;
            font-size: 20px;
        }
        .user-card .user-info p {
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .user-card .user-info p a {
            color: #333;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .user-card .user-info p a:hover {
            background-color: #4a90e2;
            color: #fff;
        }
        .tag-uranai {
            background-color: #ff80bf;
        }
        .tag-tsuri {
            background-color: #75c4eb;
        }
        .tag-jpop {
            background-color: #ffd700;
        }
        .tag-cafe {
            background-color: #ff69b4;
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
        <div class="back-button" onclick="history.back()"></div>
        <h1>Syumitter</h1>
        <button class="search-button">#検索</button>
    </div>
    <div class="results-container">
        <div class="user-card">
            <div class="user-icon">
                <img src="user1.jpg" alt="User 1">
            </div>
            <div class="user-info">
                <h3>ユーザー名1</h3>
                <p><a class="tag-uranai" href="search_hobby.php?hobby=#占い">#占い</a> <a class="tag-tsuri" href="search_hobby.php?hobby=#釣り">#釣り</a> <a class="tag-jpop" href="search_hobby.php?hobby=#J-POP">#J-POP</a></p>
            </div>
        </div>
        <div class="user-card">
            <div class="user-icon">
                <img src="user2.jpg" alt="User 2">
            </div>
            <div class="user-info">
                <h3>ユーザー名2</h3>
                <p><a class="tag-uranai" href="search_hobby.php?hobby=#占い">#占い</a> <a class="tag-tsuri" href="search_hobby.php?hobby=#釣り">#釣り</a> <a class="tag-cafe" href="search_hobby.php?hobby=#カフェ巡り">#カフェ巡り</a></p>
            </div>
        </div>
        <div class="user-card">
            <div class="user-icon">
                <img src="user3.jpg" alt="User 3">
            </div>
            <div class="user-info">
                <h3>ユーザー名3</h3>
                <p><a class="tag-jpop" href="search_hobby.php?hobby=#J-POP">#J-POP</a> <a class="tag-uranai" href="search_hobby.php?hobby=#占い">#占い</a> <a class="tag-tsuri" href="search_hobby.php?hobby=#釣り">#釣り</a></p>
            </div>
        </div>
        <div class="user-card">
            <div class="user-icon">
                <img src="user4.jpg" alt="User 4">
            </div>
            <div class="user-info">
                <h3>ユーザー名4</h3>
                <p><a class="tag-jpop" href="search_hobby.php?hobby=#J-POP">#J-POP</a> <a class="tag-uranai" href="search_hobby.php?hobby=#占い">#占い</a> <a class="tag-tsuri" href="search_hobby.php?hobby=#釣り">#釣り</a></p>
            </div>
        </div>
        <div class="user-card">
            <div class="user-icon">
                <img src="user5.jpg" alt="User 5">
            </div>
            <div class="user-info">
                <h3>ユーザー名5</h3>
                <p><a class="tag-uranai" href="search_hobby.php?hobby=#占い">#占い</a> <a class="tag-jpop" href="search_hobby.php?hobby=#J-POP">#J-POP</a> <a class="tag-cafe" href="search_hobby.php?hobby=#カフェ巡り">#カフェ巡り</a></p>
            </div>
        </div>
        <div class="user-card">
            <div class="user-icon">
                <img src="user6.jpg" alt="User 6">
            </div>
            <div class="user-info">
                <h3>ユーザー名6</h3>
                <p><a class="tag-cafe" href="search_hobby.php?hobby=#カフェ巡り">#カフェ巡り</a> <a class="tag-uranai" href="search_hobby.php?hobby=#占い">#占い</a> <a class="tag-tsuri" href="search_hobby.php?hobby=#釣り">#釣り</a></p>
            </div>
        </div>
        <div class="user-card">
            <div class="user-icon">
                <img src="user7.jpg" alt="User 7">
            </div>
            <div class="user-info">
                <h3>ユーザー名7</h3>
                <p><a class="tag-jpop" href="search_hobby.php?hobby=#J-POP">#J-POP</a> <a class="tag-tsuri" href="search_hobby.php?hobby=#釣り">#釣り</a> <a class="tag-uranai" href="search_hobby.php?hobby=#占い">#占い</a></p>
            </div>
        </div>
        <div class="user-card">
            <div class="user-icon">
                <img src="user8.jpg" alt="User 8">
            </div>
            <div class="user-info">
                <h3>ユーザー名8</h3>
                <p><a class="tag-tsuri" href="search_hobby.php?hobby=#釣り">#釣り</a> <a class="tag-uranai" href="search_hobby.php?hobby=#占い">#占い</a> <a class="tag-cafe" href="search_hobby.php?hobby=#カフェ巡り">#カフェ巡り</a></p>
            </div>
        </div>
        <div class="user-card">
            <div class="user-icon">
                <img src="user9.jpg" alt="User 9">
            </div>
            <div class="user-info">
                <h3>ユーザー名9</h3>
                <p><a class="tag-cafe" href="search_hobby.php?hobby=#カフェ巡り">#カフェ巡り</a> <a class="tag-tsuri" href="search_hobby.php?hobby=#釣り">#釣り</a> <a class="tag-uranai" href="search_hobby.php?hobby=#占い">#占い</a></p>
            </div>
        </div>
        <div class="user-card">
            <div class="user-icon">
                <img src="user10.jpg" alt="User 10">
            </div>
            <div class="user-info">
                <h3>ユーザー名10</h3>
                <p><a class="tag-jpop" href="search_hobby.php?hobby=#J-POP">#J-POP</a> <a class="tag-cafe" href="search_hobby.php?hobby=#カフェ巡り">#カフェ巡り</a> <a class="tag-tsuri" href="search_hobby.php?hobby=#釣り">#釣り</a></p>
            </div>
        </div>
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