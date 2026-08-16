<?php
// ＜入力＞
// 入力・送信値取得
$login = $_POST['login'];
$password = $_POST['password'];

// ＜処理＞
// データベース接続設定
$dsn = 'mysql:host=localhost;dbname=shop;charset=utf8mb4';
$username = 'staff';
$dbPassword = 'password';

// データベース接続
$pdo = new PDO($dsn, $username, $dbPassword);

// 検証処理
// クエリ準備
$sql = 'SELECT * FROM customer WHERE login = :login AND password = :password';
$stmt = $pdo->prepare($sql);

// パラメータ設定
$stmt->bindValue(':login', $login, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);

// クエリ実行
$stmt->execute();

// 結果取得
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// ログイン検証
if (!$result) {
    exit('ログインが失敗しました。');
}

// ＜出力＞
echo 'ログインに成功しました。';
