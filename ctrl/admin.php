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
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : "";
    $pw = isset($_SESSION['pw']) ? $_SESSION['pw'] : "";
    $chapterSectionQuestions = getAll();
    $loginOk = admLoginCheck($email, $pw);
//**************************************************
// HTMLを出力
//**************************************************
    //画面へ表示
    if($loginOk === true) {
        require_once('../view_admin/admin.html');
    } else {
        require_once('../view_admin/adm_login.html');
    }
?>