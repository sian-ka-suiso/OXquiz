<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　エクスプラネーション削除</title>
</head>
<body>
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">エクスプラネーションの削除確認</h1>
                <?php require('../view_admin/parts/header_role.php'); ?>
            </div>
        </header>

        <p class="admin-description">内容を確認してから削除してください</p>

        <div class="notice notice-danger">
            <span class="notice-icon">⚠</span>
            <span><strong>この操作は取り消せません。</strong>以下のエクスプラネーションを本当に削除しますか？</span>
        </div>

        <div class="admin-panel">
            <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>クエスチョンID</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="col-id"><?php echo htmlspecialchars($id); ?></td>
                    <td><?php echo htmlspecialchars($question_id); ?></td>
                </tr>
                </tbody>
            </table>
            </div>

            <div class="form-actions">
                <form action="../ctrl_admin/explanation_delete.php" method="post" class="inline-form">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <input type="hidden" name="step" value="2">
                    <button type="submit" class="btn btn-danger" title="このエクスプラネーションを完全に削除します">✕ 削除する</button>
                </form>
                <form action="../ctrl_admin/explanation.php" method="post" class="inline-form">
                    <input type="hidden" name="step" value="">
                    <button type="submit" class="btn btn-secondary" title="削除せずに一覧へ戻ります">← キャンセルして戻る</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
