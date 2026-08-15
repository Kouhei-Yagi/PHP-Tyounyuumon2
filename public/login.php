<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
</head>

<body>
    <form action="login_confirm.php" method="post">
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
