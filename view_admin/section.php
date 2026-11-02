<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　セクション一覧</title>
</head>
<body>
    <div class="admin-page admin-page--wide">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">📗 セクション一覧</h1>
                <?php require('../view_admin/parts/header_role.php'); ?>
            </div>
            <div class="admin-header-actions">
                <form action="../ctrl_admin/admin.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-ghost" title="管理者トップページに戻ります">← 管理トップへ戻る</button>
                </form>
            </div>
        </header>

        <p class="admin-description">節（Section）の確認・追加・編集・削除ができます</p>

        <div class="admin-toolbar">
            <div class="toolbar-group">
                <form action="../ctrl_admin/section.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-secondary" title="最新のセクション一覧を再読み込みします">
                        <span class="btn-icon">↻</span> 更新
                    </button>
                </form>
                <?php if($is_admin): ?>
                <form action="../ctrl_admin/section_insert.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-primary" title="新しいセクションを追加する画面へ進みます">
                        <span class="btn-icon">＋</span> 新規追加
                    </button>
                </form>
                <?php endif; ?>
            </div>
            <!-- 検索・並び替え（条件がURLに残るようGETで送る） -->
            <form action="../ctrl_admin/section.php" method="get" class="toolbar-group search-form">
                <label>ID
                    <input type="text" name="search_id" value="<?= V2H($search_id) ?>" inputmode="numeric" placeholder="例：3" class="search-id">
                </label>
                <label>並び順
                    <select name="sort">
                        <?php foreach ($sortOptions as $value => $label): ?>
                        <option value="<?= V2H($value) ?>" <?= $sort === $value ? 'selected' : '' ?>><?= V2H($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button type="submit" class="btn btn-primary" title="条件に合うセクションを表示します">検索</button>
                <a href="../ctrl_admin/section.php" class="btn btn-ghost" title="検索条件をクリアして全件表示に戻します">クリア</a>
            </form>
        </div>
        <?php if (isset($arrErr['search_id'])): ?>
        <p class="err search-err"><?= V2H($arrErr['search_id']) ?></p>
        <?php endif; ?>

        <div class="table-wrapper">
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>チャプターID</th>
                <th>セクション名</th>
                <th>フォルダー名</th>
                <th>表示順</th>
                <th>公開状況</th>
                <th class="col-actions">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($section_data)): ?>
            <tr class="empty-row"><td colspan="7">条件に合うセクションはありません</td></tr>
            <?php endif; ?>
            <?php foreach($section_data as $sec): ?>
            <tr>
                <td class="col-id"><?php echo V2H($sec['id']); ?></td>
                <td><?php echo V2H($sec['chapter_id']); ?></td>
                <td><?php echo V2H($sec['name']); ?></td>
                <td><?php echo V2H($sec['folder_name']); ?></td>
                <td><?php echo V2H($sec['order_number']); ?></td>
                <td>
                    <?php if ($sec['is_published'] == 1): ?>
                        <span class="badge badge-published">公開中</span>
                    <?php else: ?>
                        <span class="badge badge-unpublished">非公開</span>
                    <?php endif; ?>
                </td>
                <td class="col-actions">
                    <div class="row-actions">
                        <?php if($is_admin): ?>
                        <form action="../ctrl_admin/section_update.php" method="post" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo V2H($sec['id']); ?>">
                            <input type="hidden" name="chapter_id" value="<?php echo V2H($sec['chapter_id']); ?>">
                            <input type="hidden" name="name" value="<?php echo V2H($sec['name']); ?>">
                            <input type="hidden" name="folder_name" value="<?php echo V2H($sec['folder_name']); ?>">
                            <input type="hidden" name="order_number" value="<?php echo V2H($sec['order_number']); ?>">
                            <input type="hidden" name="is_published" value="<?php echo V2H($sec['is_published']); ?>">
                            <button type="submit" name="step" value="1" class="btn btn-small btn-edit" title="このセクションの内容を編集します">✏️ 編集</button>
                        </form>
                        <form action="../ctrl_admin/section_delete.php" method="post" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo V2H($sec['id']); ?>">
                            <input type="hidden" name="chapter_id" value="<?php echo V2H($sec['chapter_id']); ?>">
                            <input type="hidden" name="name" value="<?php echo V2H($sec['name']); ?>">
                            <input type="hidden" name="folder_name" value="<?php echo V2H($sec['folder_name']); ?>">
                            <input type="hidden" name="order_number" value="<?php echo V2H($sec['order_number']); ?>">
                            <input type="hidden" name="is_published" value="<?php echo V2H($sec['is_published']); ?>">
                            <button type="submit" name="step" value="1" class="btn btn-small btn-danger" title="このセクションを削除します（確認画面へ進みます）">✕ 削除</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</body>
</html>
