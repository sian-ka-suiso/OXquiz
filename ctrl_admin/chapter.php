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
    $chapter_insert = isset($_POST['chapter_insert']) ? $_POST['chapter_insert'] : "";
    // 検索・並び替えの条件（GETで受け取る）
    $search_id = isset($_GET['search_id']) ? trim($_GET['search_id']) : "";
    $sort      = isset($_GET['sort']) ? $_GET['sort'] : "order_asc";
    $arrErr = array();

    // プルダウンの選択肢（キーはgetChapters()に渡す値）
    $sortOptions = [
        'order_asc'  => '表示順（昇順）',
        'order_desc' => '表示順（降順）',
        'id_asc'     => 'ID順（昇順）',
        'id_desc'    => 'ID順（降順）',
    ];
//**************************************************
// 入力確認
//**************************************************
    if ($search_id !== "" && !ctype_digit($search_id)) {
        $arrErr['search_id'] = "IDは半角数字で入力してください";
        $search_id = ""; // 不正な値ではID条件をかけない
    }
    // 選択肢にない値は初期値に戻す
    if (!isset($sortOptions[$sort])) { $sort = "order_asc"; }
//**************************************************
// 挿入処理
//**************************************************
    // 追加は管理者のみ
    if($chapter_insert && !empty($_SESSION['admin_is_admin'])){
        $insert_check = insertChapter($_SESSION['name'], $_SESSION['folder_name'], $_SESSION['order_number'],$_SESSION['is_published']);
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
// データ取得
//**************************************************
    // チャプターテーブルの情報を取得
    $chapter_data = getChapters($search_id, $sort);
    // 権限を取得
    $is_admin   = isset($_SESSION['admin_is_admin']) ? $_SESSION['admin_is_admin'] : 0;
    $is_teacher = isset($_SESSION['admin_is_teacher']) ? $_SESSION['admin_is_teacher'] : 0;
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view_admin/chapter.php');
?>