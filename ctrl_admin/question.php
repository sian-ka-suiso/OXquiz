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
    $question_data = getQuestionAll();
    $question_insert = isset($_POST['question_insert']) ? $_POST['question_insert'] : "";
//**************************************************
// 挿入処理
//**************************************************
    if($question_insert){
        $insert_check = insertQuestion($_SESSION['section_id'], $_SESSION['order_number'], $_SESSION['options'],$_SESSION['correct_answer']);
        if($insert_check){
            unset($_SESSION['id']);
            unset($_SESSION['section_id']);
            unset($_SESSION['order_number']);
            unset($_SESSION['options']);
            unset($_SESSION['correct_answer']);
            unset($_SESSION['created_at']);
            unset($_SESSION['updated_at']);
        }
        header("Location:../ctrl_admin/question.php");
        exit();
    }
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view_admin/question.php');
?>