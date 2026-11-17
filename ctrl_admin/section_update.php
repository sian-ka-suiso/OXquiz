<?php
//**************************************************
// 初期処理
//**************************************************
    //SESSIONスタート
    session_start();

    //データベース接続関数の定義ファイルを読み込み
    require_once('../model/dbconnect.php');

    //データベース操作関数の定義ファイルを読み込み
    require_once('../model/dbfunction_admin.php');
//**************************************************
// ログインチェック
//**************************************************
    if(!isset($_SESSION['admin_is_login']) || $_SESSION['admin_is_login'] !== true){
        header("location: adm_login.php"); // 管理者ログイン画面に戻す
        exit();
    }
//**************************************************
// 権限チェック（管理者のみ）
//**************************************************
    if(empty($_SESSION['admin_is_admin'])){
        header("location: section.php"); // セクション一覧に戻す
        exit();
    }
//**************************************************
// 変数取得
//**************************************************
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $chapter_id = isset($_POST['chapter_id']) ? $_POST['chapter_id'] : "";
    $name = isset($_POST['name']) ? $_POST['name'] : "";
    $folder_name = isset($_POST['folder_name']) ? $_POST['folder_name'] : "";
    $order_number = isset($_POST['order_number']) ? $_POST['order_number'] : "";
    $section_categories = isset($_POST['section_categories']) && is_array($_POST['section_categories']) ? $_POST['section_categories'] : [];
    $is_published = isset($_POST['is_published']) ? $_POST['is_published'] : "";
    $step = isset($_POST['step']) ? $_POST['step'] : "";

    // 数理カテゴリーの選択肢（チェックボックスに並べる順）
    $suuriOptions = ['数理Ⅰ', '数理Ⅱ', '数理Ⅲ'];
    // 選択肢にない値は取り除く（array_values で添字を 0,1,2… に振り直す）
    $section_categories = array_values(array_intersect($suuriOptions, $section_categories));
//**************************************************
// 更新処理
//**************************************************
    if($step == 2){
        $update_check = updateSection($id, $chapter_id, $name, $folder_name, $order_number, $is_published)
                     && updateSectionCategories($id, $section_categories);
        if($update_check){
            $step = "3";
        }
    }
//**************************************************
// HTMLを出力
//**************************************************
    //画面へ表示
    if($step == 1){
        require_once('../view_admin/section_update.php');
    }elseif($step == "3"){
        header('Location: section.php');
    }else{
        header('Location: section.php');
    }
    
?>