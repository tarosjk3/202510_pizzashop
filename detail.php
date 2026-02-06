<?php

require 'dbconnect.php';

// 削除用
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete-id'])) {
    die('削除のプロセス');
}

// 表示用
if (isset($_GET['id'])) {

    $stmt = $db->prepare('SELECT * FROM pizzas WHERE id = ?');
    $stmt->bindValue(1, $_GET['id']);
    $result = $stmt->execute();

    // SQLが正常に実行され、データが取得できたら
    if ($result) {
        $pizza = $stmt->fetch();
    } else {
        header('location:index.php');
        exit;
    }
} else {
    header('location:index.php');
    exit;
}


$title = '';
?>
<?php include 'template/header.php'; ?>

<div class="container">
    <h1 class="h2 text-center my-5">Pizza Detail</h1>

    <?php if ($pizza): ?>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <?php
                    $pizza_img = '';
                    if (is_null($pizza['image'])) {
                        $pizza_img = 'default.png';
                    } else {
                        $pizza_img = $pizza['image'];
                    }
                    ?>
                    <img src="uploads/<?= htmlspecialchars($pizza_img); ?>" class="card-img-top pizza-img" alt="">
                    <div class="card-body">
                        <h2 class="card-title h4"><?= htmlspecialchars($pizza['pizza_name']); ?></h2>
                        <p class="card-text"><?= htmlspecialchars($pizza['toppings']); ?></p>
                        <p class="card-text">シェフ: <?= htmlspecialchars($pizza['chef_name']); ?></p>
                        <p class="card-text">登録日: <?= htmlspecialchars($pizza['created_at']); ?></p>
                    </div>
                    <div class="card-footer text-end">
                        <form action="detail.php" method="post" id="delete-form">
                            <input type="hidden" name="delete-id" value="<?= htmlspecialchars($pizza['id']); ?>">
                            <button class="btn btn-danger" type="submit">削除</button>
                        </form>
                        <script>
                            const deleteForm = document.querySelector('#delete-form');
                            deleteForm.addEventListener('submit', e => {
                                // フォームの送信をストップする(デフォルトの挙動をストップする)
                                e.preventDefault();

                                // ユーザーが削除を許可した場合のみ、フォームを送信する
                                const confirmed = confirm('このピザのデータを本当に削除しますか?');
                                if(confirmed) {
                                    e.target.submit();
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
            </svg>
            <div>
                ピザのデータがありません。
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'template/footer.php'; ?>