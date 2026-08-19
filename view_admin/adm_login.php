<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <title>よくある間違いOXクイズ　管理者ログイン</title>
</head>
<body>
    <a class="title" href="login">
        <img src="../images/other/logo.png" alt="よくある間違い〇✕クイズ">
    </a>
    <div style="font-size: 24px; font-weight: bold;">管理者ログイン</div>
    <form action="login" method="post">
        <div class="form-group">
            <span>メールアドレス</span>
            <input type="email" name="email" value="">
        </div>
        <div class="form-group">
            <span>パスワード</span>
            <input type="password" name="pw" value="">
        </div>
        <div class="form-group">
            <span class="err" style="margin-bottom: 5px;"><?= isset($arrErr['common']) ? $arrErr['common'] : "" ?></span>
            <button type="submit">ログイン</button>
        </div>
    </form>
</body>
<style>
    body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }
    .title img {
        padding: 0 0 15px 0;
        width: 600px;
        object-fit: fill;
        object-position: center;
    }
    form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 10px;
        width: 300px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
    }
    .form-group input {
        padding: 5px;
    }
    .form-group button {
        height: 25px;
        color: white;
        background-color: #303030;
        border: 0.5px solid #303030;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .form-group button:hover {
        color: #303030;
        background-color: white;
    }
</style>
</html>