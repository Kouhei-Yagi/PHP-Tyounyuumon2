<?php
// ＜簡易設計書＞
// 機能：カート一覧
// 目的：カートに入れている商品一覧を表示する
// 要件：ログインユーザーがカートに入れている商品を表示
// 画面：一覧画面
// データ：商品ID、商品名、価格、個数
// テーブル：cartテーブル、productテーブル

// ＜前処理＞
// 関数ファイルの読み込み
require_once(__DIR__ . '/../app/db.php');

// セッション開始
session_start();

// ログインチェック
if (!isset($_SESSION['auth'])) {
    exit('ログインしてください。');
}

// ＜入力＞
// 送信値取得
$customerId = $_SESSION['auth']['id'];

// ＜処理＞
// 例外処理
try {
    // データベース接続
    $pdo = getDbConnection();

    // カート一覧取得
    // クエリ準備
    $sqlSelectCart = '
    SELECT
        product.id,
        product.name,
        product.price,
        cart.count
    FROM cart
    INNER JOIN product
        ON cart.product_id = product.id
    WHERE customer_id = :customer_id
';
    $stmtSelectCart = $pdo->prepare($sqlSelectCart);

    // パラメータ設定
    $stmtSelectCart->bindValue('customer_id', $customerId, PDO::PARAM_INT);

    // クエリ実行
    $stmtSelectCart->execute();

    // 結果取得
    $cartItems = $stmtSelectCart->fetchAll(PDO::FETCH_ASSOC);

    // 例外発生時
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
    <title>カート一覧</title>
    <style>
        th,
        td {
            border: 1px solid black;
        }

        table {
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <h1>カート一覧</h1>

    <?php if (!$cartItems): ?>
        <p>カートに商品がありません。</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>商品ID</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>個数</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($cartItems as $cartItem): ?>
                    <tr>
                        <td>
                            <?= $cartItem['id'] ?>
                        </td>
                        <td>
                            <a href="product_detail.php?id=<?= $cartItem['id'] ?>">
                                <?= $cartItem['name'] ?>
                            </a>
                        </td>
                        <td>
                            <?= $cartItem['price'] ?>
                        </td>
                        <td>
                            <?= $cartItem['count'] ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>

</html>
