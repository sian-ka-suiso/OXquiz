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
// 権限チェック（管理者のみ）
//**************************************************
    if(empty($_SESSION['admin_is_admin'])){
        header("location: explanation.php"); // チャプター一覧に戻す
        exit();
    }
//**************************************************
// 変数取得
//**************************************************
$id = isset($_SESSION['id']) ? $_SESSION['id'] : "";
$question_id = isset($_SESSION['question_id']) ? $_SESSION['question_id'] : "";

$step = isset($_POST['step']) ? $_POST['step'] : "";
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view_admin/explanation_insert_confirm.php');

?>