<?php
// 関数ファイルの読み込み
require_once(__DIR__ . '/../app/db.php');
require_once(__DIR__ . '/../app/validation.php');

// セッション開始
session_start();

// ＜入力＞
// POST送信チェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('不正なアクセスです。');
}

// 入力・送信値取得
$csrfToken = filter_input(INPUT_POST, 'csrf_token');
$login = filter_input(INPUT_POST, 'login');
$password = filter_input(INPUT_POST, 'password');

// CSRFトークン存在チェック・検証
if ($csrfToken === null || $csrfToken !== $_SESSION['csrf_token']) {
    exit('不正なアクセスです。');
}

// CSRFトークン破棄
unset($_SESSION['csrf_token']);

// trim 前入力フィールド一覧
$rawFields = [
    'login' => $login,
    'password' => $password,
];

// trim 後入力フィールド一覧
$fields = [];
foreach ($rawFields as $key => $value) {
    $fields[$key] = trim($value);
}

// 入力値最大文字数
$maxLengths = [
    'login' => 100,
    'password' => 255,
];

// 入力値バリデーション処理
$validated = validateFields($rawFields, $fields, $maxLengths);

// 入力値存在チェック
$isExists = $validated['isExist'];
if (!$isExists) {
    exit('不正なアクセスです。');
}

// 入力値空欄チェック
$errorKey = $validated['errorKey'];
if ($errorKey) {
    exit($errorKey . 'を入力してください。');
}

// 入力値文字数チェック
$errorArray = $validated['errorArray'];
if ($errorArray) {
    exit($errorArray['key'] . 'は' . $errorArray['max'] . '文字以内で入力してください。');
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

    // ログイン検証
    // クエリ準備
    $sqlSelectCustomer = 'SELECT * FROM customer WHERE login = :login';
    $stmtSelectCustomer = $pdo->prepare($sqlSelectCustomer);

    // パラメータ設定
    $stmtSelectCustomer->bindValue(':login', $fields['login'], PDO::PARAM_STR);

    // クエリ実行
    $stmtSelectCustomer->execute();

    // 結果取得
    $customer = $stmtSelectCustomer->fetch(PDO::FETCH_ASSOC);

    // ログイン名存在チェック
    if (!$customer) {
        exit('ログインに失敗しました。');
    }

    // パスワードのハッシュ検証
    $isHash = password_verify($fields['password'], $customer['password']);
    if (!$isHash) {
        exit('ログインに失敗しました。');
    }

    // 例外発生時処理
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('システムエラーが発生しました。');
}

// ログインユーザー情報をセッションに保持
$_SESSION['auth'] = [
    'id' => $customer['id'],
    'name' => $customer['name'],
    'login' => $customer['login'],
];

// ＜出力＞
echo 'ログインに成功しました。';
