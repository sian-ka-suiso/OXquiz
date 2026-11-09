<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　解説追加確認</title>
</head>
<body>
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">解説追加内容の確認</h1>
                <?php require('../view_admin/parts/header_role.php'); ?>
            </div>
        </header>

        <p class="admin-description">この内容でよろしければ「追加する」を押してください</p>

        <div class="notice notice-info">
            <span class="notice-icon">ℹ</span>
            <span>まだ追加は完了していません。内容を確認してから確定してください。</span>
        </div>

        <div class="admin-panel">
            <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                <tr>
                    <th>問題ID</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($question_id); ?></td>
                </tr>
                </tbody>
            </table>
            </div>

            <div class="form-actions">
                <form action="../ctrl_admin/explanation.php" method="post" class="inline-form">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($question_id); ?>">
                    <input type="hidden" name="explanation_insert" value="true">
                    <button type="submit" class="btn btn-primary" title="この内容で解説を追加します">✓ 追加する</button>
                </form>
                <form action="../ctrl_admin/explanation.php" method="post" class="inline-form">
                    <input type="hidden" name="step" value="">
                    <button type="submit" class="btn btn-secondary" title="追加せずに一覧へ戻ります">← キャンセルして戻る</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
