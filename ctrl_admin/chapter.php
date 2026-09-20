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
    $chapter_data = getChapterAll();
    $chapter_insert = isset($_POST['chapter_insert']) ? $_POST['chapter_insert'] : "";
//**************************************************
// 挿入処理
//**************************************************
    if($chapter_insert){
        $insert_check = insertChapter($_SESSION['name'], $_SESSION['folder_name'], $_SESSION['order_number'],$_SESSION['is_published']);
        if($insert_check){
            unset($_SESSION['id']);
            unset($_SESSION['name']);
            unset($_SESSION['folder_name']);
            unset($_SESSION['order_number']);
            unset($_SESSION['is_published']);
        }
        header("Location:../ctrl_admin/chapter.php");
        exit();
    }
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view_admin/chapter.php');
?>