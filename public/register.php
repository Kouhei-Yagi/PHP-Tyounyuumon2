<?php
// 関数ファイルの読み込み
require_once(__DIR__ . '/../app/security.php');

// セッション開始
session_start();

// CSRFトークン生成
$_SESSION['csrf_token'] = generateCsrfToken();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>会員登録</title>
</head>

<body>
    <form action="register_confirm.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <p>
            <label for="name">お名前：</label>
            <input type="text" id="name" name="name">
        </p>

        <p>
            <label for="address">ご住所：</label>
            <input type="text" id="address" name="address">
        </p>

        <p>
            <label for="login">ログイン名：</label>
            <input type="text" id="login" name="login">
        </p>

        <p>
            <label for="password">パスワード：</label>
            <input type="password" id="password" name="password">
        </p>

        <button type="submit">登録</button>
    </form>
</body>

</html>
