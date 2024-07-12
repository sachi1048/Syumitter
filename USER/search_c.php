<?php require 'db-connect.php'; ?>
<?php
class MyController {

    public function index() {

        $pdo = new PDO("mysql:host=localhost;dbname=symitter;charset=utf8", USER, PASS);
        // GETパラメータの取得とエスケープ
        $hobby = isset($_GET['hobby']) ? $_GET['hobby'] : '';//タグ名
        $nav = isset($_GET['nav']) ? $_GET['nav'] : '';// ユーザーor 登校 or グルチャ
        // SQLクエリ
        // ユーザーを選択
        if($nav=="user") {
            $sql = "SELECT DISTINCT 
            a.user_name, a.display_name, a.aikon, a.profile, a.mail, a.pass, a.freeze_code, 
            t.tag_id, t.tag_mei, t.tag_color1, t.tag_color2, t.tag_color3 
            FROM 
                Account a 
            JOIN 
                User_tag ut ON a.user_name = ut.user_name 
            JOIN 
                Tag t ON ut.tag_id = t.tag_id 
            WHERE 
                t.tag_mei LIKE :hobby";        // プリペアドステートメントの準備
            $stmt = $pdo->prepare($sql);
            // パラメータのバインド
            $stmt->bindValue(':hobby', '%' . $hobby . '%', PDO::PARAM_STR);
            // クエリの実行
            $stmt->execute();
            // 結果の取得
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            include('searchList_v.php');

        }else if($nav=="post") {
            $sql = "SELECT
                    t.toukou_id
                    , t.title
                    , t.toukou_datetime
                    , t.contents
                    , t.setumei
                    , t.tag_id1
                    , tag1.tag_mei AS tag_mei1
                    , t.tag_id2
                    , tag2.tag_mei AS tag_mei2
                    , t.tag_id3
                    , tag3.tag_mei AS tag_mei3
                    , t.toukou_mei 
                    , tag1.tag_color1 AS tag1_color1
                    , tag1.tag_color2 AS tag1_color2
                    , tag1.tag_color3 AS tag1_color3
                    , tag2.tag_color1 AS tag2_color1
                    , tag2.tag_color2 AS tag2_color2
                    , tag2.tag_color3 AS tag2_color3
                    , tag3.tag_color1 AS tag3_color1
                    , tag3.tag_color2 AS tag3_color2
                    , tag3.tag_color3 AS tag3_color3
                FROM
                    toukou t 
                    LEFT JOIN tag tag1 
                        ON t.tag_id1 = tag1.tag_id 
                    LEFT JOIN tag tag2 
                        ON t.tag_id2 = tag2.tag_id 
                    LEFT JOIN tag tag3 
                        ON t.tag_id3 = tag3.tag_id 
                WHERE
                    tag1.tag_mei LIKE :hobby1
                    OR tag2.tag_mei LIKE :hobby2 
                    OR tag3.tag_mei LIKE :hobby3
                    ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':hobby1', '%' . $hobby . '%', PDO::PARAM_STR);
            $stmt->bindValue(':hobby2', '%' . $hobby . '%', PDO::PARAM_STR);  
            $stmt->bindValue(':hobby3', '%' . $hobby . '%', PDO::PARAM_STR);
            $stmt->execute();
            // 結果の取得
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            include('searchToukouList_v.php'); 
        }else if($nav=="group_chat") {
            $sql = "SELECT *
            FROM 
                tag t 
            WHERE 
                t.tag_mei LIKE :tag_mei";        // プリペアドステートメントの準備
            $stmt = $pdo->prepare($sql);
            // パラメータのバインド
            $stmt->bindValue(':tag_mei', $hobby , PDO::PARAM_STR);
            // クエリの実行     
            $stmt->execute();
            // 結果の取得
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
            header('Location: group_list.php?tagId='.$results["tag_id"]);
            // include('group_list.php?tag_id='.$results["tag_id"]); 
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