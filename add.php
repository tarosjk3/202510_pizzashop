<?php

require 'dbconnect.php';

$errors = [
    'chef-name' => '',
    'pizza-name' => '',
    'toppings' => '',
    'pizza-img' => '',
];

$chefname = '';
$pizzaname = '';
$toppings = '';

// データ受信後の処理
// var_dump($_SERVER['REQUEST_METHOD']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // var_dump($_POST);
    // echo '<pre>';
    // var_dump($_FILES);
    // echo '</pre>';

    // 1. 必須入力チェック & 書式チェック
    // シェフの名前
    if (empty($_POST['chef-name'])) {
        $errors['chef-name'] = 'シェフの名前が入力されていません';
    } else {
        $chefname = $_POST['chef-name'];

        if (!preg_match('/^([^\x01-\x7E]|[\da-zA-Z ])+$/', $_POST['chef-name'])) {
            $errors['chef-name'] = '日本語、英数字のみ有効です。記号等は使用できません';
        }
    }
    // ピザの名前
    if (empty($_POST['pizza-name'])) {
        $errors['pizza-name'] = 'ピザの名前が入力されていません';
    } else {
        $pizzaname = $_POST['pizza-name'];

        if (!preg_match('/^([^\x01-\x7E]|[\da-zA-Z ])+$/', $_POST['chef-name'])) {
            $errors['pizza-name'] = '日本語、英数字のみ有効です。記号等は使用できません';
        }
    }
    // トッピング
    if (empty($_POST['toppings'])) {
        $errors['toppings'] = 'トッピングが入力されていません';
    } else {
        $toppings = $_POST['toppings'];
    }

    // 2. ファイル（画像）のチェック
    // 2-1. ファイルのアップロードがあり、且つファイルのアップロードエラーをしていない
    // var_dump($_FILES);
    if ($_FILES['pizza-img']['tmp_name'] !== '' && $_FILES['pizza-img']['error'] !== UPLOAD_ERR_NO_FILE) {
        echo 'ファイルのアップロードがありました';
        // 2-2. ファイルのフォーマット(形式)チェック(JPG, PNG, GIFのいずれか)
        $imginfo = new finfo(FILEINFO_MIME_TYPE);
        $mimetype = $imginfo->file($_FILES['pizza-img']['tmp_name']);
        // var_dump($mimetype);
        $ok_mimetype = ['image/jpg', 'image/png', 'image/gif'];
        if (!in_array($mimetype, $ok_mimetype)) {
            $errors['pizza-img'] = '許可されていないファイル形式です(JPG, PNG, GIFのみ)';
        }

        // 2-3. ファイルのサイズが0ではないチェック
        if ($_FILES['pizza-img']['size'] === 0) {
            $errors['pizza-img'] = 'ファイルのサイズが0です';
        }

        // 2-4. ファイルの名前を作成する
        $upload_file_name = $_FILES['pizza-img']['name'];
        // var_dump(pathinfo($upload_file_name));
        $extension = pathinfo($upload_file_name)['extension'];
        // ランダムな文字列 + 拡張子 (例 asdf3q32sad.jpg)
        $new_img_name = uniqid() . '.' . $extension;
        // var_dump($new_img_name);

        // 2-5. ファイルを移動して保存する
        $upload_success = move_uploaded_file($_FILES['pizza-img']['tmp_name'], 'uploads/' . $new_img_name);
        if (!$upload_success) {
            $errors['pizza-img'] = 'エラーがあったためアップロードできませんでした';
        }
    }

    // 3. エラーメッセージの有無をチェックし、
    // エラーがなければデータベースへデータを登録し、TOPページへリダイレクト
    if (!array_filter($errors)) {
        // 画像のアップロードの有無を判別
        $is_image_uploaded = $_FILES['pizza-img']['tmp_name'] !== '';

        // プリペアドステートメントを作成（画像アップロードの有無で切り分ける）
        if ($is_image_uploaded) {
            $stmt = $db->prepare('INSERT INTO pizzas(chef_name, pizza_name, toppings, image) VALUES(?,?,?,?);');
        } else {
            $stmt = $db->prepare('INSERT INTO pizzas(chef_name, pizza_name, toppings) VALUES(?,?,?);');
        }
        // 登録するデータの挿入
        $stmt->bindValue(1, $_POST['chef-name']);
        $stmt->bindValue(2, $_POST['pizza-name']);
        $stmt->bindValue(3, $_POST['toppings']);
        if ($is_image_uploaded) {
            $stmt->bindValue(4, $new_img_name);
        }
        try {
            // 登録の実行
            $result = $stmt->execute();
    
            // 登録が成功した場合
            if ($result) {
                header('Location:index.php');
                exit; //プログラム処理終了
            }
        } catch (PDOException $e) {
            // 登録が失敗した場合
            $insert_failed = 'データベースへの登録が失敗しました。' . $e->getMessage();
        }

    }
}
?>
<?php
$title = 'ピザの登録';
?>
<?php include 'template/header.php'; ?>

<div class="container">
    <?php if (isset($insert_failed)): ?>
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
            </svg>
            <div>
                <?= htmlspecialchars($insert_failed); ?>
            </div>
        </div>
    <?php endif; ?>

    <h1 class="my-5 h4 text-center">ピザの登録</h1>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="add.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="chef-name" class="form-label">シェフの名前</label>
                    <input type="text" class="form-control" id="chef-name" name="chef-name" value="<?= $chefname; ?>">
                    <small class="text-danger"><?= $errors['chef-name']; ?></small>
                </div>
                <div class="mb-3">
                    <label for="pizza-name" class="form-label">ピザの名前</label>
                    <input type="text" class="form-control" id="pizza-name" name="pizza-name" value="<?= $pizzaname; ?>">
                    <small class="text-danger"><?= $errors['pizza-name']; ?></small>
                </div>
                <div class="mb-3">
                    <label for="toppings" class="form-label">トッピング</label>
                    <input type="text" class="form-control" id="toppings" name="toppings" value="<?= $toppings; ?>">
                    <small class="text-danger"><?= $errors['toppings']; ?></small>
                </div>
                <div class="mb-3">
                    <label for="pizza-img" class="form-label">ピザの画像</label>
                    <input class="form-control" type="file" id="pizza-img" name="pizza-img">
                    <small class="text-danger"><?= $errors['pizza-img']; ?></small>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" name="submit" value="1">登録する</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'template/footer.php'; ?>