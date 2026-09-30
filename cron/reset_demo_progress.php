<?php
// =============================================
// デモアカウント（user_id=59）進捗リセット - cron実行用
// =============================================
// ロリポップのcron設定で、このファイルのサーバー上の絶対パスを
// 「実行ファイル」として登録する（例: /home/ユーザー名/ドメイン/cron/reset_demo_progress.php）。
//
// 実際のリセット内容は sql/reset_demo_progress.sql に記述されている。
// このファイルはそれを読み込んでDBに流すだけの薄いラッパー。
// SQLの内容を変更したい場合は sql/reset_demo_progress.sql を編集する。

require_once __DIR__ . '/../model/db_config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = file_get_contents(__DIR__ . '/../sql/reset_demo_progress.sql');
    $sql = preg_replace('/--.*$/m', '', $sql); // コメント行を除去

    foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
        $pdo->exec($statement);
    }

    echo '[' . date('Y-m-d H:i:s') . "] デモアカウントの進捗をリセットしました。\n";
} catch (Throwable $e) {
    error_log('[reset_demo_progress] ' . $e->getMessage());
    echo '[' . date('Y-m-d H:i:s') . '] リセットに失敗しました: ' . $e->getMessage() . "\n";
    exit(1);
}
