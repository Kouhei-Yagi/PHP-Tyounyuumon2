<?php
// セッション開始
session_start();

// CSRFトークン生成
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
</head>

<body>
    <form action="login_confirm.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <p>
            <label for="login">ログイン名：</label>
            <input type="text" id="login" name="login">
        </p>

        <p>
            <label for="password">パスワード：</label>
            <input type="password" id="password" name="password">
        </p>

        <button type="submit">ログイン</button>
    </form>
</body>

</html>
