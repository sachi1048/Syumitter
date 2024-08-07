<?php
class MyController {
   
     public function index() {
        require 'db-connect.php';
        $pdo = new PDO($connect, USER, PASS);
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
                    Toukou t 
                    LEFT JOIN Tag tag1 
                        ON t.tag_id1 = tag1.tag_id 
                    LEFT JOIN Tag tag2 
                        ON t.tag_id2 = tag2.tag_id 
                    LEFT JOIN Tag tag3 
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
            $sql='SELECT
                gc.group_id
                , gc.group_mei
                , gc.aikon AS group_aikon
                , gm.member
                , a.display_name
                , t.tag_mei
                , t.tag_color1
                , t.tag_color2
                , t.tag_color3 
            FROM
                Group_chat gc 
                LEFT JOIN Group_member gm 
                    ON gc.group_id = gm.group_id 
                LEFT JOIN Account a 
                    ON gm.member = a.user_name 
                LEFT JOIN Tag t 
                    ON gc.tag_id = t.tag_id
                WHERE
                t.tag_mei LIKE :hobby
            ';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':hobby', '%' . $hobby . '%', PDO::PARAM_STR);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // データの整形
            $groupedResults = [];
            foreach ($results as $row) {
                $groupedResults[$row['group_id']]['group_mei'] = $row['group_mei'];
                $groupedResults[$row['group_id']]['group_aikon'] = $row['group_aikon'];
                $groupedResults[$row['group_id']]['tag_mei'] = $row['tag_mei'];
                $groupedResults[$row['group_id']]['tag_color1'] = $row['tag_color1'];
                $groupedResults[$row['group_id']]['tag_color2'] = $row['tag_color2'];
                $groupedResults[$row['group_id']]['tag_color3'] = $row['tag_color3'];
                $groupedResults[$row['group_id']]['members'][] = [
                    'member' => $row['member'],
                    'display_name' => $row['display_name']
                ];
            }
            include('searchToukouGroupChatList_v.php');
       
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