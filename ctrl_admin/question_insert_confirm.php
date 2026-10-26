<?php
//**************************************************
// 初期処理
//**************************************************
    session_start();

    echo '<pre>';
    var_dump($_SESSION);
    echo '</pre>';

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
    $id = isset($_SESSION['id']) ? $_SESSION['id'] : "";
    $section_id = isset($_SESSION['section_id']) ? $_SESSION['section_id'] : "";
    $order_number = isset($_SESSION['order_number']) ? $_SESSION['order_number'] : "";
    $options = isset($_SESSION['options']) ? $_SESSION['options'] : "";
    $correct_answer = isset($_SESSION['correct_answer']) ? $_SESSION['correct_answer'] : "";
    $created_at = isset($_SESSION['created_at']) ? $_SESSION['created_at'] : "";
    $updated_at = isset($_SESSION['updated_at']) ? $_SESSION['updated_at'] : "";

    $step = isset($_POST['step']) ? $_POST['step'] : "";
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view_admin/question_insert_confirm.php');

?>