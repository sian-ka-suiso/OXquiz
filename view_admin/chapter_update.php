<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　チャプター編集</title>
</head>
<body>
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">チャプターの編集</h1>
                <p class="admin-subtitle">「変更後」の内容を書き換えて更新してください</p>
            </div>
        </header>

        <div class="admin-panel">
            <div class="compare-grid">
                <div>
                    <p class="panel-heading is-before">変更前</p>
                    <table class="admin-form-table">
                        <tr><th>ID</th><td class="field-value"><?php echo htmlspecialchars($id); ?></td></tr>
                        <tr><th>チャプター名</th><td class="field-value"><?php echo htmlspecialchars($name); ?></td></tr>
                        <tr><th>フォルダー名</th><td class="field-value"><?php echo htmlspecialchars($folder_name); ?></td></tr>
                        <tr><th>順番</th><td class="field-value"><?php echo htmlspecialchars($order_number); ?></td></tr>
                        <tr><th>公開状況</th><td class="field-value"><?php echo htmlspecialchars($is_published); ?></td></tr>
                    </table>
                </div>
                <div>
                    <p class="panel-heading is-after">変更後</p>
                    <form id="chapterUpdateForm" action="../ctrl_admin/chapter_update.php" method="post">
                        <table class="admin-form-table">
                            <tr><th>ID</th><td class="field-value"><?php echo htmlspecialchars($id); ?></td></tr>
                            <tr>
                                <th>チャプター名</th>
                                <td><input type="text" name="name" value="<?= $name ?>"></td>
                            </tr>
                            <tr>
                                <th>フォルダー名</th>
                                <td><input type="text" name="folder_name" value="<?= $folder_name ?>"></td>
                            </tr>
                            <tr>
                                <th>順番</th>
                                <td><input type="text" name="order_number" value="<?= $order_number ?>"></td>
                            </tr>
                            <tr>
                                <th>公開状況</th>
                                <td>
                                    <select name="is_published">
                                        <option value="0" <?= $is_published == 0 ? 'selected' : '' ?>>非公開（０）</option>
                                        <option value="1" <?= $is_published == 1 ? 'selected' : '' ?>>公開中（１）</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" name="step" value="2">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    </form>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" form="chapterUpdateForm" class="btn btn-primary" title="変更後の内容で更新します">✓ 変更する</button>
                <form action="../ctrl_admin/chapter.php" method="post" class="inline-form">
                    <input type="hidden" name="step" value="">
                    <button type="submit" class="btn btn-secondary" title="変更せずに一覧へ戻ります">← キャンセルして戻る</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
