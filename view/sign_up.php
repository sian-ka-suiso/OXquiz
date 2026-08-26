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
    <title>よくある間違いOXクイズ　新規登録</title>
</head>
<body>
    <a href="../ctrl/index.php">
        <img src="../images/other/logo.png" alt="よくある間違い〇✕クイズ">
    </a>
    <div style="font-size: 22px; font-weight: bold;">新規登録</div>
    <form action="../ctrl/sign_up.php" method="post">
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
        <div class="form-group">
            <label for="class_id">クラス</label>
            <select name="class_id" id="class_id">
                <option value="">選択してください</option>
                <option value="0" <?= ($class_id === "0" || $class_id === 0) ? 'selected' : '' ?>>所属なし</option>
                <?php foreach ($arrClasses as $class): ?>
                <option value="<?= $class['id'] ?>" <?= ($class_id == $class['id']) ? 'selected' : '' ?>><?= $class['class_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <div class="err">
                <?= isset($arrErr['class_id']) ? $arrErr['class_id'] : "" ?>
            </div>
        </div>
        <div class="form-group tos-group">
            <label class="tos-check">
                <input type="checkbox" name="tos_agree" value="1" <?= $tos_agree ? 'checked' : '' ?>>
                <span><button type="button" class="tos-open-btn" data-tos-open>利用規約</button>に同意する</span>
            </label>
            <div class="err">
                <?= isset($arrErr['tos_agree']) ? $arrErr['tos_agree'] : "" ?>
            </div>
        </div>
        <div class="btn-area">
            <a href="../ctrl/">戻る</a>
            <div>
                <button type="submit">入力確認</button>
                <input type="hidden" name="step" value="1">
            </div>
        </div>
    </form>

    <?php require_once __DIR__ . '/parts/tos_modal.php'; ?>
</body>
</html>