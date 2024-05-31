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
        .results-container {
            padding-top: 140px; /* ヘッダーの高さを考慮 */
            overflow-y: auto;
            height: calc(100vh - 160px); /* フッターの高さを考慮して調整 */
            background-color: white;
            padding-bottom: 60px; /* フッターの高さを考慮して余白を追加 */
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
        .grid-container {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0; /* ギャップをゼロに */
            width: 100vw; /* 全幅を使う */
            box-sizing: border-box; /* 境界線を含む幅 */
        }
        .grid-item {
            background-color: #fff;
            border: 1px solid #000; /* 境界線を追加 */
            overflow: hidden;
            position: relative;
            width: 100%;
            padding-bottom: 100%; /* Maintain a square aspect ratio */
        }
        .grid-item img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .results-container {
            padding-top: 140px; /* ヘッダーの高さを考慮 */
            overflow-y: auto;
            height: calc(100vh - 160px); /* フッターの高さを考慮して調整 */
            background-color: white;
            padding-bottom: 60px; /* フッターの高さを考慮して余白を追加 */
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="back-button" onclick="history.back()"></div>
        <h1>Syumitter</h1>
    </div>

    <div class="results-container">
        <div class="grid-container">
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <div class="grid-item"><img src="/mnt/data/スクリーンショット 2024-05-30 102534.png" alt="User Image"></div>
            <!-- Add more grid-items here as needed -->
        </div>
    </div>

    <div class="footer-container">
        <a href="#"><i class="fas fa-plus"></i></a>
        <a href="#"><i class="fas fa-search"></i></a>
        <a href="#"><i class="fas fa-comments"></i></a>
        <a href="#"><i class="fas fa-user"></i></a>
    </div>
</body>
</html>
