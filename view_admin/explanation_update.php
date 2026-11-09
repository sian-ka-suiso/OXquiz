<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　エクスプラネーション編集</title>
</head>
<body>
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">エクスプラネーションの編集</h1>
                <?php require('../view_admin/parts/header_role.php'); ?>
            </div>
        </header>

        <p class="admin-description">「変更後」の内容を書き換えて更新してください</p>

        <div class="admin-panel">
            <div class="compare-grid">
                <div>
                    <p class="panel-heading is-before">変更前</p>
                    <table class="admin-form-table">
                        <tr><th>ID</th><td class="field-value"><?php echo htmlspecialchars($id); ?></td></tr>
                        <tr><th>クエスチョンID</th><td class="field-value"><?php echo htmlspecialchars($question_id); ?></td></tr>
                    </table>
                </div>
                <div>
                    <p class="panel-heading is-after">変更後</p>
                    <form id="explanationUpdateForm" action="../ctrl_admin/explanation_update.php" method="post">
                        <table class="admin-form-table">
                            <tr><th>ID</th><td class="field-value"><?php echo htmlspecialchars($id); ?></td></tr>
                            <tr>
                                <th>クエスチョンID</th>
                                <td><input type="text" name="question_id" value="<?= $question_id ?>"></td>
                            </tr>
                        </table>
                        <input type="hidden" name="step" value="2">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    </form>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" form="explanationUpdateForm" class="btn btn-primary" title="変更後の内容で更新します">✓ 変更する</button>
                <form action="../ctrl_admin/explanation.php" method="post" class="inline-form">
                    <input type="hidden" name="step" value="">
                    <button type="submit" class="btn btn-secondary" title="変更せずに一覧へ戻ります">← キャンセルして戻る</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
