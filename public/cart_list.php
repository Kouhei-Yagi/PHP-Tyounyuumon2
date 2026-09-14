<?php
// ＜簡易設計書＞
// 機能：カート一覧
// 目的：カートに入れている商品一覧を表示する
// 要件：ログインユーザーがカートに入れている商品を表示
// 画面：一覧画面
// データ：商品ID、商品名、価格、個数
// テーブル：cartテーブル、productテーブル

// ＜前処理＞
// セッション開始
session_start();

// ＜入力＞
// 送信値取得
$customerId = $_SESSION['auth']['id'];

// ＜処理＞
// カート一覧取得
// データベース接続設定
$dsn = 'mysql:host=localhost;dbname=shop;charset=utf8mb4';
$user = 'staff';
$dbPassword = 'password';

// データベース接続
$pdo = new PDO($dsn, $user, $dbPassword);

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
            <?php foreach ($cartItems as $cartItem) { ?>
                <tr>
                    <td><?= $cartItem['id'] ?></td>
                    <td><?= $cartItem['name'] ?></td>
                    <td><?= $cartItem['price'] ?></td>
                    <td><?= $cartItem['count'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>
