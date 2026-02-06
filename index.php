<?php

session_start();

$_SESSION['name'] = '山田太郎';

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
    <?php
    // 削除用フラッシュメッセージ
    if (isset($_SESSION['delete-msg'])):
    ?>
        <div class="alert alert-success d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
            </svg>
            <div>
                データが削除されました。
            </div>
        </div>
        <?php unset($_SESSION['delete-msg']); ?>
    <?php endif; ?>

    <h1 class="h2 text-center my-5">Our Special Pizzas</h1>

    <div class="row">
        <?php foreach ($pizzas as $pizza): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <?php
                    $pizza_img = '';
                    if (is_null($pizza['image'])) {
                        $pizza_img = 'default.png';
                    } else {
                        $pizza_img = $pizza['image'];
                    }
                    ?>
                    <a href="detail.php?id=<?= htmlspecialchars($pizza['id']); ?>"><img src="uploads/<?= htmlspecialchars($pizza_img); ?>" class="card-img-top pizza-img" alt=""></a>
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