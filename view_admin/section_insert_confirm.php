<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　セクション追加確認</title>
</head>
<body>
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">セクション追加内容の確認</h1>
                <p class="admin-subtitle">この内容でよろしければ「追加する」を押してください</p>
            </div>
        </header>

        <div class="notice notice-info">
            <span class="notice-icon">ℹ</span>
            <span>まだ追加は完了していません。内容を確認してから確定してください。</span>
        </div>

        <div class="admin-panel">
            <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                <tr>
                    <th>チャプターID</th>
                    <th>セクション名</th>
                    <th>フォルダー名</th>
                    <th>順番</th>
                    <th>公開状況</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($chapter_id); ?></td>
                    <td><?php echo htmlspecialchars($name); ?></td>
                    <td><?php echo htmlspecialchars($folder_name); ?></td>
                    <td><?php echo htmlspecialchars($order_number); ?></td>
                    <td><?php echo htmlspecialchars($is_published); ?></td>
                </tr>
                </tbody>
            </table>
            </div>

            <div class="form-actions">
                <form action="../ctrl_admin/section.php" method="post" class="inline-form">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <input type="hidden" name="chapter_id" value="<?php echo htmlspecialchars($chapter_id); ?>">
                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                    <input type="hidden" name="folder_name" value="<?php echo htmlspecialchars($folder_name); ?>">
                    <input type="hidden" name="order_number" value="<?php echo htmlspecialchars($order_number); ?>">
                    <input type="hidden" name="is_published" value="<?php echo htmlspecialchars($is_published); ?>">
                    <input type="hidden" name="section_insert" value="true">
                    <button type="submit" class="btn btn-primary" title="この内容でセクションを追加します">✓ 追加する</button>
                </form>
                <form action="../ctrl_admin/section.php" method="post" class="inline-form">
                    <input type="hidden" name="step" value="">
                    <button type="submit" class="btn btn-secondary" title="追加せずに一覧へ戻ります">← キャンセルして戻る</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
