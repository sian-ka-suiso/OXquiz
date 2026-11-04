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
        header("location: evaluation.php"); // チャプター一覧に戻す
        exit();
    }
//**************************************************
// 変数取得
//**************************************************
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $question_id = isset($_POST['question_id']) ? $_POST['question_id'] : "";
    $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : "";
    $updated_at = isset($_POST['updated_at']) ? $_POST['updated_at'] : "";
    $step = isset($_POST['step']) ? $_POST['step'] : "";
//**************************************************
// 削除処理
//**************************************************
    if($step == 2){
        $delete_check = deleteExplanation($id);
        if($delete_check){
            $step = "3";
        }
    }
//**************************************************
// HTMLを出力
//**************************************************
    if($step == 1){
        require_once('../view_admin/explanation_delete.php');
    }elseif($step == "3"){
        header('Location: ../ctrl_admin/explanation.php');
    }else{
        header('Location: ../ctrl_admin/explanation.php');
    }
?>