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
    $question_insert = isset($_POST['question_insert']) ? $_POST['question_insert'] : "";
    // 検索・並び替えの条件（GETで受け取る）
    $search_id         = isset($_GET['search_id']) ? trim($_GET['search_id']) : "";
    $search_section_id = isset($_GET['search_section_id']) ? trim($_GET['search_section_id']) : "";
    $sort              = isset($_GET['sort']) ? $_GET['sort'] : "section_asc";
    $arrErr = array();

    // プルダウンの選択肢（キーはgetQuestionList()に渡す値）
    $sortOptions = [
        'section_asc'  => 'セクションID順（昇順）',
        'section_desc' => 'セクションID順（降順）',
        'id_asc'       => 'ID順（昇順）',
        'id_desc'      => 'ID順（降順）',
    ];
//**************************************************
// 入力確認
//**************************************************
    if ($search_id !== "" && !ctype_digit($search_id)) {
        $arrErr['search_id'] = "IDは半角数字で入力してください";
        $search_id = ""; // 不正な値ではID条件をかけない
    }
    if ($search_section_id !== "" && !ctype_digit($search_section_id)) {
        $arrErr['search_section_id'] = "セクションIDは半角数字で入力してください";
        $search_section_id = "";
    }
    // 選択肢にない値は初期値に戻す
    if (!isset($sortOptions[$sort])) { $sort = "section_asc"; }
//**************************************************
// 挿入処理
//**************************************************
    // 追加は管理者のみ
    if($question_insert && !empty($_SESSION['admin_is_admin'])){
        $insert_check = insertQuestion($_SESSION['section_id'], $_SESSION['order_number'], $_SESSION['options'],$_SESSION['correct_answer']);
        if($insert_check){
            unset($_SESSION['id']);
            unset($_SESSION['section_id']);
            unset($_SESSION['order_number']);
            unset($_SESSION['options']);
            unset($_SESSION['correct_answer']);
            unset($_SESSION['created_at']);
            unset($_SESSION['updated_at']);
        }
        header("Location:../ctrl_admin/question.php");
        exit();
    }
//**************************************************
// データ取得
//**************************************************
    // 問題テーブルの情報を取得
    $question_data = getQuestionList($search_id, $search_section_id, $sort);
    // 権限を取得
    $is_admin   = isset($_SESSION['admin_is_admin']) ? $_SESSION['admin_is_admin'] : 0;
    $is_teacher = isset($_SESSION['admin_is_teacher']) ? $_SESSION['admin_is_teacher'] : 0;
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view_admin/question.php');
?>