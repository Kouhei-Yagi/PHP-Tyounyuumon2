<?php
// 関数ファイルの読み込み
require_once(__DIR__ . '/../app/db.php');

// セッション開始
session_start();

// ＜入力＞
// POST送信チェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('不正なアクセスです。');
}

// 送信・入力値取得
$csrfToken = filter_input(INPUT_POST, 'csrf_token');
$name = filter_input(INPUT_POST, 'name');
$address = filter_input(INPUT_POST, 'address');
$login = filter_input(INPUT_POST, 'login');
$password = filter_input(INPUT_POST, 'password');

// CSRFトークン存在チェック・検証
if (!isset($csrfToken) || $csrfToken !== $_SESSION['csrf_token']) {
    exit('不正なアクセスです。');
}

// CSRFトークン破棄
unset($_SESSION['csrf_token']);

// trim 前の入力フィールド一覧
$rawFields = [
    'name' => $name,
    'address' => $address,
    'login' => $login,
    'password' => $password,
];

// 入力値存在チェック
foreach ($rawFields as $key => $value) {
    if ($value === null) {
        exit('不正なアクセスです。');
    }
}

// trim 後の入力フィールド一覧を作成
$fields = [];
foreach ($rawFields as $key => $value) {
    $fields[$key] = trim($value);
}

// 文字数ルール
$rules = [
    'name' => 100,
    'address' => 200,
    'login' => 100,
    'password' => 255,
];

// 入力値空欄チェック
foreach ($fields as $key => $value) {
    if ($value === '') {
        exit($key . 'を入力してください。');
    }
}

// 入力値文字数チェック
foreach ($rules as $key => $max) {
    if (mb_strlen($fields[$key]) > $max) {
        exit($key . 'は' . $max . '以内で入力してください。');
    }
}

// ＜処理＞
// データベース接続設定
$dsn = 'mysql:host=localhost;dbname=shop;charset=utf8mb4';
$username = 'staff';
$dbPassword = 'password';

// 例外処理
try {
    // データベース接続
    $pdo = getDbConnection($dsn, $username, $dbPassword);

    // ログイン名重複チェック
    // クエリ準備
    $sqlSelectLogin = 'SELECT COUNT(*) FROM customer WHERE login = :login';
    $stmtSelectLogin = $pdo->prepare($sqlSelectLogin);

    // パラメータ設定
    $stmtSelectLogin->bindValue(':login', $fields['login'], PDO::PARAM_STR);

    // クエリ実行
    $stmtSelectLogin->execute();

    // 結果を取得
    $count = $stmtSelectLogin->fetchColumn();

    // 重複チェック
    if ($count > 0) {
        exit('ログイン名が重複しています。');
    }

    // 登録処理
    // パスワードのハッシュ化
    $hashedPassword = password_hash($fields['password'], PASSWORD_DEFAULT);

    // クエリ準備
    $sqlInsertCustomer = 'INSERT INTO customer(name, address, login, password) VALUES(:name, :address, :login, :password)';
    $stmtInsertCustomer = $pdo->prepare($sqlInsertCustomer);

    // パラメータ設定
    $stmtInsertCustomer->bindValue(':name', $fields['name'], PDO::PARAM_STR);
    $stmtInsertCustomer->bindValue(':address', $fields['address'], PDO::PARAM_STR);
    $stmtInsertCustomer->bindValue(':login', $fields['login'], PDO::PARAM_STR);
    $stmtInsertCustomer->bindValue(':password', $hashedPassword, PDO::PARAM_STR);

    // クエリ実行
    $stmtInsertCustomer->execute();

    // 例外発生時処理
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('システムエラーが発生しました。');
}

// ＜出力＞
echo '登録しました。';
