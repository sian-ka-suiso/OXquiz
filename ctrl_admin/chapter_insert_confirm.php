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
        header("location: login"); // 管理者ログイン画面に戻す
        exit();
    }
//**************************************************
// 変数取得
//**************************************************
$id = isset($_SESSION['id']) ? $_SESSION['id'] : "";
$name = isset($_SESSION['name']) ? $_SESSION['name'] : "";
$folder_name = isset($_SESSION['folder_name']) ? $_SESSION['folder_name'] : "";
$order_number = isset($_SESSION['order_number']) ? $_SESSION['order_number'] : "";
$is_published = isset($_SESSION['is_published']) ? $_SESSION['is_published'] : "";

$step = isset($_POST['step']) ? $_POST['step'] : "";
//**************************************************
// HTMLを出力
//**************************************************
    //画面へ表示
    require_once('../view_admin/chapter_insert_confirm.php');

?>