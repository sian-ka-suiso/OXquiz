<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　問題一覧</title>
</head>
<body>
    <div class="admin-page admin-page--wide">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">📗 問題一覧</h1>
                <?php require('../view_admin/parts/header_role.php'); ?>
            </div>
            <div class="admin-header-actions">
                <form action="../ctrl_admin/admin.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-ghost" title="管理者トップページに戻ります">← 管理トップへ戻る</button>
                </form>
            </div>
        </header>

        <p class="admin-description">問題（Question）の確認・追加・編集・削除ができます</p>

        <div class="admin-toolbar">
            <div class="toolbar-group">
                <form action="../ctrl_admin/question.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-secondary" title="最新の問題一覧を再読み込みします">
                        <span class="btn-icon">↻</span> 更新
                    </button>
                </form>
                <?php if($is_admin): ?>
                <form action="../ctrl_admin/question_insert.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-primary" title="新しい問題を追加する画面へ進みます">
                        <span class="btn-icon">＋</span> 新規追加
                    </button>
                </form>
                <?php endif; ?>
            </div>
            <!-- 検索・並び替え（条件がURLに残るようGETで送る） -->
            <form action="../ctrl_admin/question.php" method="get" class="toolbar-group search-form">
                <label>ID
                    <input type="text" name="search_id" value="<?= V2H($search_id) ?>" inputmode="numeric" placeholder="例：3" class="search-id">
                </label>
                <label>セクションID
                    <input type="text" name="search_section_id" value="<?= V2H($search_section_id) ?>" inputmode="numeric" placeholder="例：3" class="search-id">
                </label>
                <label>並び順
                    <select name="sort">
                        <?php foreach ($sortOptions as $value => $label): ?>
                        <option value="<?= V2H($value) ?>" <?= $sort === $value ? 'selected' : '' ?>><?= V2H($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button type="submit" class="btn btn-primary" title="条件に合う問題を表示します">検索</button>
                <a href="../ctrl_admin/question.php" class="btn btn-ghost" title="検索条件をクリアして全件表示に戻します">クリア</a>
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
                <th>セクションID</th>
                <th>表示順</th>
                <th>選択肢数</th>
                <th>正解番号</th>
                <th class="col-actions">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($question_data)): ?>
            <tr class="empty-row"><td colspan="6">条件に合う問題はありません</td></tr>
            <?php endif; ?>
            <?php foreach($question_data as $que): ?>
            <tr>
                <td class="col-id"><?php echo V2H($que['id']); ?></td>
                <td><?php echo V2H($que['section_id']); ?></td>
                <td><?php echo V2H($que['order_number']); ?></td>
                <td><?php echo V2H($que['options']); ?></td>
                <td><?php echo V2H($que['correct_answer']); ?></td>
                <td class="col-actions">
                    <div class="row-actions">
                        <?php if($is_admin): ?>
                        <form action="../ctrl_admin/question_update.php" method="post" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo V2H($que['id']); ?>">
                            <input type="hidden" name="section_id" value="<?php echo V2H($que['section_id']); ?>">
                            <input type="hidden" name="order_number" value="<?php echo V2H($que['order_number']); ?>">
                            <input type="hidden" name="options" value="<?php echo V2H($que['options']); ?>">
                            <input type="hidden" name="correct_answer" value="<?php echo V2H($que['correct_answer']); ?>">
                            <button type="submit" name="step" value="1" class="btn btn-small btn-edit" title="この問題の内容を編集します">✎ 編集</button>
                        </form>
                        <form action="../ctrl_admin/question_delete.php" method="post" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo V2H($que['id']); ?>">
                            <input type="hidden" name="section_id" value="<?php echo V2H($que['section_id']); ?>">
                            <input type="hidden" name="order_number" value="<?php echo V2H($que['order_number']); ?>">
                            <input type="hidden" name="options" value="<?php echo V2H($que['options']); ?>">
                            <input type="hidden" name="correct_answer" value="<?php echo V2H($que['correct_answer']); ?>">
                            <button type="submit" name="step" value="1" class="btn btn-small btn-danger" title="この問題を削除します（確認画面へ進みます）">✕ 削除</button>
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
