<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>よくある間違いOXクイズ　チャプター削除</title>
</head>
<body>
    <h2>以下のチャプターを削除しますか？</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>チャプター名</th>
            <th>フォルダー名</th>
            <th>order</th>
            <th>公開状況</th>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($id); ?></td>
            <td><?php echo htmlspecialchars($name); ?></td>
            <td><?php echo htmlspecialchars($folder_name); ?></td>
            <td><?php echo htmlspecialchars($order_number); ?></td>
            <td><?php echo htmlspecialchars($is_published); ?></td>
        </tr>
    </table>
    <form action="../ctrl_admin/chapter_delete.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <input type="hidden" name="step" value="2">
        <button type="submit">削除</button>
    </form>
    <form action="../ctrl_admin/chapter.php" method="post">
        <input type="hidden" name="step" value="">
        <button type="submit">戻る</button>
    </form>
</body>
</html>