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
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : "";
    $step = isset($_POST['step']) ? $_POST['step'] : "";
//**************************************************
// 削除処理
//**************************************************
    if($step == 2){
        $delete_check = deleteUser($id);
        if($delete_check){
            $step = "3";
        }
    }
//**************************************************
// HTMLを出力
//**************************************************
    if($step == 1){
        require_once('../view_admin/user_delete.php');
    }elseif($step == "3"){
        header('Location: user.php');
    }else{
        header('Location: user.php');
    }
?>