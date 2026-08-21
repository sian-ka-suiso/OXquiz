<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>よくある間違いOXクイズ　ユーザー削除</title>
</head>
<body>
    <h2>以下のユーザーを削除しますか？</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>メールアドレス</th>
            <th>ユーザー名</th>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($id); ?></td>
            <td><?php echo htmlspecialchars($email); ?></td>
            <td><?php echo htmlspecialchars($user_name); ?></td>
        </tr>
    </table>
    <form action="../ctrl_admin/user_delete.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <input type="hidden" name="step" value="2">
        <button type="submit">削除</button>
    </form>
    <form action="../ctrl_admin/user.php" method="post">
        <input type="hidden" name="step" value="">
        <button type="submit">戻る</button>
    </form>
</body>
</html>