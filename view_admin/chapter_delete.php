<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　チャプター削除</title>
</head>
<body>
<<<<<<< HEAD
    <h2>以下のチャプターを削除しますか？</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>チャプター名</th>
            <th>フォルダー名</th>
            <th>表示順</th>
            <th>公開状況</th>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($id); ?></td>
            <td><?php echo htmlspecialchars($name); ?></td>
            <td><?php echo htmlspecialchars($folder_name); ?></td>
            <td><?php echo htmlspecialchars($order_number); ?></td>
            <td><?php echo htmlspecialchars($is_published); ?></td>
        </tr>
    </table>
    <form action="../ctrl_admin/chapter_delete.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <input type="hidden" name="step" value="2">
        <button type="submit">削除</button>
    </form>
    <form action="../ctrl_admin/chapter.php" method="post">
        <input type="hidden" name="step" value="">
        <button type="submit">戻る</button>
    </form>
=======
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">チャプターの削除確認</h1>
                <p class="admin-subtitle">内容を確認してから削除してください</p>
            </div>
        </header>

        <div class="notice notice-danger">
            <span class="notice-icon">⚠</span>
            <span><strong>この操作は取り消せません。</strong>以下のチャプターを本当に削除しますか？</span>
        </div>

        <div class="admin-panel">
            <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>チャプター名</th>
                    <th>フォルダー名</th>
                    <th>順番</th>
                    <th>公開状況</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="col-id"><?php echo htmlspecialchars($id); ?></td>
                    <td><?php echo htmlspecialchars($name); ?></td>
                    <td><?php echo htmlspecialchars($folder_name); ?></td>
                    <td><?php echo htmlspecialchars($order_number); ?></td>
                    <td><?php echo htmlspecialchars($is_published); ?></td>
                </tr>
                </tbody>
            </table>
            </div>

            <div class="form-actions">
                <form action="../ctrl_admin/chapter_delete.php" method="post" class="inline-form">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <input type="hidden" name="step" value="2">
                    <button type="submit" class="btn btn-danger" title="このチャプターを完全に削除します">✕ 削除する</button>
                </form>
                <form action="../ctrl_admin/chapter.php" method="post" class="inline-form">
                    <input type="hidden" name="step" value="">
                    <button type="submit" class="btn btn-secondary" title="削除せずに一覧へ戻ります">← キャンセルして戻る</button>
                </form>
            </div>
        </div>
    </div>
>>>>>>> origin/main
</body>
</html>
