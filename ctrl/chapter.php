<?php
//**************************************************
// 初期処理
//**************************************************
    //SESSIONスタート
    session_start();

    //データベース接続関数の定義ファイルを読み込み
    require_once('../model/dbconnect.php');

    //データベース操作関数の定義ファイルを読み込み
    require_once('../model/dbfunction.php');
//**************************************************
// 変数取得
//**************************************************
    //ログインチェックフラグ
    $is_login = isset($_SESSION['is_login']) ? $_SESSION['is_login'] : "";
    //Id
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "";
    //メールアドレス
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : "";

    //ユーザー情報（ユーザー名、登録日、更新日、管理者フラグ）取得
    $userData = getUserInfo($user_id);
    if ($userData) {
        $user_name  = $userData['user_name'];
        $created_at = $userData['created_at'];
        $update_at  = $userData['update_at'];
        $is_admin   = $userData['is_admin'];
    } else {
        // ユーザーが見つからなかった場合の予備処理
        $user_name = "ログインユーザー";
        $is_admin = 0;
    }

    //チャプター名をセクション名も合わせて取得
    $chapters = getChaptersWithSections();
    //ゲスト
    $is_guest = !empty($_SESSION['guest']);
//**************************************************
// ログインチェック
//**************************************************
    if(!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true){
        header("location: index.php"); // ログイン画面に戻す
        exit();
    }
//**************************************************
// HTMLを出力
//**************************************************
    //画面へ表示
    require_once('../view/chapter.html');
?>