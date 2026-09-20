<?php
//**************************************************
// 初期処理
//**************************************************
    session_start();

    require_once('../model/dbconnect.php');

    require_once('../model/dbfunction_admin.php');
//**************************************************
// ログインチェック
//**************************************************
    if(!isset($_SESSION['admin_is_login']) || $_SESSION['admin_is_login'] !== true){
        header("location: adm_login.php"); // 管理者ログイン画面に戻す
        exit();
    }
//**************************************************
// 変数取得
//**************************************************
    // 管理者としてログイン中か、教員としてログイン中か（今後、権限ごとにUIを出し分ける想定）
    $admin_role = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : "";
    $chapterSectionQuestions = getAll();
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view_admin/admin.php');

?>