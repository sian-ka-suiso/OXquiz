<?php
//**************************************************
// 初期処理
//**************************************************
    //SESSIONスタート
    session_start();

    //データベース接続関数の定義ファイルを読み込み
    require_once('../model/dbconnect.php');

    //データベース操作関数の定義ファイルを読み込み
    require_once('../model/dbfunction.php');
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
    $section_data = getSectionAll();
    $sign_up = isset($_POST['sign_up']) ? $_POST['sign_up'] : "";
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $chapter_id = isset($_POST['chapter_id']) ? $_POST['chapter_id'] : "";
    $name = isset($_POST['name']) ? $_POST['name'] : "";
    $folder_name = isset($_POST['folder_name']) ? $_POST['folder_name'] : "";
    $older_number = isset($_POST['older_number']) ? $_POST['older_number'] : "";
    $is_published = isset($_POST['is_published']) ? $_POST['is_published'] : "";
    $section_insert = isset($_POST['section_insert']) ? $_POST['section_insert'] : "";
//**************************************************
// 挿入処理
//**************************************************
    if($section_insert){
        $insert_check = insertSection($_SESSION['id'],$_SESSION['chapter_id'],$_SESSION['name'], $_SESSION['folder_name'], $_SESSION['order_number'],$_SESSION['is_published']);
        if($insert_check){
            unset($_SESSION['id']);
            unset($_SESSION['chapter_id']);
            unset($_SESSION['name']);
            unset($_SESSION['folder_name']);
            unset($_SESSION['order_number']);
            unset($_SESSION['is_published']);
        }
        header("Location:../ctrl_admin/section.php");
        exit();
    }
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view_admin/section.php');
    // //画面へ表示
    // if($step == ""){
    //     require_once('../view_admin/user.php');
    // }
    // if($step == 1){
    //     require_once('../view_admin/user_delete.php');
    // }
?>