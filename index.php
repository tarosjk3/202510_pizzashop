<?php

    // データベース接続ファイル
    require 'dbconnect.php';

    // データの取得
    $sql = 'SELECT id, chef_name, pizza_name FROM pizzas';
    $result = $db->query($sql);

    if($result) {
        // $result->fetch(); //１件取得
        $pizzas = $result->fetchAll(); // 全件取得

        echo '<pre>';
        var_dump($pizzas);
        echo '</pre>';
    }

    // fetchを使って１件ずつ取り出す方法
    // while($result->fetch()) {...}


    $title = 'Pizzeria Interplan';
?>
<?php include 'template/header.php'; ?>



<?php include 'template/footer.php'; ?>