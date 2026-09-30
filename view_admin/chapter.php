<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　チャプター一覧</title>
</head>
<body>
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">📘 チャプター一覧</h1>
                <p class="admin-subtitle">章（Chapter）の確認・追加・編集・削除ができます</p>
            </div>
            <div class="admin-header-actions">
                <form action="../ctrl_admin/admin.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-ghost" title="管理者トップページに戻ります">← 管理トップへ戻る</button>
                </form>
            </div>
        </header>

        <div class="admin-toolbar">
            <div class="toolbar-group">
                <form action="../ctrl_admin/chapter.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-secondary" title="最新のチャプター一覧を再読み込みします">
                        <span class="btn-icon">↻</span> 更新
                    </button>
                </form>
                <form action="../ctrl_admin/chapter_insert.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-primary" title="新しいチャプターを追加する画面へ進みます">
                        <span class="btn-icon">＋</span> 新規追加
                    </button>
                </form>
            </div>
        </div>

        <div class="table-wrapper">
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>チャプター名</th>
                <th>フォルダー名</th>
                <th>表示順</th>
                <th>公開状況</th>
                <th class="col-actions">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($chapter_data as $chap): ?>
            <tr>
                <td class="col-id"><?php echo V2H($chap['id']); ?></td>
                <td><?php echo V2H($chap['name']); ?></td>
                <td><?php echo V2H($chap['folder_name']); ?></td>
                <td><?php echo V2H($chap['order_number']); ?></td>
                <td>
                    <?php if ($chap['is_published'] == 1): ?>
                        <span class="badge badge-published">公開中</span>
                    <?php else: ?>
                        <span class="badge badge-unpublished">非公開</span>
                    <?php endif; ?>
                </td>
                <td class="col-actions">
                    <div class="row-actions">
                        <form action="../ctrl_admin/chapter_update.php" method="post" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo V2H($chap['id']); ?>">
                            <input type="hidden" name="name" value="<?php echo V2H($chap['name']); ?>">
                            <input type="hidden" name="folder_name" value="<?php echo V2H($chap['folder_name']); ?>">
                            <input type="hidden" name="order_number" value="<?php echo V2H($chap['order_number']); ?>">
                            <input type="hidden" name="is_published" value="<?php echo V2H($chap['is_published']); ?>">
                            <button type="submit" name="step" value="1" class="btn btn-small btn-edit" title="このチャプターの内容を編集します">✏️ 編集</button>
                        </form>
                        <form action="../ctrl_admin/chapter_delete.php" method="post" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo V2H($chap['id']); ?>">
                            <input type="hidden" name="name" value="<?php echo V2H($chap['name']); ?>">
                            <input type="hidden" name="folder_name" value="<?php echo V2H($chap['folder_name']); ?>">
                            <input type="hidden" name="order_number" value="<?php echo V2H($chap['order_number']); ?>">
                            <input type="hidden" name="is_published" value="<?php echo V2H($chap['is_published']); ?>">
                            <button type="submit" name="step" value="1" class="btn btn-small btn-danger" title="このチャプターを削除します（確認画面へ進みます）">✕ 削除</button>
                        </form>
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
