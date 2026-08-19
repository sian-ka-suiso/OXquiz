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
    $is_login = isset($_SESSION['is_login']) ? $_SESSION['is_login'] : "";
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "";
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : "";
    $pre_page_link = isset($_POST['pre_page_link']) ? $_POST['pre_page_link'] : "";
//**************************************************
// ログインチェック
//**************************************************
    if(!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true){
        header("location: ./"); // ログイン画面に戻す
        exit();
    }
//**************************************************
// 変更情報取得
//**************************************************
    $change_name = isset($_POST['change_name']) ? $_POST['change_name'] : "";
    $reset_pass = isset($_POST['reset_pass']) ? $_POST['reset_pass'] : "";
//**************************************************
// ユーザー名変更
//**************************************************
    if($change_name !== "") {
        ChangeUserName($user_id, $change_name);
        $change_message = "ユーザー名を更新しました。";
    }
//**************************************************
// パスワード再設定
//**************************************************
    if($reset_pass !== "") {
        ResetLoginPass($user_id, $reset_pass);
        $reset_message = "パスワードを更新しました。";
    }

//**************************************************
// 変数取得（更新後反映させるため、最後に取得）
//**************************************************
    $bookmarks = getUserBookmarks($user_id);
    foreach ($bookmarks as &$bookmark) {
        $bookmark['question_number'] = getQuestionNumberInSection((int)$bookmark['section_id'], (int)$bookmark['question_id']);
    }
    unset($bookmark);

    $userData = getUserInfo($user_id);
    if ($userData) {
        $user_name  = $userData['user_name'];
        $created_at = $userData['created_at'];
        $update_at  = $userData['update_at'];
        $is_admin   = $userData['is_admin'];
    } else {
        // ユーザーが見つからなかった場合の予備処理
        $user_name = "ゲスト";
        $created_at = 0000;
        $update_at = 0000;
        $is_admin = 0;
    }
//**************************************************
// HTMLを出力
//**************************************************
    //画面へ表示
    require_once('../view/mypage.php');
?>