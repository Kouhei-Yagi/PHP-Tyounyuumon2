<?php
// ＜前処理＞
// 関数ファイルの読み込み
require_once(__DIR__ . '/../app/product.php');

// ＜処理＞
// 例外処理
try {
    // 商品一覧取得
    $products = getProducts();

    // 例外発生時処理
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
    <title>商品一覧</title>
    <style>
        td {
            border: 1px solid black;
        }

        table {
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <h1>商品一覧</h1>

    <table>
        <thead>
            <tr>
                <td>商品番号</td>
                <td>商品名</td>
                <td>価格</td>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($products as $product) { ?>
                <tr>
                    <td>
                        <?= $product['id']; ?>
                    </td>
                    <td>
                        <a href="product_detail.php?id=<?= $product['id'] ?>">
                            <?= $product['name']; ?>
                        </a>
                    </td>
                    <td>
                        <?= $product['price']; ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>
