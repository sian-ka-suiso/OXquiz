<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>以下のユーザーを編集します</h2>
    <div class="tables">
        <table border="1">
            <h3>変更前</h3>
            <tr>
                <th>ID</th>
                <th>メールアドレス</th>
                <th>ユーザー名</th>
                <th>管理者フラグ</th>
            </tr>
            <tr>
                <td><?php echo htmlspecialchars($id); ?></td>
                <td><?php echo htmlspecialchars($email); ?></td>
                <td><?php echo htmlspecialchars($user_name); ?></td>
                <td><?php echo htmlspecialchars($is_admin); ?></td>
            </tr>
        </table>
        <form action="user_update" method="post">
            <table border="1">
                <h3>変更後</h3>
                        <tr>
                    <th>ID</th>
                    <th>メールアドレス</th>
                    <th>ユーザー名</th>
                    <th>管理者フラグ</th>
                </tr>
                <tr>
                    <td><?php echo htmlspecialchars($id); ?></td>
                    <td><?php echo htmlspecialchars($email); ?></td>
                    <td><?php echo htmlspecialchars($user_name); ?></td>
                    <td>
                        <select name="is_admin" id="">
                            <option value="0">０</option>
                            <option value="1">１</option>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="step" value="2">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <button type="submit">変更する</button>
        </form>
    </div>
    <form action="user" method="post">
        <input type="hidden" name="step" value="">
        <button type="submit">戻る</button>
    </form>
</body>
</html>