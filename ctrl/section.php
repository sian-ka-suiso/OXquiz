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
    
    // チャプター番号
    $chapter_id = isset($_GET['chapter_id']) ? $_GET['chapter_id'] : "";
    // セクションid、セクション名、クエスチョンid配列
    $sections = getSectionsWithQuestions($chapter_id);

    if (!$is_guest){
        // done/wrongのラベル付け
        $all_question_ids = [];
        foreach ($sections as $section) {
            foreach ($section['question_ids'] as $qid) {
                $all_question_ids[] = $qid;
            }
        }
        $question_statuses = getQuestionStatuses($user_id, $all_question_ids);
        
        //ユーザー情報（ユーザー名、登録日、更新日、管理者フラグ）取得
        $userData = getUserInfo($user_id);
        if ($userData) {
            $user_name  = $userData['user_name'];
            $created_at = $userData['created_at'];
            $update_at  = $userData['update_at'];
            $is_admin   = $userData['is_admin'];
        } else {
            // ユーザーが見つからなかった場合の予備処理
            $user_name = "ユーザーネーム";
            $is_admin = 0;
        }
    }
//**************************************************
// ログインチェック処理
//**************************************************
    if(!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true){
        header("location: index.php"); // ログイン画面に戻す
        exit();
    }
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view/section.html');
?>