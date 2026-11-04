<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_admin/admin.css">
    <title>よくある間違いOXクイズ　クエスチョン追加</title>
</head>
<body>
    <div class="admin-page">
        <header class="admin-header">
            <div class="admin-header-text">
                <h1 class="admin-title">クエスチョンの新規追加</h1>
                <p class="admin-subtitle">内容を入力して追加してください</p>
            </div>
        </header>

        <div class="admin-panel">
            <form id="questionInsertForm" action="../ctrl_admin/question_insert.php" method="post">
                <table class="admin-form-table">
                    <tr>
                        <th>セクションID</th>
                        <td>
                            <input type="text" name="section_id" value="<?= $section_id ?>">
                            <span class="err"><?= isset($arrErr['section_id']) ? $arrErr['section_id'] : "" ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th>順番（order）</th>
                        <td>
                            <input type="text" name="order_number" value="<?= $order_number ?>">
                            <span class="err"><?= isset($arrErr['order_number']) ? $arrErr['order_number'] : "" ?></span>
                        </td>

                    <tr>
                        <th>選択肢</th>
                        <td>
                            <input type="text" name="options" value="<?= $options ?>">
                            <span class="err"><?= isset($arrErr['options']) ? $arrErr['options'] : "" ?></span>
                        </td>
                    </tr>
                    </tr>
                    <tr>
                        <th>正解番号</th>
                        <td>
                            <input type="text" name="correct_answer" value="<?= $correct_answer ?>">
                            <span class="err"><?= isset($arrErr['correct_answer']) ? $arrErr['correct_answer'] : "" ?></span>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <input type="hidden" name="step" value="1">
            </form>
            <div class="form-actions">
                <button type="submit" form="questionInsertForm" class="btn btn-primary" title="入力内容の確認画面へ進みます">確認画面へ進む</button>
                <form action="../ctrl_admin/question.php" method="post" class="inline-form">
                    <input type="hidden" name="step" value="">
                    <button type="submit" class="btn btn-secondary" title="追加せずに一覧へ戻ります">← キャンセルして戻る</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
