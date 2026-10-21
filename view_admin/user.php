<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　アカウント一覧</title>
</head>
<body>
    <div class="admin-page admin-page--wide">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">👤 アカウント一覧</h1>
                <?php require('../view_admin/parts/header_role.php'); ?>
            </div>
            <div class="admin-header-actions">
                <form action="../ctrl_admin/admin.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-ghost" title="管理者トップページに戻ります">← 管理トップへ戻る</button>
                </form>
            </div>
        </header>

        <p class="admin-description">アカウント情報の確認・編集・削除・仮パスワード発行ができます</p>

        <div class="admin-toolbar">
            <div class="toolbar-group">
                <form action="../ctrl_admin/user.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-secondary" title="最新のアカウント一覧を再読み込みします">
                        <span class="btn-icon">↻</span> 更新
                    </button>
                </form>
            </div>
            <!-- 検索・並び替え（条件がURLに残るようGETで送る） -->
            <form action="../ctrl_admin/user.php" method="get" class="toolbar-group search-form">
                <label>ID
                    <input type="text" name="search_id" value="<?= V2H($search_id) ?>" inputmode="numeric" placeholder="例：12" class="search-id">
                </label>
                <label>権限
                    <select name="role">
                        <?php foreach ($roleOptions as $value => $label): ?>
                        <option value="<?= V2H($value) ?>" <?= $search_role === (string)$value ? 'selected' : '' ?>><?= V2H($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>並び順
                    <select name="sort">
                        <?php foreach ($sortOptions as $value => $label): ?>
                        <option value="<?= V2H($value) ?>" <?= $sort === $value ? 'selected' : '' ?>><?= V2H($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button type="submit" class="btn btn-primary" title="条件に合うアカウントを表示します">検索</button>
                <a href="../ctrl_admin/user.php" class="btn btn-ghost" title="検索条件をクリアして全件表示に戻します">クリア</a>
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
                <th>メールアドレス</th>
                <!-- <th>パスワード</th> -->
                <th>アカウント名</th>
                <th>登録日</th>
                <th>更新日</th>
                <th>権限</th>
                <th>クラスID</th>
                <th class="col-actions">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($users)): ?>
            <tr class="empty-row"><td colspan="8">条件に合うアカウントはありません</td></tr>
            <?php endif; ?>
            <?php foreach($users as $user): ?>
            <tr>
                <td class="col-id"><?php echo V2H($user['id']); ?></td>
                <td><?php echo V2H($user['email']); ?></td>
                <!-- <td>**********</td> -->
                <td><?php echo V2H($user['user_name']); ?></td>
                <td><?php echo V2H($user['created_at']); ?></td>
                <td><?php echo V2H($user['update_at']); ?></td>
                <td>
                    <?php foreach($user['roles'] as $role): ?>
                    <span class="badge <?= V2H($role['class']) ?>"><?php echo V2H($role['label']) ?></span>
                    <?php endforeach; ?>
                </td>
                <td><?php echo V2H($user['class_id']); ?></td>
                <td class="col-actions">
                    <div class="row-actions">
                        <?php if($is_admin): ?>
                        <form action="../ctrl_admin/user_update.php" method="post" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo V2H($user['id']); ?>">
                            <input type="hidden" name="email" value="<?php echo V2H($user['email']); ?>">
                            <input type="hidden" name="user_name" value="<?php echo V2H($user['user_name']); ?>">
                            <input type="hidden" name="is_admin" value="<?php echo V2H($user['is_admin']); ?>">
                            <button type="submit" name="step" value="1" class="btn btn-small btn-edit" title="このアカウントの情報を編集します">✏️ 編集</button>
                        </form>
                        <form action="../ctrl_admin/user_delete.php" method="post" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo V2H($user['id']); ?>">
                            <input type="hidden" name="email" value="<?php echo V2H($user['email']); ?>">
                            <input type="hidden" name="user_name" value="<?php echo V2H($user['user_name']); ?>">
                            <button type="submit" name="step" value="1" class="btn btn-small btn-danger" title="このアカウントを削除します（確認画面へ進みます）">✕ 削除</button>
                        </form>
                        <?php endif; ?>
                        <form action="../ctrl_admin/temp_pass.php" method="post" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo V2H($user['id']); ?>">
                            <input type="hidden" name="email" value="<?php echo V2H($user['email']); ?>">
                            <input type="hidden" name="user_name" value="<?php echo V2H($user['user_name']); ?>">
                            <button type="submit" name="step" value="1" class="btn btn-small btn-warning" title="このアカウントの仮パスワードを発行します（元のパスワードは上書きされます）">🔑 仮パスワード発行</button>
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
