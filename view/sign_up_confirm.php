<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/sign_up.css">
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=RocknRoll+One&family=WDXL+Lubrifont+JP+N&display=swap" rel="stylesheet">
    <title>よくある間違いOXクイズ　登録確認</title>
</head>
<body>
    <a href="../ctrl/index.php">
        <img src="../images/other/logo.png" alt="よくある間違い〇✕クイズ">
    </a>
    <div style="font-size: 22px; font-weight: bold;">入力内容の確認</div>
    <form action="../ctrl/index.php" method="post">
        <div class="form-group">
            <span>メールアドレス</span>
            <input type="email" name="email" value="<?= $_SESSION['email'] ?>" readonly>
            <input type="hidden" name="sign_up" value="true">
        </div>
        <div class="form-group">
            <span>パスワード</span>
            <input type="password" id="password" name="login_pass" value="<?= $_SESSION['login_pass']; ?>" readonly>
        </div>
        <div class="btn-area">
            <a href="../ctrl/sign_up.php">戻る</a>
            <button type="submit">登録する</button>
        </div>
    </form>
</body>
</html>