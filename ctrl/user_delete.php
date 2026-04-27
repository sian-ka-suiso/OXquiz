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
    //画面へ表示
    if($step == 1){
        require_once('../view_admin/user_delete.html');
    }elseif($step == "3"){
        header('Location: user.php');
    }else{
        header('Location: user.php');
    }
?>