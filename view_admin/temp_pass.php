<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>以下のユーザーに対して、仮パスワードを設定しますか？</h2>
    <div>仮パスワード：<?php echo V2H($temp_pass) ?></div>
    <div>※ 仮パスワードを設定すると、元のパスワードは上書きされます</div>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>メールアドレス</th>
            <th>ユーザー名</th>
        </tr>
        <tr>
            <td><?php echo V2H($id); ?></td>
            <td><?php echo V2H($email); ?></td>
            <td><?php echo V2H($user_name); ?></td>
        </tr>
    </table>
    <form action="../ctrl_admin/temp_pass.php" method="post">
        <input type="hidden" name="id" value="<?php echo V2H($id); ?>">
        <input type="hidden" name="step" value="2">
        <input type="hidden" name="temp_pass" value="<?php echo V2H($temp_pass) ?>">
        <button type="submit">仮パスワードを設定</button>
    </form>
    <form action="../ctrl_admin/user.php" method="post">
        <button type="submit">戻る</button>
    </form>
</body>
</html>