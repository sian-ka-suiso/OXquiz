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
    $name = isset($_POST['name']) ? $_POST['name'] : "";
    $folder_name = isset($_POST['folder_name']) ? $_POST['folder_name'] : "";
    $order_number = isset($_POST['order_number']) ? $_POST['order_number'] : "";
    $is_published = isset($_POST['is_published']) ? $_POST['is_published'] : "";
    $step = isset($_POST['step']) ? $_POST['step'] : "";
//**************************************************
// STEP1（入力）
//**************************************************
if ($step == 1) {
    if ($name == "") {
        $arrErr['name'] = "チャプター名を入力してください";
    } elseif (checkChapterName($name)) {
        // checkChapterName() が true なら既存チャプター名あり
        $arrErr['name'] = "このチャプター名は既に存在します";
    }

    if ($folder_name == "") {
        $arrErr['folder_name'] = "フォルダー名を入力してください";
    } elseif (checkFolder($folder_name)) {
        // checkFolder() が true なら既存フォルダー名あり
        $arrErr['folder_name'] = "このフォルダー名は既に存在します";
    }

    if ($order_number == "") {
        $arrErr['order_number'] = "order番号を入力してください";
    } elseif (!ctype_digit($order_number)) {
        $arrErr['order_number'] = "order番号は半角数字で入力してください";
    } elseif (checkOrder($order_number)) {
        // checkChapterName() が true なら既存order番号あり
        $arrErr['order_number'] = "このorder番号は既に存在します";
    }

    // エラーがなければセッションにデータを保存し、ステップ2へ
    if (empty($arrErr)) {
        $_SESSION['id'] = $id;
        $_SESSION['name'] = $name;
        $_SESSION['folder_name'] = $folder_name;
        $_SESSION['order_number'] = $order_number;
        $_SESSION['is_published'] = $is_published;
        header("Location: chapter_insert_confirm.php");
        exit();
    }
}
//**************************************************
// HTMLを出力
//**************************************************
    //画面へ表示
    require_once('../view_admin/chapter_insert.php');
?>