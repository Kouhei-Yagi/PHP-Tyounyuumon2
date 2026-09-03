<?php
// ＜前処理＞
// 関数ファイルの読み込み
require_once(__DIR__ . '/../app/db.php');
require_once(__DIR__ . '/../app/validation.php');

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
    // データベース接続
    $pdo = getDbConnection();

    // 商品詳細取得
    // クエリ準備
    $sqlSelectProduct = 'SELECT * FROM product WHERE id = :id';
    $stmtSelectProduct = $pdo->prepare($sqlSelectProduct);

    // パラメータ設定
    $stmtSelectProduct->bindValue(':id', $idInt, PDO::PARAM_INT);

    // クエリ実行
    $stmtSelectProduct->execute();

    // 結果取得
    $product = $stmtSelectProduct->fetch(PDO::FETCH_ASSOC);

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
</body>

</html>
