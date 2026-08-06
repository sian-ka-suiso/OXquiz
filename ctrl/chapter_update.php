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
    $name = isset($_POST['name']) ? $_POST['name'] : "";
    $folder_name = isset($_POST['folder_name']) ? $_POST['folder_name'] : "";
    $order_number = isset($_POST['order_number']) ? $_POST['order_number'] : "";
    $is_published = isset($_POST['is_published']) ? $_POST['is_published'] : "";
    $step = isset($_POST['step']) ? $_POST['step'] : "";
//**************************************************
// 削除処理
//**************************************************
    if($step == 2){
        $update_check = updateChapter($id, $name, $folder_name, $order_number, $is_published);
        if($update_check){
            $step = "3";
        }
    }
//**************************************************
// HTMLを出力
//**************************************************
    //画面へ表示
    if($step == 1){
        require_once('../view_admin/chapter_update.html');
    }elseif($step == "3"){
        header('Location: ../ctrl_admin/chapter.php');
    }else{
        header('Location: ../ctrl_admin/chapter.php');
    }
    
?>