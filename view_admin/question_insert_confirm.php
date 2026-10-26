<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　クエスチョン追加確認</title>
</head>
<body>
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">クエスチョン追加内容の確認</h1>
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
                    <th>セクションID</th>
                    <th>順番</th>
                    <th>選択肢</th>
                    <th>正解番号</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($section_id); ?></td>
                    <td><?php echo htmlspecialchars($order_number); ?></td>
                    <td><?php echo htmlspecialchars($options); ?></td>
                    <td><?php echo htmlspecialchars($correct_answer); ?></td>
                </tr>
                </tbody>
            </table>
            </div>

            <div class="form-actions">
                <form action="../ctrl_admin/question.php" method="post" class="inline-form">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <input type="hidden" name="section_id" value="<?php echo htmlspecialchars($section_id); ?>">
                    <input type="hidden" name="order_number" value="<?php echo htmlspecialchars($order_number); ?>">
                    <input type="hidden" name="options" value="<?php echo htmlspecialchars($options); ?>">
                    <input type="hidden" name="correct_answer" value="<?php echo htmlspecialchars($correct_answer); ?>">
                    <input type="hidden" name="created_at" value="<?php echo htmlspecialchars($created_at); ?>">
                    <input type="hidden" name="updated_at" value="<?php echo htmlspecialchars($updated_at); ?>">
                    <input type="hidden" name="question_insert" value="true">
                    <button type="submit" class="btn btn-primary" title="この内容でセクションを追加します">✓ 追加する</button>
                </form>
                <form action="../ctrl_admin/question.php" method="post" class="inline-form">
                    <input type="hidden" name="step" value="">
                    <button type="submit" class="btn btn-secondary" title="追加せずに一覧へ戻ります">← キャンセルして戻る</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
