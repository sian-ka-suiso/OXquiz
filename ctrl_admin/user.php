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
        $roles = [];
        if ($user['is_admin'] == 1) { $roles[] = ['label' => '管理者', 'class' => 'badge-admin']; }
        if ($user['is_teacher'] == 1) { $roles[] = ['label' => '教員', 'class' => 'badge-teacher']; }
        if (empty($roles)) { $roles[] = ['label' => '一般', 'class' => 'badge-user' ]; }
        $user['roles'] = $roles;
    }
    unset($user);
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view_admin/user.php');
?>