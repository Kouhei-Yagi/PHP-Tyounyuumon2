<?php
// ＜前処理＞
// 関数ファイルの読み込み
require_once(__DIR__ . '/../app/validation.php');
require_once(__DIR__ . '/../app/product.php');

// セッション開始
session_start();

// CSRF トークン生成
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// ＜入力＞
// クエリパラメータ取得
$id = filter_input(INPUT_GET, 'id');

// id バリデーション処理
$validated = validateProductId($id);

// id 存在・空欄・数値チェック
if (!$validated) {
    exit('商品が正しく選択されていません。');
}

// id 型変換
$idInt = (int)$id;

// ＜処理＞
// 例外処理
try {
    // 商品1件取得
    $product = getProduct($idInt);

    // 商品存在チェック
    if (!$product) {
        exit('商品が正しく選択されていません。');
    }

    // 例外処理発生時
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('システムエラーが発生しました。');
}
?>

<!-- 出力 -->
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>商品詳細</title>
</head>

<body>
    <h1>商品詳細</h1>

    <p><img src="images/products/product_<?= $product['id'] ?>.jpg" alt="商品写真"></p>
    <p>商品番号：<?= $product['id'] ?></p>
    <p>商品名：<?= $product['name'] ?></p>
    <p>価格：<?= $product['price'] ?></p>

    <form action="cart_add.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">

        <p>
            <label for="count">個数：</label>
            <select name="count" id="count">
                <?php for ($i = 1; $i <= 10; $i++) { ?>
                    <option value="<?= $i ?>"><?= $i; ?></option>
                <?php } ?>
            </select>
        </p>

        <button type="submit">カート追加</button>
    </form>
</body>

</html>
