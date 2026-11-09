<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　Excel出力</title>
</head>
<body>
    <div class="admin-page admin-page--wide">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">📊 Excel出力</h1>
                <?php require('../view_admin/parts/header_role.php'); ?>
            </div>
            <div class="admin-header-actions">
                <form action="../ctrl_admin/admin.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-ghost" title="管理者トップページに戻ります">← 管理トップへ戻る</button>
                </form>
            </div>
        </header>

        <p class="admin-description">出力するクラスを選び、内容を確認してから出力してください</p>

        <?php if (empty($classes)): ?>
        <div class="notice notice-info">
            <span class="notice-icon">ℹ</span>
            <span>担当しているクラスがありません。</span>
        </div>
        <?php else: ?>
        <div class="admin-toolbar">
            <!-- クラス選択（選択中のクラスがURLに残るようGETで送る） -->
            <form action="../ctrl_admin/export.php" method="get" class="toolbar-group search-form">
                <label>クラス
                    <select name="class_id">
                        <option value="">選択してください</option>
                        <?php foreach ($classes as $class): ?>
                        <option value="<?= V2H($class['id']) ?>" <?= (string)$class_id === (string)$class['id'] ? 'selected' : '' ?>><?= V2H($class['id']) ?>：<?= V2H($class['class_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button type="submit" class="btn btn-primary" title="選択したクラスの出力内容を表示します">表示</button>
            </form>
        </div>
        <?php if (isset($arrErr['class_id'])): ?>
        <p class="err search-err"><?= V2H($arrErr['class_id']) ?></p>
        <?php endif; ?>
        <?php endif; ?>

        <?php if ($selected_class !== null): ?>
        <div class="admin-panel">
            <p class="panel-heading">出力するクラス</p>
            <table class="admin-form-table">
                <tr><th>クラスID</th><td class="field-value"><?= V2H($selected_class['id']) ?></td></tr>
                <tr><th>クラス名</th><td class="field-value"><?= V2H($selected_class['class_name']) ?></td></tr>
                <tr><th>年度</th><td class="field-value"><?= V2H($selected_class['year']) ?></td></tr>
                <tr><th>学期</th><td class="field-value"><?= V2H($selected_class['semester']) ?></td></tr>
                <tr><th>曜日・時限</th><td class="field-value"><?= V2H($selected_class['date']) ?></td></tr>
                <tr><th>担当教員ID</th><td class="field-value"><?= V2H($selected_class['teacher_id']) ?></td></tr>
                <tr><th>所属人数</th><td class="field-value"><?= count($class_users) ?>人</td></tr>
            </table>

            <p class="panel-heading">出力対象のアカウント</p>
            <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>メールアドレス</th>
                    <th>アカウント名</th>
                    <th>登録日</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($class_users)): ?>
                <tr class="empty-row"><td colspan="4">このクラスに所属するアカウントはありません</td></tr>
                <?php endif; ?>
                <?php foreach ($class_users as $class_user): ?>
                <tr>
                    <td class="col-id"><?= V2H($class_user['id']) ?></td>
                    <td><?= V2H($class_user['email']) ?></td>
                    <td><?= V2H((string)$class_user['user_name']) ?></td>
                    <td><?= V2H($class_user['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>

            <div class="form-actions">
                <form action="../ctrl_admin/export.php" method="post" class="inline-form">
                    <input type="hidden" name="class_id" value="<?= V2H($selected_class['id']) ?>">
                    <input type="hidden" name="export" value="1">
                    <button type="submit" class="btn btn-primary" title="このクラスのデータをExcelファイルで出力します">📥 Excelで出力</button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
