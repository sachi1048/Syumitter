<?php session_start(); 
require 'db-connect.php'; 
$input_tag = isset($_GET['tag']) ? $_GET['tag'] : '';

if (empty($input_tag)) {
    echo "タグを入力してください。";
    exit;
}

// タグIDを取得するSQLクエリ
$pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT tag_id FROM Tag WHERE tag_mei = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$input_tag]);
$tag_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

// タグIDが見つからない場合はエラーメッセージを表示
if (empty($tag_ids)) {
    echo "タグが見つかりませんでした。";
    exit;
}

// プレースホルダの準備
$placeholders = implode(',', array_fill(0, count($tag_ids), '?'));

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Syumitter - 検索結果</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/menu.css">
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
            padding-top: 200px;
            overflow-y: auto;
            height: calc(100vh - 160px);
            background-color: white;
            padding-bottom: 45px;
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
            grid-template-columns: repeat(3, 1fr);
            gap: 0;
            width: 100vw;
            box-sizing: border-box;
        }
        .grid-item {
            background-color: #fff;
            border: 1px solid #000;
            overflow: hidden;
            position: relative;
            width: 100%;
            padding-bottom: 100%;
        }
        .grid-item img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="back-button" onclick="history.back()"></div>
        <h1 class="h1-2">Syumitter</h1>
        <button class="search-button">#検索</button>
    </div>

    <div class="results-container">
        <div class="grid-container">
        <table style="padding-bottom: 100px;">
        <?php 
        $sql = "SELECT * FROM Toukou WHERE tag_id1 IN ($placeholders) OR tag_id2 IN ($placeholders) OR tag_id3 IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_merge($tag_ids, $tag_ids, $tag_ids));
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

         $count = 0;
         echo '<tr>';

         foreach ($results as $row) {
            if ($count % 3 == 0 && $count != 0) {
                echo '</tr><tr>';
            }
            echo '<td>';
            echo '<a href="toukou_disp.php?toukou_id=', $row['toukou_id'], '"><img src="img/toukou/', $row['contents'], '" class="size">';
            echo '</td>';
            $count++;
        }
        if ($count % 3 != 0) {
            echo '</tr>';
        }
        echo '</table>';
    
        ?>
    </table>
        </div>
    </div>

    <footer><?php require 'menu.php'; ?></footer>
</body>
</html>