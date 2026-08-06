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
// 変数取得
//**************************************************
    $chapter_data = getChapterData();
    $sign_up = isset($_POST['sign_up']) ? $_POST['sign_up'] : "";
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $name = isset($_POST['name']) ? $_POST['name'] : "";
    $folder_name = isset($_POST['folder_name']) ? $_POST['folder_name'] : "";
    $older_number = isset($_POST['older_number']) ? $_POST['older_number'] : "";
    $is_published = isset($_POST['is_published']) ? $_POST['is_published'] : "";
    $chapter_insert = isset($_POST['chapter_insert']) ? $_POST['chapter_insert'] : "";
//**************************************************
// 挿入処理
//**************************************************
    if($chapter_insert){
        $insert_check = insertChapter($_SESSION['id'],$_SESSION['name'], $_SESSION['folder_name'], $_SESSION['order_number'],$_SESSION['is_published']);
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
// 削除処理
//**************************************************
    // if($step == 2){
    //     $delete_check = deleteUser($id);
    //     if($delete_check){
    //         $step = "";
    //     }
    // }
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view_admin/chapter.html');
    // //画面へ表示
    // if($step == ""){
    //     require_once('../view_admin/user.html');
    // }
    // if($step == 1){
    //     require_once('../view_admin/user_delete.html');
    // }
?>