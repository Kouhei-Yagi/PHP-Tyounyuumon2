<?php
// ＜処理＞
// 商品一覧取得
// データベース接続設定
$dsn = 'mysql:host=localhost;dbname=shop;charset=utf8mb4';
$user = 'staff';
$password = 'password';

// データベース接続
$pdo = new PDO($dsn, $user, $password);

// クエリ実行
$sqlSelectProducts = 'SELECT * FROM product';
$stmtSelectProducts = $pdo->query($sqlSelectProducts);

// 結果取得
$results = $stmtSelectProducts->fetchAll(PDO::FETCH_ASSOC);
?>

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
            <?php foreach ($results as $item) { ?>
                <tr>
                    <td>
                        <?= $item['id']; ?>
                    </td>
                    <td>
                        <?= $item['name']; ?>
                    </td>
                    <td>
                        <?= $item['price']; ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>
