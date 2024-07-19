<?php session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);
$user_name = $_SESSION['user']['user_name'];
$display_name = $_SESSION['user']['display_name'];
$aikon = $_SESSION['user']['aikon'];


// 趣味タグとメンバーのセッションを初期化または更新
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['selectedOptions'])) {
        $_SESSION['selectedTags'] = $_POST['selectedOptions'];
    }
    if (isset($_POST['members'])) {
        $_SESSION['selectedMembers'] = $_POST['members'];
    }
}

// セッションから選択されたデータを取得
$selectedTags = isset($_SESSION['selectedTags']) ? $_SESSION['selectedTags'] : [];
$selectedMembers = isset($_SESSION['selectedMembers']) ? $_SESSION['selectedMembers'] : [];


$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['members']) && is_array($_POST['members'])) {
        $_SESSION['selectedMembers'] = $_POST['members'];
        $selectedMembers = $_POST['members'];
    }
    if (isset($_POST['selectedOptions']) && is_array($_POST['selectedOptions'])) {
        $_SESSION['selectedTags'] = $_POST['selectedOptions'];
        $selectedTags = $_POST['selectedOptions'];
    }

    // バリデーション
    if (empty($_POST['group_name'])) {
        $errors[] = 'グループ名を入力してください。';
    }
    if (empty($selectedTags)) {
        $errors[] = '趣味タグを選択してください。';
    }
    if (empty($selectedMembers)) {
        $errors[] = 'メンバーを選択してください。';
    }

    // エラーがなければ新しいグループをDBに挿入
    if (empty($errors)) {
        $group_name = $_POST['group_name'];
        $tag_id = $selectedTags[0]; //タグが一つしか選択できないので
        $aikonn = $aikon; // ファイル名を取得

        // ファイルが選択された場合
        if (isset($_FILES['aikon']) && $_FILES['aikon']['error'] == UPLOAD_ERR_OK) {
            $aikonn = basename($_FILES['aikon']['name']); // ファイル名を取得
            $upload_dir = 'img/aikon/';
            $upload_file = $upload_dir . $aikonn;

            // ファイルのアップロード処理
            if (!move_uploaded_file($_FILES['aikon']['tmp_name'], $upload_file)) {
                $errors[] = 'ファイルのアップロードに失敗しました。';
            }
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare('INSERT INTO Group_chat (group_mei, creator_mei, aikon, tag_id) VALUES (?, ?, ?, ?)');
            $stmt->execute([$group_name, $user_name, $aikonn, $tag_id]);

            // 挿入したグループのIDを取得
            $group_id = $pdo->lastInsertId();

            // 作成者をグループメンバーとして追加
            $stmt = $pdo->prepare('INSERT INTO Group_member (group_id, member) VALUES (?, ?)');
            $stmt->execute([$group_id, $user_name]);

            // 選択されたメンバーをグループに追加
            foreach ($selectedMembers as $member) {
                $stmt = $pdo->prepare('INSERT INTO Group_member (group_id, member) VALUES (?, ?)');
                $stmt->execute([$group_id, $member]);
            }

            // 成功したらグループ一覧ページにリダイレクト
            header('Location: group_list.php');
            exit();
        }

        
    }
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

        <!-- エラーメッセージの表示 -->
        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <form action="group_new.php" method="post" enctype="multipart/form-data">
        <div class="aikon">
            <lable label="file_label">
                <img id="aikonPreview" src="img/aikon/<?php echo htmlspecialchars($aikon, ENT_QUOTES, 'UTF-8'); ?>" class="maru">
                <input type="file" name="aikon" id="aikonInput">
                <input type="hidden" name="aikonn" id="aikonnHidden" value="<?php echo htmlspecialchars($aikon, ENT_QUOTES, 'UTF-8'); ?>">
            </lable>
        </div>
        <br>
        <!-- グループ名の入力 -->
        <input type="text" name="group_name" placeholder="チャット名" maxlength="15" value="<?php echo isset($_POST['group_name']) ? htmlspecialchars($_POST['group_name'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
        <br>

        <!-- 趣味タグ選択 -->
        <?php
        // 選択された趣味タグの表示
        if (!empty($selectedTags)) {
            foreach ($selectedTags as $tagId) {
                $sel = $pdo->prepare('SELECT * FROM Tag WHERE tag_id = ?');
                $sel->execute([$tagId]);
                $tag = $sel->fetch(PDO::FETCH_ASSOC);
                if ($tag) {
                    echo '<div class="s-tag" style="background: rgb(', $tag['tag_color1'], ',', $tag['tag_color2'], ',', $tag['tag_color3'], ');">', htmlspecialchars($tag['tag_mei']), '</div>';
                    echo '<input type="hidden" name="selectedOptions[]" value="', $tag['tag_id'], '">';
                }
            }
        }        
        ?>
        <br>
        <button class="btn-tag" type="button" onclick="location.href='tag_sentaku2.php'">＃趣味タグ選択</button>
        <br>

        <!-- 選択されたメンバーの表示 -->
        <?php
        $count=0;
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
                    $count++;
                }
            }
            echo '</table>';
        }
        ?>

        <button class="btn-tag" type="button" onclick="location.href='member-sentaku.php'">メンバー招待</button>
        <br>
        <button class="nextbutton" type="submit">作成</button>
    </form>
    </div>
    <br>
    <?php
        for($i = 0; $i < $count; $i++){
            echo '<br>';
        }
    ?>
    <?php require 'menu.php'; ?>

    <script>
        document.getElementById('aikonInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('aikonPreview').src = e.target.result;
                    document.getElementById('aikonnHidden').value = file.name;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>