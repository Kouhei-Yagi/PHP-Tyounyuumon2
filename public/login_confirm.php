<?php
// ＜前処理＞
// 関数ファイルの読み込み
require_once(__DIR__ . '/../app/db.php');
require_once(__DIR__ . '/../app/validation.php');
require_once(__DIR__ . '/../app/security.php');
require_once(__DIR__ . '/../app/auth.php');

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

// CSRFトークン存在チェック・検証・破棄
$isCsrfToken = validateCsrfToken($csrfToken);
if (!$isCsrfToken) {
    exit('不正なアクセスです。');
}

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
$isExists = $validated['isExists'];
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
// 例外処理
try {
    // ログイン認証処理
    $customer = authenticateUser($fields);
    if (!$customer) {
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
