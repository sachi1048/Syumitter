<?php require 'db-connect.php'; ?>
<?php
class MyController {

    public function index() {

        $pdo = new PDO("mysql:host=localhost;dbname=symitter;charset=utf8", USER, PASS);
        // GETパラメータの取得とエスケープ
        $hobby = isset($_GET['hobby']) ? $_GET['hobby'] : '';//タグ名
        $nav = isset($_GET['nav']) ? $_GET['nav'] : '';// ユーザーor 登校 or グルチャ
        // SQLクエリ
        $sql = "SELECT a.user_name, a.display_name, a.aikon, a.profile, a.mail, a.pass, a.freeze_code, t.tag_id, t.tag_mei, t.tag_color1, t.tag_color2, t.tag_color3 FROM Account a JOIN User_tag ut ON a.user_name = ut.user_name JOIN Tag t ON ut.tag_id = t.tag_id WHERE t.tag_mei LIKE :hobby ";
        // プリペアドステートメントの準備
        $stmt = $pdo->prepare($sql);
        // パラメータのバインド
        $stmt->bindValue(':hobby', '%' . $hobby . '%', PDO::PARAM_STR);
        // クエリの実行
        $stmt->execute();
        // 結果の取得
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // 結果の表示
        foreach ($results as $row) {
            echo 'User Name: ' . $row['user_name'] . '<br>';
            echo 'Display Name: ' . $row['display_name'] . '<br>';
            echo 'Aikon: ' . $row['aikon'] . '<br>';
            echo 'Profile: ' . $row['profile'] . '<br>';
            echo 'Mail: ' . $row['mail'] . '<br>';
            echo 'Pass: ' . $row['pass'] . '<br>';
            echo 'Freeze Code: ' . $row['freeze_code'] . '<br>';
            echo 'Tag ID: ' . $row['tag_id'] . '<br>';
            echo 'Tag Mei: ' . $row['tag_mei'] . '<br>';
            echo 'Tag Color1: ' . $row['tag_color1'] . '<br>';
            echo 'Tag Color2: ' . $row['tag_color2'] . '<br>';
            echo 'Tag Color3: ' . $row['tag_color3'] . '<br>';
            echo '<hr>';
        }
    }

}

// 実際のリクエストを処理する部分
$controller = new MyController();

if (isset($_GET["method"])) {
    $method = $_GET["method"];
    if (method_exists($controller, $method)) {
        $controller->$method();
    } else {
        echo "Method $method not found.";
    }
} else {
    echo "Method parameter is missing.";
}

?>