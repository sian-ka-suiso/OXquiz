<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>以下のチャプターを追加します</h2>
    <div class="tables">

        <form action="chapter_insert" method="post">
            <table border="1">
                <h3>追加内容</h3>
                <tr>
                    <th>チャプター名</th>
                    <th>フォルダー名</th>
                    <th>order</th>
                    <th>公開状況</th>
                </tr>
                <tr>
                    <td>
                        <input type="text" name="name" value="<?= $name ?>">
                        <div class="err">
                            <?= isset($arrErr['name']) ? $arrErr['name'] : "" ?>
                        </div>
                    </td>
                    <td>
                        <input type="text" name="folder_name" value="<?= $folder_name ?>">
                        <div class="err">
                            <?= isset($arrErr['folder_name']) ? $arrErr['folder_name'] : "" ?>
                        </div>
                    </td>
                    <td>
                        <input type="text" name="order_number" value="<?= $order_number ?>">
                        <div class="err">
                            <?= isset($arrErr['order_number']) ? $arrErr['order_number'] : "" ?>
                        </div>
                    </td>
                    <td>
                        <select name="is_published" id="">
                            <option value="0" <?= $is_published == 0 ? 'selected' : '' ?>>０</option>
                            <option value="1" <?= $is_published == 1 ? 'selected' : '' ?>>１</option>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <button type="submit">追加する</button>
            <input type="hidden" name="step" value="1">
        </form>
    </div>
    <form action="chapter" method="post">
        <input type="hidden" name="step" value="">
        <button type="submit">戻る</button>
    </form>
</body>
</html>