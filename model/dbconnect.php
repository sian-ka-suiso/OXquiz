<?php
//**************************************************
// アプリ設定
//**************************************************
// 開発時は true（詳細なエラーを画面に表示）、本番運用時は false にする
define('APP_DEBUG', true);

//**************************************************
// DBエラー発生時の共通処理
//**************************************************
// 詳細は必ずサーバー側のログに記録する。
// 画面表示は APP_DEBUG が true のときだけ詳細を出し、
// false のときは一般ユーザー向けの文言のみを表示する。
function dbError(string $context, Throwable $e): never {
    error_log('[' . $context . '] ' . $e->getMessage());

    if (APP_DEBUG) {
        die('実行エラー（' . $context . '）：' . $e->getMessage() . '<br />');
    }

    die(
        '<div style="max-width:480px;margin:80px auto;padding:24px 28px;' .
        'font-family:\'Noto Sans JP\',sans-serif;font-size:14px;line-height:1.8;' .
        'color:#3a3a3a;background:#fdecea;border:1px solid #f5b8b8;border-radius:8px;">' .
        '現在データベースへのアクセスが正常に行われておりません。<br>' .
        'しばらく経ってから再度お試しいただくか、管理者（○○）までお問い合わせください。' .
        '</div>'
    );
}

//データベース接続関数
function db_connect()
{
    // データベース接続情報（環境ごとの値は model/db_config.php に定義）
    require_once __DIR__ . '/db_config.php';

    //DSNの作成
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";

    try {
        //データベースに接続
        $pdo = new PDO($dsn, DB_USER, DB_PASS);

        //エラーが発生したら例外を投げる設定
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //プリペアドステートメントを使えるようにする設定
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        //print "接続しました<br />";

    } catch (PDOException $Exception) {

        //例外が発生したら接続エラーを出力
        dbError(__FUNCTION__, $Exception);
    }
    return $pdo;
}
