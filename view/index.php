<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/index.css">
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=RocknRoll+One&family=WDXL+Lubrifont+JP+N&display=swap" rel="stylesheet">
    <title>よくある間違いOXクイズ</title>
</head>
<body>
    <?php unset($_SESSION['email']); ?>
    <?php unset($_SESSION['login_pass']); ?>
    <div class="container">
        <?php if(isset($sign_up) && $sign_up){ ?>
            <span>登録が完了しました。</span>
        <?php } ?>
        <?php if(isset($sign_out) && $sign_out){ ?>
            <span>ログアウトしました。</span>
        <?php } ?>
        <a class="title" href="../ctrl/">
            <img src="../images/other/logo.png" alt="よくある間違い〇✕クイズ">
        </a>
        <div class="form-container">
            <form action="../ctrl/index.php" method="post" class="login-form">
                <div>
                    メールアドレス<input type="email" name="email" value="">
                    <span class="err"><?= isset($arrErr['email']) ? $arrErr['email'] : "" ?></span>
                </div>
    			<div>
                    パスワード<input type="password" name="login_pass" value="">
                    <span class="err"><?= isset($arrErr['login_pass']) ? $arrErr['login_pass'] : "" ?></span>
                </div>
                <input type="hidden" name="step" value="1">
                <div>
                    <button type="submit">ログイン</button>
                    <span class="err"><?= isset($arrErr['common']) ? $arrErr['common'] : "" ?></span>
                </div>
            </form>
            <div style="display: flex; justify-content: center; align-items: center; gap: 5px;">
                <div style="height: 0.5px; width: 125px; background-color: #c2c2c2;"></div>
                <div style="width: 50px; color: #c2c2c2; font-size: 14px; text-align: center;">または</div>
                <div style="height: 0.5px; width: 125px; background-color: #c2c2c2;"></div>
            </div>
            <div class="other-form">
                <form action="../ctrl/" method="post">
                    <button type="submit">ゲストログイン</button>
                    <input type="hidden" name="guest" value="1">
                </form>
                <form action="../ctrl/sign_up.php" method="post">
                    <button type="submit">新規登録</button>
                </form>
            </div>
            <div class="login-benefits">
                <p class="login-benefits-title">ログインしたら可能になること</p>
                <ul>
                    <li>進捗（正解・不正解）の記録</li>
                    <li>復習リストへの問題の追加</li>
                    <li>マイページでのアカウント管理</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>