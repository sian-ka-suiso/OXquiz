<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>よくある間違い〇✕クイズ 利用規約への同意</title>
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/variables.css">
    <link rel="stylesheet" type="text/css" href="../css/tos_agree.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Zen+Kaku+Gothic+New&display=swap" rel="stylesheet">
</head>

<body>
    <div class="tos-gate">
        <a href="../ctrl/" class="tos-gate-logo">
            <img src="../images/other/logo.png" alt="よくある間違い〇✕クイズ">
        </a>

        <h1 class="tos-gate-title">利用規約への同意のお願い</h1>
        <p class="tos-gate-lead">利用規約を更新しました。本アプリのご利用を続けるには、内容をご確認のうえ同意していただく必要があります。</p>

        <div class="tos-gate-box">
            <?= renderTosHtml(file_get_contents(__DIR__ . '/../ToS.md')) ?>
        </div>

        <form action="tos_agree.php" method="post">
            <div class="err">
                <?= isset($arrErr['tos_agree']) ? $arrErr['tos_agree'] : "" ?>
            </div>

            <div class="tos-gate-actions">
                <label class="tos-check">
                    <input type="checkbox" name="tos_agree" value="1">
                    <span>利用規約に同意する</span>
                </label>
                <button type="submit" name="submit_tos" value="1" class="tos-gate-submit">同意して始める</button>
            </div>
        </form>

        <form action="../ctrl/" method="post" class="tos-gate-logout-form">
            <input type="hidden" name="sign_out" value="true">
            <button type="submit" class="tos-gate-logout">同意せずログアウトする</button>
        </form>
    </div>
</body>

</html>
