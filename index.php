<?php

// データベース接続ファイル
require 'dbconnect.php';

// データの取得
$sql = 'SELECT id, pizza_name, toppings, image FROM pizzas ORDER BY created_at DESC';
$result = $db->query($sql);

if ($result) {
    // $result->fetch(); //１件取得
    $pizzas = $result->fetchAll(); // 全件取得
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
                    <?php
                        $pizza_img = '';
                        if(is_null($pizza['image'])) {
                            $pizza_img = 'default.png';
                        } else {
                            $pizza_img = $pizza['image'];
                        }
                    ?>
                    <img src="uploads/<?= htmlspecialchars($pizza_img); ?>" class="card-img-top pizza-img" alt="">
                    <div class="card-body">
                        <h2 class="card-title h4"><?= htmlspecialchars($pizza['pizza_name']); ?></h2>
                        <p class="card-text"><?= htmlspecialchars($pizza['toppings']); ?></p>
                        <a href="detail.php?id=<?= htmlspecialchars($pizza['id']); ?>" class="btn btn-primary">詳細</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'template/footer.php'; ?>