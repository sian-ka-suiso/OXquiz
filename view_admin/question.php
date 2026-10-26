<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　クエスチョン一覧</title>
</head>
<body>
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">📗 クエスチョン一覧</h1>
                <p class="admin-subtitle">問題（Question）の確認・追加・編集・削除ができます</p>
            </div>
            <div class="admin-header-actions">
                <form action="../ctrl_admin/admin.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-ghost" title="管理者トップページに戻ります">← 管理トップへ戻る</button>
                </form>
            </div>
        </header>

        <div class="admin-toolbar">
            <div class="toolbar-group">
                <form action="../ctrl_admin/question.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-secondary" title="最新のクエスチョン一覧を再読み込みします">
                        <span class="btn-icon">↻</span> 更新
                    </button>
                </form>
                <form action="../ctrl_admin/question_insert.php" method="post" class="inline-form">
                    <button type="submit" class="btn btn-primary" title="新しいクエスチョンを追加する画面へ進みます">
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
                <th>セクションID</th>
                <th>順番</th>
                <th>選択肢</th>
                <th>正解番号</th>
                <th>制作日</th>
                <th>更新日</th>
                <th class="col-actions">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($question_data as $que): ?>
            <tr>
                <td class="col-id"><?php echo V2H($que['id']); ?></td>
                <td><?php echo V2H($que['section_id']); ?></td>
                <td><?php echo V2H($que['order_number']); ?></td>
                <td><?php echo V2H($que['options']); ?></td>
                <td><?php echo V2H($que['correct_answer']); ?></td>
                <td><?php echo V2H($que['created_at']); ?></td>
                <td><?php echo V2H($que['updated_at']); ?></td>
                <td class="col-actions">
                    <div class="row-actions">
                        <form action="../ctrl_admin/question_update.php" method="post" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo V2H($que['id']); ?>">
                            <input type="hidden" name="section_id" value="<?php echo V2H($que['section_id']); ?>">
                            <input type="hidden" name="order_number" value="<?php echo V2H($que['order_number']); ?>">
                            <input type="hidden" name="options" value="<?php echo V2H($que['options']); ?>">
                            <input type="hidden" name="correct_answer" value="<?php echo V2H($que['correct_answer']); ?>">
                            <input type="hidden" name="created_at" value="<?php echo V2H($que['created_at']); ?>">
                            <input type="hidden" name="updated_at" value="<?php echo V2H($que['updated_at']); ?>">
                            <button type="submit" name="step" value="1" class="btn btn-small btn-edit" title="このセクションの内容を編集します">✎ 編集</button>
                        </form>
                        <form action="../ctrl_admin/question_delete.php" method="post" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo V2H($que['id']); ?>">
                            <input type="hidden" name="section_id" value="<?php echo V2H($que['section_id']); ?>">
                            <input type="hidden" name="order_number" value="<?php echo V2H($que['order_number']); ?>">
                            <input type="hidden" name="options" value="<?php echo V2H($que['options']); ?>">
                            <input type="hidden" name="correct_answer" value="<?php echo V2H($que['correct_answer']); ?>">
                            <input type="hidden" name="created_at" value="<?php echo V2H($que['created_at']); ?>">
                            <input type="hidden" name="updated_at" value="<?php echo V2H($que['updated_at']); ?>">
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
</body>
</html>
