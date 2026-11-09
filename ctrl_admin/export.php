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
    // ログイン中ユーザーの情報と権限フラグ
    $user_id    = isset($_SESSION['admin_user_id']) ? $_SESSION['admin_user_id'] : 0;
    $is_admin   = isset($_SESSION['admin_is_admin']) ? $_SESSION['admin_is_admin'] : 0;
    $is_teacher = isset($_SESSION['admin_is_teacher']) ? $_SESSION['admin_is_teacher'] : 0;

    // 選択されたクラスID（表示はGET、出力はPOSTで受け取る）
    $class_id = "";
    if (isset($_POST['class_id'])) {
        $class_id = $_POST['class_id'];
    } elseif (isset($_GET['class_id'])) {
        $class_id = $_GET['class_id'];
    }
    $is_export = isset($_POST['export']) && $_POST['export'] === "1";
    $arrErr = array();
//**************************************************
// データ取得
//**************************************************
    // 選択できるクラス（管理者は全クラス、教員は担当クラスのみ）
    $classes = $is_admin ? getClasses() : getClasses($user_id);
//**************************************************
// 入力確認
//**************************************************
    // 選択肢にないクラス（担当外のクラスなど）は選択されていない扱いにする
    $selected_class = null;
    foreach ($classes as $class) {
        if ((string)$class['id'] === (string)$class_id) {
            $selected_class = $class;
            break;
        }
    }
    if ($class_id !== "" && $selected_class === null) {
        $arrErr['class_id'] = "選択できないクラスです";
    }
//**************************************************
// 出力データ取得
//**************************************************
    // 選択したクラスに所属する学生の一覧
    $class_users = ($selected_class !== null) ? getClassUsers((int)$selected_class['id']) : [];
//**************************************************
// Excel出力
//**************************************************
    if ($is_export && $selected_class !== null) {
        // ここでExcelファイルを作成して出力する
    }
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view_admin/export.php');
?>
