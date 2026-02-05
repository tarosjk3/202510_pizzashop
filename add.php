<?php
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
    var_dump($_FILES);
    if($_FILES['pizza-img']['tmp_name'] !== '' && $_FILES['pizza-img']['error'] !== UPLOAD_ERR_NO_FILE) {
        echo 'ファイルのアップロードがありました';
        // 2-2. ファイルのフォーマット(形式)チェック(JPG, PNG, GIFのいずれか)
        $imginfo = new finfo(FILEINFO_MIME_TYPE);
        $mimetype = $imginfo->file($_FILES['pizza-img']['tmp_name']);
        var_dump($mimetype);
        $ok_mimetype = ['image/jpg', 'image/png', 'image/gif'];
        if(!in_array($mimetype, $ok_mimetype)) {
            $errors['pizza-img'] = '許可されていないファイル形式です(JPG, PNG, GIFのみ)';
        }

        // 2-3. ファイルのサイズが0ではないチェック
        if($_FILES['pizza-img']['size'] === 0) {
            $errors['pizza-img'] = 'ファイルのサイズが0です';
        }

        // 2-4. ファイルの名前を作成する
        $upload_file_name = $_FILES['pizza-img']['name'];
        var_dump(pathinfo($upload_file_name));
        $extension = pathinfo($upload_file_name)['extension'];
        // ランダムな文字列 + 拡張子 (例 asdf3q32sad.jpg)
        $new_img_name = uniqid() . '.' . $extension;
        var_dump($new_img_name);

        // 2-5. ファイルを移動して保存する
        $upload_success = move_uploaded_file($_FILES['pizza-img']['tmp_name'], 'uploads/' . $new_img_name);
        if(!$upload_success) {
            $errors['pizza-img'] = 'エラーがあったためアップロードできませんでした';
        }
    }

    // 3. エラーメッセージの有無をチェックし、エラーがなければTOPページへリダイレクト
    if( !array_filter($errors) ) {
        header('Location:index.php');
        exit; //プログラム処理終了
    }
}


?>
<?php
$title = 'ピザの登録';
?>
<?php include 'template/header.php'; ?>

<div class="container">
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