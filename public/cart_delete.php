<?php
// ＜簡易設計書＞
// 機能：カート商品削除処理
// 目的：カート一覧から商品を削除する
// 要件：ログインユーザーのカート一覧から商品が削除される
// 画面：カート一覧画面（削除ボタン） → カート削除処理
// データ：顧客ID、商品ID
// テーブル：cartテーブル

// ＜前処理＞
// セッション開始
session_start();

// ＜入力＞
// 送信値取得
$customerId = $_SESSION['auth']['id'];
$productId = $_POST['product_id'];

// ＜処理＞
// カート商品削除
// データベース接続設定
$dsn = 'mysql:host=localhost;dbname=shop;charset=utf8mb4';
$user = 'staff';
$dbPassword = 'password';

// データベース接続
$pdo = new PDO($dsn, $user, $dbPassword);

// クエリ準備
$sqlDeleteCart = '
    DELETE FROM cart
    WHERE customer_id = :customer_id
        AND product_id = :product_id
';

$stmtDeleteCart = $pdo->prepare($sqlDeleteCart);

// パラメータ設定
$stmtDeleteCart->bindValue(':customer_id', $customerId, PDO::PARAM_INT);
$stmtDeleteCart->bindValue(':product_id', $productId, PDO::PARAM_INT);

// クエリ実行
$stmtDeleteCart->execute();

// ＜出力＞
echo 'カートから商品を削除しました。';
