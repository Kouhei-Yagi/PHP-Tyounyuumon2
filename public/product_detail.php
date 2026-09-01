<?php
// ＜入力＞
// クエリパラメータ取得
$id = filter_input(INPUT_GET, 'id');

// id 存在チェック
if ($id === null) {
    exit('不正なアクセスです。');
}

// id 空欄チェック
if ($id === '') {
    exit('商品が正しく選択されていません。');
}

// ＜処理＞
// データベース接続設定
$dsn = 'mysql:host=localhost;dbname=shop;charset=utf8mb4';
$user = 'staff';
$password = 'password';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// 例外処理
try {
    // データベース接続
    $pdo = new PDO($dsn, $user, $password, $options);

    // クエリ準備
    $sqlSelectProduct = 'SELECT * FROM product WHERE id = :id';
    $stmtSelectProduct = $pdo->prepare($sqlSelectProduct);

    // パラメータ設定
    $stmtSelectProduct->bindValue(':id', $id, PDO::PARAM_INT);

    // クエリ実行
    $stmtSelectProduct->execute();

    // 結果取得
    $product = $stmtSelectProduct->fetch(PDO::FETCH_ASSOC);

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
