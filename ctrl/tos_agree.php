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
    // ポートフォリオ用デモアカウントは利用規約への同意を求めない
    $is_demo_account = ($email === 'demo@email.com');
//**************************************************
// ログインチェック
//**************************************************
    if(!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true){
        header("location: index.php"); // ログイン画面に戻す
        exit();
    }
//**************************************************
// 同意済み／ゲスト／デモアカウントの場合はこのページ不要
//**************************************************
    if ($is_guest || $is_demo_account || hasTosAgreed((int)$user_id)) {
        header("location: chapter.php");
        exit();
    }
//**************************************************
// 同意処理
//**************************************************
    $arrErr = array();

    if (isset($_POST['submit_tos'])) {
        $tos_agree = isset($_POST['tos_agree']);
        if (!$tos_agree) {
            $arrErr['tos_agree'] = "利用規約に同意いただけない場合、ご利用できません。";
        } else {
            agreeToS((int)$user_id);
            header("location: chapter.php");
            exit();
        }
    }
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view/tos_agree.php');
?>
