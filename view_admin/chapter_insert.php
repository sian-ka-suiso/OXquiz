<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　チャプター追加</title>
</head>
<body>
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">チャプターの新規追加</h1>
                <p class="admin-subtitle">内容を入力して追加してください</p>
            </div>
        </header>

        <div class="admin-panel">
            <form id="chapterInsertForm" action="../ctrl_admin/chapter_insert.php" method="post">
                <table class="admin-form-table">
                    <tr>
                        <th>チャプター名</th>
                        <td>
                            <input type="text" name="name" value="<?= $name ?>">
                            <span class="err"><?= isset($arrErr['name']) ? $arrErr['name'] : "" ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th>フォルダー名</th>
                        <td>
                            <input type="text" name="folder_name" value="<?= $folder_name ?>">
                            <span class="err"><?= isset($arrErr['folder_name']) ? $arrErr['folder_name'] : "" ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th>表示順</th>
                        <td>
                            <input type="text" name="order_number" value="<?= $order_number ?>">
                            <span class="err"><?= isset($arrErr['order_number']) ? $arrErr['order_number'] : "" ?></span>
                        </td>
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
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <input type="hidden" name="step" value="1">
            </form>
            <div class="form-actions">
                <button type="submit" form="chapterInsertForm" class="btn btn-primary" title="入力内容の確認画面へ進みます">確認画面へ進む</button>
                <form action="../ctrl_admin/chapter.php" method="post" class="inline-form">
                    <input type="hidden" name="step" value="">
                    <button type="submit" class="btn btn-secondary" title="追加せずに一覧へ戻ります">← キャンセルして戻る</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
