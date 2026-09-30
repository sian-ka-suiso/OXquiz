<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　管理者ログイン</title>
</head>
<body>
    <div class="login-page">
        <a class="login-logo" href="../ctrl_admin/adm_login.php">
            <img src="../images/other/logo.png" alt="よくある間違い〇✕クイズ">
        </a>
        <div class="login-card">
            <h2 class="login-heading">管理者ログイン</h2>
            <form class="login-form" action="../ctrl_admin/adm_login.php" method="post">
                <div class="form-group">
                    <span>メールアドレス</span>
                    <input type="email" name="email" value="">
                </div>
                <div class="form-group">
                    <span>パスワード</span>
                    <input type="password" name="pw" value="">
                </div>
                <span class="err"><?= isset($arrErr['common']) ? $arrErr['common'] : "" ?></span>
                <button type="submit" class="btn btn-primary" title="入力した内容でログインします">ログイン</button>
            </form>
        </div>
    </div>
</body>
</html>
