<?php

// データベース接続ファイル
require 'dbconnect.php';

// データの取得
$sql = 'SELECT id, pizza_name, toppings, image FROM pizzas';
$result = $db->query($sql);

if ($result) {
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

<div class="container">
    <h1 class="h2 text-center my-5">Our Special Pizzas</h1>

    <div class="row">
        <?php foreach ($pizzas as $pizza): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <h2 class="card-title h4">ピザの名前</h2>
                        <p class="card-text">トッピング</p>
                        <a href="#" class="btn btn-primary">詳細</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'template/footer.php'; ?>