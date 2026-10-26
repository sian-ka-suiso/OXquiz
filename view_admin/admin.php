<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　管理者ページ</title>
</head>
<body>
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">管理者トップページ</h1>
                <?php require('../view_admin/parts/header_role.php'); ?>
            </div>
            <div class="admin-header-actions">
                <form action="../ctrl_admin/adm_login.php" method="post" class="inline-form">
                    <input type="hidden" name="sign_out" value="true">
                    <button type="submit" class="btn btn-ghost" title="ログアウトして管理者ログイン画面に戻ります">ログアウト</button>
                </form>
            </div>
        </header>

        <p class="admin-description">編集したいデータベースを選んでください</p>

        <div class="menu-grid">
            <a class="menu-card" href="../ctrl_admin/user.php" title="アカウントの一覧確認・編集・削除・仮パスワード発行ができます">
                <span class="menu-card-icon">👤</span>
                <span class="menu-card-title">アカウント</span>
                <span class="menu-card-desc">アカウント情報の確認・編集・削除</span>
            </a>
            <a class="menu-card" href="../ctrl_admin/chapter.php" title="章（Chapter）の一覧確認・追加・編集・削除ができます">
                <span class="menu-card-icon">📘</span>
                <span class="menu-card-title">章（Chapter）</span>
                <span class="menu-card-desc">章の一覧・追加・編集・削除</span>
            </a>
            <a class="menu-card" href="../ctrl_admin/section.php" title="節（Section）の一覧確認・追加・編集・削除ができます">
                <span class="menu-card-icon">📗</span>
                <span class="menu-card-title">節（Section）</span>
                <span class="menu-card-desc">節の一覧・追加・編集・削除</span>
            </a>
            <a class="menu-card" href="../ctrl_admin/question.php" title="問題（Question）の一覧確認・追加">
                <span class="menu-card-icon">📕</span>
                <span class="menu-card-title">問題</span>
                <span class="menu-card-desc">問題の一覧・追加・編集・削除</span>
            </a>
        </div>
    </div>
</body>
</html>
