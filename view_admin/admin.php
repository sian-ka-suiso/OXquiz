<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <title>よくある間違いOXクイズ　管理者ページ</title>
</head>
<body>
    <h2>データベース 一覧</h2>
    <ul>
        <li><a href="user">ユーザー</a></li>
        <li><a href="chapter">章（Chapter）</a></li>
        <li><a href="">節（Section）</a></li>
        <li><a href="">問題・解説</a></li>
        <li><a href="">数学ナビ</a></li>
    </ul>
    <form action="login" method="post">
        <input type="hidden" name="sign_out" value="true">
        <button type="submit">ログアウト</button>
    </form>
</body>
</html>