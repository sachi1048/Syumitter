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
            text-align: center;
            padding: 20px;
            font-family: "Pacifico", cursive;
            font-size: 36px;
            background: linear-gradient(to right, #ffb380, #ff80bf, #d884e4);
            -webkit-background-clip: text;
            color: transparent;
            border-bottom: 2px solid #000;
        }
        .results-container {
            padding: 20px;
            border-top: 2px solid #000;
        }
        .user-card {
            display: flex;
            align-items: center;
            background-color: #fafafa; 
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            transition: background 0.3s ease;
            color: #333;
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
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .user-card .user-info p a:hover {
            background-color: #4a90e2;
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
    </style>
</head>
<body>
    <div class="header-container">
        <h1>Syumitter</h1>
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
    </div>

    <script>
        // 任意のJavaScriptコードをここに追加
    </script>
</body>
</html>