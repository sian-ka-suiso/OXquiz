<?php
//**************************************************
// 初期処理
//**************************************************
    session_start();

    require_once('../model/dbconnect.php');

    require_once('../model/dbfunction.php');
//**************************************************
// 変数取得
//**************************************************
    $is_login = isset($_SESSION['is_login']) ? $_SESSION['is_login'] : "";
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "";
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : "";
    $is_guest = !empty($_SESSION['guest']);
    $pre_page_link = isset($_POST['pre_page_link']) ? $_POST['pre_page_link'] : "";
    // ポートフォリオ用デモアカウント（全訪問者で共有するため、名前・パスワードの変更や利用規約同意を求めない）
    $is_demo_account = ($email === 'demo@email.com');
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
    if(!$is_guest && !$is_demo_account && !hasTosAgreed((int)$user_id)){
        header("location: tos_agree.php");
        exit();
    }
//**************************************************
// 変更情報取得
//**************************************************
    $change_name = isset($_POST['change_name']) ? $_POST['change_name'] : "";
    $reset_pass = isset($_POST['reset_pass']) ? $_POST['reset_pass'] : "";
    $current_pass = isset($_POST['current_pass']) ? $_POST['current_pass'] : "";
    $arrErr = array();
//**************************************************
// ユーザー名変更
//**************************************************
    if($change_name !== "") {
        if ($is_demo_account) {
            $change_message = "デモアカウントのため、ユーザー名は変更できません。";
        } else {
            ChangeUserName($user_id, $change_name);
            $change_message = "ユーザー名を更新しました。";
        }
    }
//**************************************************
// パスワード再設定
//**************************************************
    // 再設定ボタンが押されたとき（新しいパスワード欄が送られてきたとき）だけ処理する
    if(isset($_POST['reset_pass'])) {
        if ($is_demo_account) {
            $reset_message = "デモアカウントのため、パスワードは変更できません。";
        } else {
            // 現在のパスワードの確認（本人以外による変更を防ぐ）
            if($current_pass == ""){
                $arrErr['current_pass'] = "現在のパスワードを入力してください";
            } else {
                // 照合に失敗すると false が返るため、(int) 比較の前に成功したかを確認する
                $checked_id = loginCheck($email, $current_pass);
                if(!$checked_id || (int)$checked_id !== (int)$user_id){
                    $arrErr['current_pass'] = "現在のパスワードが正しくありません";
                }
            }

            // 新しいパスワードの確認（ログイン・新規登録と同じ条件）
            if($reset_pass == ""){
                $arrErr['reset_pass'] = "新しいパスワードを入力してください";
            } elseif(mb_strlen($reset_pass,"UTF-8") < 6 || mb_strlen($reset_pass,"UTF-8") > 20){
                $arrErr['reset_pass'] = "パスワードは6〜20文字で入力してください";
            }

            if(empty($arrErr)){
                ResetLoginPass($user_id, $reset_pass);
                $reset_message = "パスワードを更新しました。";
            }
        }
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
        $class_id   = $userData['class_id'];
        $tos_agreed_at = $userData['tos_agreed_at'];
    } else {
        // ユーザーが見つからなかった場合の予備処理
        $user_name = "ゲスト";
        $created_at = 0000;
        $update_at = 0000;
        $is_admin = 0;
        $class_id = 0;
        $tos_agreed_at = null;
    }
    //所属クラス名の取得
    $class_name = (empty($class_id)) ? "なし" : getClassName((int)$class_id);
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view/mypage.php');
?>