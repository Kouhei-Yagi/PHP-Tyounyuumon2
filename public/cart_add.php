<?php
// ＜前処理＞
// セッション開始
session_start();

// ＜入力＞
// 送信・入力値取得
$customerId = $_SESSION['auth']['id'];
$productId = $_POST['id'];
$count = $_POST['count'];

// ＜処理＞
// データベース接続設定
$dsn = 'mysql:host=localhost;dbname=shop;charset=utf8mb4';
$user = 'staff';
$dbPassword = 'password';

// データベース接続
$pdo = new PDO($dsn, $user, $dbPassword);

// カート登録
// クエリ準備
$sqlInsertCart = 'INSERT INTO cart(customer_id, product_id, count) VALUES (:customer_id, :product_id, :count)';
$stmtInsertCart = $pdo->prepare($sqlInsertCart);

// クエリパラメータ設定
$stmtInsertCart->bindValue('customer_id', $customerId, PDO::PARAM_INT);
$stmtInsertCart->bindValue('product_id', $productId, PDO::PARAM_INT);
$stmtInsertCart->bindValue('count', $count, PDO::PARAM_INT);

// クエリ実行
$stmtInsertCart->execute();

// ＜出力＞
echo 'カートに登録しました。';
