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
    $name = isset($_POST['name']) ? $_POST['name'] : "";
    $folder_name = isset($_POST['folder_name']) ? $_POST['folder_name'] : "";
    $order_number = isset($_POST['order_number']) ? $_POST['order_number'] : "";
    $is_published = isset($_POST['is_published']) ? $_POST['is_published'] : "";
    $step = isset($_POST['step']) ? $_POST['step'] : "";
//**************************************************
// 削除処理
//**************************************************
    if($step == 2){
        $delete_check = deleteChapter($id);
        if($delete_check){
            $step = "3";
        }
    }
//**************************************************
// HTMLを出力
//**************************************************
    if($step == 1){
        require_once('../view_admin/chapter_delete.php');
    }elseif($step == "3"){
        header('Location: ../ctrl_admin/chapter.php');
    }else{
        header('Location: ../ctrl_admin/chapter.php');
    }
?>