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
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $section_id = isset($_POST['section_id']) ? $_POST['section_id'] : "";
    $order_number = isset($_POST['order_number']) ? $_POST['order_number'] : "";
    $options = isset($_POST['options']) ? $_POST['options'] : "";
    $correct_answer = isset($_POST['correct_answer']) ? $_POST['correct_answer'] : "";
    $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : "";
    $updated_at = isset($_POST['updated_at']) ? $_POST['updated_at'] : "";
    $step = isset($_POST['step']) ? $_POST['step'] : "";
//**************************************************
// 削除処理
//**************************************************
    if($step == 2){
        $delete_check = deleteQuestion($id);
        if($delete_check){
            $step = "3";
        }
    }
//**************************************************
// HTMLを出力
//**************************************************
    if($step == 1){
        require_once('../view_admin/question_delete.php');
    }elseif($step == "3"){
        header('Location: ../ctrl_admin/question.php');
    }else{
        header('Location: ../ctrl_admin/question.php');
    }
?>