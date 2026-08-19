<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/sign_up.css">
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=RocknRoll+One&family=WDXL+Lubrifont+JP+N&display=swap" rel="stylesheet">
    <title>よくある間違いOXクイズ　新規登録</title>
</head>
<body>
    <a href="./">
        <img src="images/other/logo.png" alt="よくある間違い〇✕クイズ">
    </a>
    <div style="font-size: 22px; font-weight: bold;">新規登録</div>
    <form action="sign_up" method="post">
        <div class="form-group">
            <label for="email">メールアドレス<span style="font-size: 14px; color: #353535;">（大学のアドレス）</span></label>
            <input type="email" name="email" value="<?= $email ?>" placeholder="例 : c1234567@st.kanazawa-it.ac.jp">
            <div class="err">
                <?= isset($arrErr['email']) ? $arrErr['email'] : "" ?>
            </div>
        </div>
        <div class="form-group">
            <label for="password">パスワード<span style="font-size: 14px; color: #353535;">（英数字６文字以上）</span></label>
            <input type="password" name="login_pass" value="<?= $login_pass ?>" placeholder="">
            <div class="err">
                <?= isset($arrErr['login_pass']) ? $arrErr['login_pass'] : "" ?>
            </div>
        </div>
        <div class="form-group">
            <label for="password">パスワード<span style="font-size: 14px; color: #353535;">（確認）</span></label>
            <input type="password" name="login_pass2" value="<?= $login_pass2 ?>" placeholder="">
            <div class="err">
                <?= isset($arrErr['login_pass2']) ? $arrErr['login_pass2'] : "" ?>
            </div>
        </div>
        <div class="btn-area">
            <a href="./">戻る</a>
            <div>
                <button type="submit">入力確認</button>
                <input type="hidden" name="step" value="1">
            </div>
        </div>
    </form>
</body>
</html>