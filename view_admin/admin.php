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
                <p class="admin-subtitle">編集したいデータベースを選んでください</p>
            </div>
            <div class="admin-header-actions">
                <form action="../ctrl_admin/adm_login.php" method="post" class="inline-form">
                    <input type="hidden" name="sign_out" value="true">
                    <button type="submit" class="btn btn-ghost" title="ログアウトして管理者ログイン画面に戻ります">ログアウト</button>
                </form>
            </div>
        </header>

        <p class="admin-role">ログイン権限（admin_role）：<strong><?= htmlspecialchars($admin_role) ?></strong></p>

        <div class="menu-grid">
            <a class="menu-card" href="../ctrl_admin/user.php" title="ユーザーの一覧確認・編集・削除・仮パスワード発行ができます">
                <span class="menu-card-icon">👤</span>
                <span class="menu-card-title">ユーザー</span>
                <span class="menu-card-desc">ユーザー情報の確認・編集・削除</span>
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
            <span class="menu-card is-disabled" title="未実装のページです">
                <span class="menu-card-icon">❓</span>
                <span class="menu-card-title">問題・解説</span>
                <span class="menu-card-desc">準備中</span>
            </span>
            <span class="menu-card is-disabled" title="未実装のページです">
                <span class="menu-card-icon">🧭</span>
                <span class="menu-card-title">数学ナビ</span>
                <span class="menu-card-desc">準備中</span>
            </span>
        </div>
    </div>
</body>
</html>
