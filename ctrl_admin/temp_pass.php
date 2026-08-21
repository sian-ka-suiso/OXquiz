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
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : "";
    $step = isset($_POST['step']) ? $_POST['step'] : "";
    $temp_pass = isset($_POST['temp_pass']) ? $_POST['temp_pass'] : "";
//**************************************************
// 仮パスワード発行
//**************************************************
    if($step == 1){
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $temp_pass = '';

        for($i = 0; $i < 8; $i++){
            $temp_pass .= $chars[random_int(0, strlen($chars) - 1)];
        }
    }
//**************************************************
// 仮パスワード発行処理
//**************************************************
    if($step == 2){
        $temp_check = ResetLoginPass($id, $temp_pass);
        if($temp_check){
            $step = 3;
        }
    }
//**************************************************
// HTMLを出力
//**************************************************
    //画面へ表示
    if($step == 1){
        require_once('../view_admin/temp_pass.php');
    }elseif($step == "3"){
        header('Location: user.php');
    }else{
        header('Location: user.php');
    }
?>