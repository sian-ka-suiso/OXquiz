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
// 利用規約チェック
//**************************************************
    if(!$is_guest && !hasTosAgreed((int)$user_id)){
        header("location: tos_agree.php");
        exit();
    }
//**************************************************
// 変数取得（続き）
//**************************************************
    //ユーザー情報（ユーザー名、登録日、更新日、管理者フラグ）取得
    if (!$is_guest){
        $userData = getUserInfo($user_id);
        if ($userData) {
            $user_name  = $userData['user_name'];
            $created_at = $userData['created_at'];
            $update_at  = $userData['update_at'];
            $is_admin   = $userData['is_admin'];
            $is_teacher = $userData['is_teacher'];
        } else {
            // ユーザーが見つからなかった場合の予備処理
            $user_name = "ログインユーザー";
            $is_admin = 0;
            $is_teacher = 0;
        }
    } else {
        $user_name  = "ゲストユーザー";
        $created_at = 0;
        $update_at  = 0;
        $is_admin   = 0;
        $is_teacher = 0;
    }

    //チャプター名をセクション名も合わせて取得
    $chapters = getChaptersWithSections();
    $section_categories = getSectionCategories();
    $chapter_progress = (!$is_guest) ? getChapterProgressList($user_id) : [];
    $section_progress = (!$is_guest) ? getSectionProgressList($user_id) : [];

//**************************************************
// HTMLを出力
//**************************************************
    //画面へ表示
    require_once('../view/chapter.php');
?>