<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　仮パスワード発行</title>
</head>
<body>
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">仮パスワードの発行確認</h1>
                <p class="admin-subtitle">内容を確認してから設定してください</p>
            </div>
        </header>

        <div class="notice notice-warning">
            <span class="notice-icon">⚠</span>
            <span><strong>仮パスワードを設定すると、元のパスワードは上書きされます。</strong>取り消せませんのでご注意ください。</span>
        </div>

        <div class="admin-panel">
            <div class="temp-pass-box">
                <span class="temp-pass-label">発行される仮パスワード</span>
                <span class="temp-pass-value"><?php echo V2H($temp_pass) ?></span>
            </div>

            <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>メールアドレス</th>
                    <th>ユーザー名</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="col-id"><?php echo V2H($id); ?></td>
                    <td><?php echo V2H($email); ?></td>
                    <td><?php echo V2H($user_name); ?></td>
                </tr>
                </tbody>
            </table>
            </div>

            <div class="form-actions">
                <form action="../ctrl_admin/temp_pass.php" method="post" class="inline-form">
                    <input type="hidden" name="id" value="<?php echo V2H($id); ?>">
                    <input type="hidden" name="step" value="2">
                    <input type="hidden" name="temp_pass" value="<?php echo V2H($temp_pass) ?>">
                    <button type="submit" class="btn btn-warning" title="このユーザーの仮パスワードを設定します">🔑 仮パスワードを設定</button>
                </form>
                <form action="../ctrl_admin/user.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-secondary" title="発行せずに一覧へ戻ります">← キャンセルして戻る</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
