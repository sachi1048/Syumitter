<?php session_start(); ?>
<?php require 'db-connect.php'; ?>

<?php
$pdo = new PDO($connect, USER, PASS);
$user_name = $_SESSION['user']['user_name'];
$display_name = $_SESSION['user']['display_name'];
$aikon = $_SESSION['user']['aikon'];

// 選択されたメンバーを受け取る
$selectedMembers = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['members']) && is_array($_POST['members'])) {
    $selectedMembers = $_POST['members'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/menu.css">
    <title>新規グループチャット作成画面</title>
</head>
<body>
    <h1 class="h1-2">Syumitter</h1>
    <a href="group_list.php">
        <span class="btn-mdr2"></span>
    </a>

    <div class="frame">
        <h2>新規グループチャット作成</h2>

    <form action="#" method="post">
        <div class="aikon">
            <lable label="file_label">
                <img src="img/aikon/<?php echo $aikon; ?>" class="maru">
                <input type="file" name="aikon">
            </lable>
        </div>
        
        <!-- グループ名の入力 -->
        <input type="text" name="group_name" placeholder="チャット名">
        <br>

        <!-- 趣味タグ選択 -->
        <?php
        // 初期化
        $selectedTags = [];
        // もしも趣味タグ選択画面でタグが選択されていればここに表示される
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selectedOptions']) && is_array($_POST['selectedOptions'])) {
            $count = 0;
            foreach ($_POST['selectedOptions'] as $pow) {
                $sel = $pdo->prepare('select * from Tag where tag_id = ?');
                $sel->execute([$pow]);
                foreach ($sel as $woe) {
                    $count++;
                    echo '<br>';
                    echo '<div class="s-tag" style="background: rgb(', $woe['tag_color1'], ',', $woe['tag_color2'], ',', $woe['tag_color3'], '">', $woe['tag_mei'], '</div>';
                    echo '<input type="hidden" name="tag', $count, '" value="', $woe['tag_id'], '">';
                    echo '<br>';
                }
            }
        }
        ?>
        <button class="btn-tag" type="button" onclick="location.href='tag_sentaku2.php'">＃趣味タグ選択</button>
        <br>

        <!-- 選択されたメンバーの表示 -->
        <?php
        if (!empty($selectedMembers)) {

            echo '<table style="margin:auto;">';
            foreach ($selectedMembers as $member) {
                $stmt = $pdo->prepare('SELECT * FROM Account WHERE user_name = ?');
                $stmt->execute([$member]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    echo '<tr><td>';
                    echo '<div class="aikon">
                            <img src="img/aikon/', htmlspecialchars($row['aikon']), '" alt="アイコン" class="maru2">
                          </div></td>';
                    echo '<td><span>', htmlspecialchars($row['user_name']), '</span></td>';
                    echo '</tr>';
                }
            }
            echo '</table>';
        }
        ?>

        <button class="btn-tag" type="button" onclick="location.href='member-sentaku.php'">メンバー招待</button>
        
        <button class="nextbutton" type="submit">作成</button>
    </form>
    </div>

    <?php require 'menu.php'; ?>
</body>
</html>