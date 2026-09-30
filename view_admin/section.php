<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>よくある間違いOXクイズ　セクション一覧</title>
</head>
<body>
    <h2>セクションDB</h2>
    <form action="../ctrl_admin/section.php" method="post">
        <button type="submit">更新</button>
    </form>
    <form action="../ctrl_admin/section_insert.php" method="post">
        <button type="submit">追加</button>
    </form>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Chapter ID</th>
            <th>名前</th>
            <th>フォルダー名</th>
            <th>表示順</th>
            <th>公開状況</th>
            <th>編集</th>
            <th>削除</th>
        </tr>
    <?php foreach($section_data as $sec): ?>
        <tr>            
            <td><?php echo V2H($sec['id']); ?></td>
            <td><?php echo V2H($sec['chapter_id']); ?></td>
            <td><?php echo V2H($sec['name']); ?></td>
            <td><?php echo V2H($sec['folder_name']); ?></td>
            <td><?php echo V2H($sec['order_number']); ?></td>
            <td><?php echo V2H($sec['is_published']); ?></td>
            <form action="../ctrl_admin/section_update.php" method="post">
                <td><button type="submit" name="step" value="1">編集</button></td>
                <input type="hidden" name="id" value="<?php echo V2H($sec['id']); ?>">
                <input type="hidden" name="chapter_id" value="<?php echo V2H($sec['chapter_id']); ?>">
                <input type="hidden" name="name" value="<?php echo V2H($sec['name']); ?>">
                <input type="hidden" name="folder_name" value="<?php echo V2H($sec['folder_name']); ?>">
                <input type="hidden" name="order_number" value="<?php echo V2H($sec['order_number']); ?>">
                <input type="hidden" name="is_published" value="<?php echo V2H($sec['is_published']); ?>">
            </form>
            <form action="../ctrl_admin/section_delete.php" method="post">
                <td><button type="submit" name="step" value="1">削除</button></td>
                <input type="hidden" name="id" value="<?php echo V2H($sec['id']); ?>">
                <input type="hidden" name="chapter_id" value="<?php echo V2H($sec['chapter_id']); ?>">
                <input type="hidden" name="name" value="<?php echo V2H($sec['name']); ?>">
                <input type="hidden" name="folder_name" value="<?php echo V2H($sec['folder_name']); ?>">
                <input type="hidden" name="order_number" value="<?php echo V2H($sec['order_number']); ?>">
                <input type="hidden" name="is_published" value="<?php echo V2H($sec['is_published']); ?>">
            </form>
        </tr>
    <?php endforeach; ?>
    </table>
    <form action="../ctrl_admin/admin.php" method="post">
        <button type="submit">戻る</button>
    </form>
=======
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　セクション一覧</title>
</head>
<body>
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">📗 セクション一覧</h1>
                <p class="admin-subtitle">節（Section）の確認・追加・編集・削除ができます</p>
            </div>
            <div class="admin-header-actions">
                <form action="../ctrl_admin/admin.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-ghost" title="管理者トップページに戻ります">← 管理トップへ戻る</button>
                </form>
            </div>
        </header>

        <div class="admin-toolbar">
            <div class="toolbar-group">
                <form action="../ctrl_admin/section.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-secondary" title="最新のセクション一覧を再読み込みします">
                        <span class="btn-icon">↻</span> 更新
                    </button>
                </form>
                <form action="../ctrl_admin/section_insert.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-primary" title="新しいセクションを追加する画面へ進みます">
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
                <th>チャプターID</th>
                <th>名前</th>
                <th>フォルダー名</th>
                <th>順番</th>
                <th>公開状況</th>
                <th class="col-actions">操作</th>
            </tr>
            </thead>
            <tbody>
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
                        <form action="../ctrl_admin/setction_update.php" method="post" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo V2H($sec['id']); ?>">
                            <input type="hidden" name="chapter_id" value="<?php echo V2H($sec['chapter_id']); ?>">
                            <input type="hidden" name="name" value="<?php echo V2H($sec['name']); ?>">
                            <input type="hidden" name="folder_name" value="<?php echo V2H($sec['folder_name']); ?>">
                            <input type="hidden" name="order_number" value="<?php echo V2H($sec['order_number']); ?>">
                            <input type="hidden" name="is_published" value="<?php echo V2H($sec['is_published']); ?>">
                            <button type="submit" name="step" value="1" class="btn btn-small btn-edit" title="このセクションの内容を編集します">✎ 編集</button>
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
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
>>>>>>> origin/main
</body>
</html>
