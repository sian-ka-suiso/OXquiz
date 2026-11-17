<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　エクスプラネーション追加</title>
</head>
<body>
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">エクスプラネーションの新規追加</h1>
                <?php require('../view_admin/parts/header_role.php'); ?>
            </div>
        </header>

        <p class="admin-description">内容を入力して追加してください</p>

        <div class="admin-panel">
            <form id="explanationInsertForm" action="../ctrl_admin/explanation_insert.php" method="post">
                <table class="admin-form-table">
                    <tr>
                        <th>クエスチョンID</th>
                        <td>
                            <input type="text" name="question_id" value="<?= $question_id ?>">
                            <span class="err"><?= isset($arrErr['question_id']) ? $arrErr['question_id'] : "" ?></span>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <input type="hidden" name="step" value="1">
            </form>
            <div class="form-actions">
                <button type="submit" form="explanationInsertForm" class="btn btn-primary" title="入力内容の確認画面へ進みます">確認画面へ進む</button>
                <form action="../ctrl_admin/explanation.php" method="post" class="inline-form">
                    <input type="hidden" name="step" value="">
                    <button type="submit" class="btn btn-secondary" title="追加せずに一覧へ戻ります">← キャンセルして戻る</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
