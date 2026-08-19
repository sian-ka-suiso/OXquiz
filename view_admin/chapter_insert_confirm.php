<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>以下のユーザーを追加します</h2>
    <div class="tables">
        <table border="1">
            <h3>追加内容</h3>
            <tr>
                <th>チャプター名</th>
                <th>フォルダー名</th>
                <th>order</th>
                <th>公開状況</th>
            </tr>
            <tr>
                <td><?php echo htmlspecialchars($name); ?></td>
                <td><?php echo htmlspecialchars($folder_name); ?></td>
                <td><?php echo htmlspecialchars($order_number); ?></td>
                <td><?php echo htmlspecialchars($is_published); ?></td>
            </tr>
        </table>
        <form action="chapter" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <input type="hidden" name="folder_name" value="<?php echo htmlspecialchars($folder_name); ?>">
            <input type="hidden" name="order_number" value="<?php echo htmlspecialchars($order_number); ?>">
            <input type="hidden" name="is_published" value="<?php echo htmlspecialchars($is_published); ?>">
            <button type="submit">追加する</button>
            <input type="hidden" name="chapter_insert" value="true">
        </form>
    </div>
    <form action="chapter" method="post">
        <input type="hidden" name="step" value="">
        <button type="submit">戻る</button>
    </form>
</body>
</html>
