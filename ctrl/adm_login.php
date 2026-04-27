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
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $pw = isset($_POST['pw']) ? $_POST['pw'] : "";
    $sign_out = isset($_POST['sign_out']) ? $_POST['sign_out'] : "";
    $arrErr = array();
//**************************************************
// ログインチェック
//**************************************************
    $loginOk = admLoginCheck($email, $pw);
    if($loginOk){
        $_SESSION['email'] = $email;
        $_SESSION['pw'] = $pw;
        header("location: admin.php");
        exit();
    } else if ($email != "" || $pw != "") {
        $arrErr['common'] = "メールアドレスもしくはパスワードが間違っています。";
    } else {
        $arrErr['common'] = "";
    }
//**************************************************
// サインアウト処理
//**************************************************
    if($sign_out){
        unset($_SESSION['email']);
        unset($_SESSION['pw']);
    }
//**************************************************
// HTMLを出力
//**************************************************
    //画面へ表示
    require_once('../view_admin/adm_login.html');
?>