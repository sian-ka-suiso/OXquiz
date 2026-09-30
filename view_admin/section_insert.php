<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　セクション追加</title>
</head>
<body>
<<<<<<< HEAD
    <h2>以下のセクションを追加します</h2>
    <div class="tables">

        <form action="../ctrl_admin/section_insert.php" method="post">
            <table border="1">
                <h3>追加内容</h3>
                <tr>
                    <th>チャプターID</th>
                    <th>セクション名</th>
                    <th>フォルダー名</th>
                    <th>表示順</th>
                    <th>公開状況</th>
                </tr>
                <tr>
                    <td>
                        <input type="text" name="chapter_id" value="<?= $chapter_id ?>">
                        <div class="err">
                            <?= isset($arrErr['chapter_id']) ? $arrErr['chapter_id'] : "" ?>
                        </div>
                    </td>
                    <td>
                        <input type="text" name="name" value="<?= $name ?>">
                        <div class="err">
                            <?= isset($arrErr['name']) ? $arrErr['name'] : "" ?>
                        </div>
                    </td>
                    <td>
                        <input type="text" name="folder_name" value="<?= $folder_name ?>">
                        <div class="err">
                            <?= isset($arrErr['folder_name']) ? $arrErr['folder_name'] : "" ?>
                        </div>
                    </td>
                    <td>
                        <input type="text" name="order_number" value="<?= $order_number ?>">
                        <div class="err">
                            <?= isset($arrErr['order_number']) ? $arrErr['order_number'] : "" ?>
                        </div>
                    </td>
                    <td>
                        <select name="is_published" id="">
                            <option value="0" <?= $is_published == 0 ? 'selected' : '' ?>>０</option>
                            <option value="1" <?= $is_published == 1 ? 'selected' : '' ?>>１</option>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <button type="submit">追加する</button>
            <input type="hidden" name="step" value="1">
        </form>
=======
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">セクションの新規追加</h1>
                <p class="admin-subtitle">内容を入力して追加してください</p>
            </div>
        </header>

        <div class="admin-panel">
            <form id="sectionInsertForm" action="../ctrl_admin/section_insert.php" method="post">
                <table class="admin-form-table">
                    <tr>
                        <th>チャプターID</th>
                        <td>
                            <input type="text" name="chapter_id" value="<?= $chapter_id ?>">
                            <span class="err"><?= isset($arrErr['chapter_id']) ? $arrErr['chapter_id'] : "" ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th>セクション名</th>
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
                        <th>順番（order）</th>
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
                <button type="submit" form="sectionInsertForm" class="btn btn-primary" title="入力内容の確認画面へ進みます">確認画面へ進む</button>
                <form action="../ctrl_admin/section.php" method="post" class="inline-form">
                    <input type="hidden" name="step" value="">
                    <button type="submit" class="btn btn-secondary" title="追加せずに一覧へ戻ります">← キャンセルして戻る</button>
                </form>
            </div>
        </div>
>>>>>>> origin/main
    </div>
</body>
</html>
