<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>以下のセクションを編集します</h2>
    <div class="tables">
        <table border="1">
            <h3>変更前</h3>
            <tr>
                <th>ID</th>
                <th>セクションID</th>
                <th>表示順</th>
                <th>選択肢</th>
                <th>正解番号</th>
            </tr>
            <tr>
                <td><?php echo htmlspecialchars($id); ?></td>
                <td><?php echo htmlspecialchars($section_id); ?></td>
                <td><?php echo htmlspecialchars($order_number); ?></td>
                <td><?php echo htmlspecialchars($options); ?></td>
                <td><?php echo htmlspecialchars($correct_answer); ?></td>
            </tr>
        </table>
        <form action="../ctrl_admin/question_update.php" method="post">
            <table border="1">
                <h3>変更後</h3>
                <tr>
                    <th>ID</th>
                    <th>セクションID</th>
                    <th>表示順</th>
                    <th>選択肢</th>
                    <th>正解番号</th>
                </tr>
                <tr>
                    <td><?php echo htmlspecialchars($id); ?></td>
                    <td>
                        <input type="text" name="section_id" value="<?= $section_id ?>">
                    </td>
                    <td>
                        <input type="text" name="order_number" value="<?= $order_number?>">
                    </td>
                    <td>
                        <input type="text" name="options" value="<?= $options ?>">
                    </td>
                    <td>
                        <input type="text" name="correct_answer" value="<?= $correct_answer ?>">
                    </td>
                </tr>
            </table>
            <input type="hidden" name="step" value="2">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <button type="submit">変更する</button>
        </form>
    </div>
    <form action="../ctrl_admin/question.php" method="post">
        <input type="hidden" name="step" value="">
        <button type="submit">戻る</button>
    </form>
</body>
</html>