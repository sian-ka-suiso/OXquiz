<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　クエスチョン編集</title>
</head>
<body>
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">クエスチョンの編集</h1>
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
                        <tr><th>セクションID</th><td class="field-value"><?php echo htmlspecialchars($section_id); ?></td></tr>
                        <tr><th>順番</th><td class="field-value"><?php echo htmlspecialchars($order_number); ?></td></tr>
                        <tr><th>選択肢</th><td class="field-value"><?php echo htmlspecialchars($options); ?></td></tr>
                        <tr><th>正解番号</th><td class="field-value"><?php echo htmlspecialchars($correct_answer); ?></td></tr>
                    </table>
                </div>
                <div>
                    <p class="panel-heading is-after">変更後</p>
                    <form id="questionUpdateForm" action="../ctrl_admin/question_update.php" method="post">
                        <table class="admin-form-table">
                            <tr><th>ID</th><td class="field-value"><?php echo htmlspecialchars($id); ?></td></tr>
                            <tr>
                                <th>セクションID</th>
                                <td><input type="text" name="section_id" value="<?= $section_id ?>"></td>
                            </tr>
                            <tr>
                                <th>順番</th>
                                <td><input type="text" name="order_number" value="<?= $order_number ?>"></td>
                            </tr>
                            <tr>
                                <th>選択肢</th>
                                <td><input type="text" name="options" value="<?= $options ?>"></td>
                            </tr>
                            <tr>
                                <th>正解番号</th>
                                <td><input type="text" name="correct_answer" value="<?= $correct_answer ?>"></td>
                            </tr>
                        </table>
                        <input type="hidden" name="step" value="2">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    </form>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" form="questionUpdateForm" class="btn btn-primary" title="変更後の内容で更新します">✓ 変更する</button>
                <form action="../ctrl_admin/question.php" method="post" class="inline-form">
                    <input type="hidden" name="step" value="">
                    <button type="submit" class="btn btn-secondary" title="変更せずに一覧へ戻ります">← キャンセルして戻る</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
