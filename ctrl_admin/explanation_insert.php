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
// 権限チェック（管理者のみ）
//**************************************************
    if(empty($_SESSION['admin_is_admin'])){
        header("location: explanation.php"); // チャプター一覧に戻す
        exit();
    }
//**************************************************
// 変数取得
//**************************************************
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $question_id = isset($_POST['question_id']) ? $_POST['question_id'] : "";
    $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : "";
    $updated_at = isset($_POST['updated_at']) ? $_POST['updated_at'] : "";

    $step = isset($_POST['step']) ? $_POST['step'] : "";
//**************************************************
// STEP1（入力）
//**************************************************
if ($step == 1) {
    if ($question_id == "") {
        $arrErr['question_id'] = "クエスチョンIDを入力してください";
    } elseif (!checkExplanationQue($question_id)) {
        // checkExplanationQue() が true なら既存クエスチョンIDなし
        $arrErr['question_id'] = "このクエスチョンIDは存在しません";
    }

    // エラーがなければセッションにデータを保存し、ステップ2へ
    if (empty($arrErr)) {
        $_SESSION['id'] = $id;
        $_SESSION['question_id'] = $question_id;
        header("Location: explanation_insert_confirm.php");
        exit();
    }
}
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view_admin/explanation_insert.php');
?>