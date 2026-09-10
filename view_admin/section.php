<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>よくある間違いOXクイズ　チャプター一覧</title>
</head>
<body>
    <h2>ユーザーDB</h2>
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
            <th>Order Number</th>
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
            <form action="../ctrl_admin/setction_update.php" method="post">
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
</body>
</html>