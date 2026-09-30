<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　ユーザー一覧</title>
</head>
<body>
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">👤 ユーザー一覧</h1>
                <p class="admin-subtitle">ユーザー情報の確認・編集・削除・仮パスワード発行ができます</p>
            </div>
            <div class="admin-header-actions">
                <form action="../ctrl_admin/admin.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-ghost" title="管理者トップページに戻ります">← 管理トップへ戻る</button>
                </form>
            </div>
        </header>

        <div class="admin-toolbar">
            <div class="toolbar-group">
                <form action="../ctrl_admin/user.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-secondary" title="最新のユーザー一覧を再読み込みします">
                        <span class="btn-icon">↻</span> 更新
                    </button>
                </form>
            </div>
        </div>

        <div class="table-wrapper">
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>メールアドレス</th>
                <th>パスワード</th>
                <th>ユーザー名</th>
                <th>登録日</th>
                <th>更新日</th>
                <th>権限</th>
                <th class="col-actions">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($users as $user): ?>
            <tr>
                <td class="col-id"><?php echo V2H($user['id']); ?></td>
                <td><?php echo V2H($user['email']); ?></td>
                <td>**********</td>
                <td><?php echo V2H($user['user_name']); ?></td>
                <td><?php echo V2H($user['created_at']); ?></td>
                <td><?php echo V2H($user['update_at']); ?></td>
                <td>
                    <?php if ($user['is_admin'] == 1): ?>
                        <span class="badge badge-admin">管理者</span>
                    <?php else: ?>
                        <span class="badge badge-user">一般</span>
                    <?php endif; ?>
                </td>
                <td class="col-actions">
                    <div class="row-actions">
                        <form action="../ctrl_admin/user_update.php" method="post" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo V2H($user['id']); ?>">
                            <input type="hidden" name="email" value="<?php echo V2H($user['email']); ?>">
                            <input type="hidden" name="user_name" value="<?php echo V2H($user['user_name']); ?>">
                            <input type="hidden" name="is_admin" value="<?php echo V2H($user['is_admin']); ?>">
                            <button type="submit" name="step" value="1" class="btn btn-small btn-edit" title="このユーザーの情報を編集します">✏️ 編集</button>
                        </form>
                        <form action="../ctrl_admin/user_delete.php" method="post" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo V2H($user['id']); ?>">
                            <input type="hidden" name="email" value="<?php echo V2H($user['email']); ?>">
                            <input type="hidden" name="user_name" value="<?php echo V2H($user['user_name']); ?>">
                            <button type="submit" name="step" value="1" class="btn btn-small btn-danger" title="このユーザーを削除します（確認画面へ進みます）">✕ 削除</button>
                        </form>
                        <form action="../ctrl_admin/temp_pass.php" method="post" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo V2H($user['id']); ?>">
                            <input type="hidden" name="email" value="<?php echo V2H($user['email']); ?>">
                            <input type="hidden" name="user_name" value="<?php echo V2H($user['user_name']); ?>">
                            <button type="submit" name="step" value="1" class="btn btn-small btn-warning" title="このユーザーの仮パスワードを発行します（元のパスワードは上書きされます）">🔑 仮パスワード発行</button>
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
