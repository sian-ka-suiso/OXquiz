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
    // ユーザーテーブルの情報を取得
    $users = getUsers();
    // 各権限を配列にまとめて定義
    foreach ($users as &$user) {
        $user['roles'] = getRoleLabels($user);
    }
    unset($user);
    // 権限を取得
    $is_admin   = isset($_SESSION['admin_is_admin']) ? $_SESSION['admin_is_admin'] : 0;
    $is_teacher = isset($_SESSION['admin_is_teacher']) ? $_SESSION['admin_is_teacher'] : 0;
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view_admin/user.php');
?>