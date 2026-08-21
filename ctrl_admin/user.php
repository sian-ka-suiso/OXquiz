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
    $users = getUsers();
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : "";
    $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : "";
    $update_at = isset($_POST['update_at']) ? $_POST['update_at'] : "";
    $is_admin = isset($_POST['is_admin']) ? $_POST['is_admin'] : "";
    $step = isset($_POST['step']) ? $_POST['step'] : "";
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
    require_once('../view_admin/user.php');
    // //画面へ表示
    // if($step == ""){
    //     require_once('../view_admin/user.php');
    // }
    // if($step == 1){
    //     require_once('../view_admin/user_delete.php');
    // }
?>