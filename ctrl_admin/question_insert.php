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
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $section_id = isset($_POST['section_id']) ? $_POST['section_id'] : "";
    $order_number = isset($_POST['order_number']) ? $_POST['order_number'] : "";
    $options = isset($_POST['options']) ? $_POST['options'] : "";
    $correct_answer = isset($_POST['correct_answer']) ? $_POST['correct_answer'] : "";
    $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : "";
    $updated_at = isset($_POST['updated_at']) ? $_POST['updated_at'] : "";
    $step = isset($_POST['step']) ? $_POST['step'] : "";
//**************************************************
// STEP1（入力）
//**************************************************
if ($step == 1) {
    if ($section_id == "") {
        $arrErr['section_id'] = "セクションIDを入力してください";
    } elseif (!checkQuestionSec($section_id)) {
      // checkQuestionSec() が true なら既存セクションIDあり
        $arrErr['section_id'] = "このセクションIDは存在しません";
    }

    if ($order_number == "") {
        $arrErr['order_number'] = "order番号を入力してください";
    } elseif (!ctype_digit($order_number)) {
        $arrErr['order_number'] = "order番号は半角数字で入力してください";
    } elseif (checkQuestionOrder($order_number, $section_id)) {
        // checkQuestionOrder() が true なら既存order番号あり
        $arrErr['order_number'] = "このorder番号は既に存在します";
    }

    if ($options == "") {
        $arrErr['options'] = "選択肢の数を入力してください";
    } elseif ($options < 0) {
        $arrErr['options'] = "選択肢の数は0以上を指定してください";
    }
    if ($correct_answer == "") {
        $arrErr['correct_answer'] = "正解番号を入力してください";
    } elseif ($correct_answer < 1) {
        $arrErr['correct_answer'] = "正解番号は1以上を指定してください";
    } elseif ($correct_answer > $options) {
        $arrErr['correct_answer'] = "正解番号は選択肢の数以下にしてください";
    }

    // エラーがなければセッションにデータを保存し、ステップ2へ
    if (empty($arrErr)) {
        $_SESSION['id'] = $id;
        $_SESSION['section_id'] = $section_id;
        $_SESSION['order_number'] = $order_number;
        $_SESSION['options'] = $options;
        $_SESSION['correct_answer'] = $correct_answer;
        $_SESSION['created_at'] = $created_at;
        $_SESSION['updated_at'] = $updated_at;
        header("Location: question_insert_confirm.php");
        exit();
    }
}
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view_admin/question_insert.php');
?>