<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　エクスプラネーション一覧</title>
</head>
<body>
    <div class="admin-page admin-page--wide">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">📘 エクスプラネーション一覧</h1>
                <?php require('../view_admin/parts/header_role.php'); ?>
            </div>
            <div class="admin-header-actions">
                <form action="../ctrl_admin/admin.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-ghost" title="管理者トップページに戻ります">← 管理トップへ戻る</button>
                </form>
            </div>
        </header>

        <p class="admin-description">エクスプラネーション（Explanation）の確認・追加・編集・削除ができます</p>

        <div class="admin-toolbar">
            <div class="toolbar-group">
                <form action="../ctrl_admin/explanation.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-secondary" title="最新の解説一覧を再読み込みします">
                        <span class="btn-icon">↻</span> 更新
                    </button>
                </form>
                <?php if($is_admin): ?>
                <form action="../ctrl_admin/explanation_insert.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-primary" title="新しい解説を追加する画面へ進みます">
                        <span class="btn-icon">＋</span> 新規追加
                    </button>
                </form>
                <?php endif; ?>
            </div>
            <!-- 検索・並び替え（条件がURLに残るようGETで送る） -->
            <form action="../ctrl_admin/explanation.php" method="get" class="toolbar-group search-form">
                <label>ID
                    <input type="text" name="search_id" value="<?= V2H($search_id) ?>" inputmode="numeric" placeholder="例：3" class="search-id">
                </label>
                <label>クエスチョンID
                    <input type="text" name="search_question_id" value="<?= V2H($search_question_id) ?>" inputmode="numeric" placeholder="例：3" class="search-id">
                </label>
                <label>並び順
                    <select name="sort">
                        <?php foreach ($sortOptions as $value => $label): ?>
                        <option value="<?= V2H($value) ?>" <?= $sort === $value ? 'selected' : '' ?>><?= V2H($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button type="submit" class="btn btn-primary" title="条件に合うチャプターを表示します">検索</button>
                <a href="../ctrl_admin/explanation.php" class="btn btn-ghost" title="検索条件をクリアして全件表示に戻します">クリア</a>
            </form>
        </div>
        <?php foreach ($arrErr as $err): ?>
        <p class="err search-err"><?= V2H($err) ?></p>
        <?php endforeach; ?>

        <div class="table-wrapper">
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>クエスチョンID</th>
                <th class="col-actions">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($explanation_data)): ?>
            <tr class="empty-row"><td colspan="6">条件に合う解説はありません</td></tr>
            <?php endif; ?>
            <?php foreach($explanation_data as $exp): ?>
            <tr>
                <td class="col-id"><?php echo V2H($exp['id']); ?></td>
                <td><?php echo V2H($exp['question_id']); ?></td>
                <td class="col-actions">
                    <div class="row-actions">
                        <?php if($is_admin): ?>
                        <form action="../ctrl_admin/explanation_update.php" method="post" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo V2H($exp['id']); ?>">
                            <input type="hidden" name="question_id" value="<?php echo V2H($exp['question_id']); ?>">
                            <button type="submit" name="step" value="1" class="btn btn-small btn-edit" title="この解説の内容を編集します">✏️ 編集</button>
                        </form>
                        <form action="../ctrl_admin/explanation_delete.php" method="post" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo V2H($exp['id']); ?>">
                            <input type="hidden" name="question_id" value="<?php echo V2H($exp['question_id']); ?>">
                            <button type="submit" name="step" value="1" class="btn btn-small btn-danger" title="この解説を削除します（確認画面へ進みます）">✕ 削除</button>
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
