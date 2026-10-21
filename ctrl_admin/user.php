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
    // 検索・並び替えの条件（GETで受け取る）
    $search_id   = isset($_GET['search_id']) ? trim($_GET['search_id']) : "";
    $search_role = isset($_GET['role']) ? $_GET['role'] : "";
    $sort        = isset($_GET['sort']) ? $_GET['sort'] : "id_asc";
    $arrErr = array();

    // プルダウンの選択肢（キーはgetUsers()に渡す値）
    $roleOptions = [
        ''        => 'すべて',
        'admin'   => '管理者',
        'teacher' => '教員',
        'general' => '一般',
    ];
    $sortOptions = [
        'id_asc'          => 'ID順（昇順）',
        'id_desc'         => 'ID順（降順）',
    ];
//**************************************************
// 入力確認
//**************************************************
    if ($search_id !== "" && !ctype_digit($search_id)) {
        $arrErr['search_id'] = "IDは半角数字で入力してください";
        $search_id = ""; // 不正な値ではID条件をかけない
    }
    // 選択肢にない値は、それぞれ初期値に戻す
    if (!isset($roleOptions[$search_role])) { $search_role = ""; }
    if (!isset($sortOptions[$sort])) { $sort = "id_asc"; }
//**************************************************
// データ取得
//**************************************************
    // ユーザーテーブルの情報を取得
    $users = getUsers($search_id, $search_role, $sort);
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