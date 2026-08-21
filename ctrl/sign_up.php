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
    //メールアドレス
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    //ログインパスワード
    $login_pass = isset($_POST['login_pass']) ? $_POST['login_pass'] : "";
    //ログインパスワード（確認）
    $login_pass2 = isset($_POST['login_pass2']) ? $_POST['login_pass2'] : "";
    //クラスID
    $class_id = isset($_POST['class_id']) ? $_POST['class_id'] : "";
    //処理ステップ
    $step = isset($_POST['step']) ? $_POST['step'] : "";
    $arrErr = array();
    //選択可能なクラス一覧を取得
    $arrClasses = getActiveClasses();
//**************************************************
// STEP1（入力）
//**************************************************
if ($step == 1) {
    if ($email == "") {
        $arrErr['email'] = "メールアドレスを入力してください";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $arrErr['email'] = "有効なメールアドレスを入力してください";
    } elseif (checkEmail($email)) {
        // checkEmail() が true なら既存メールあり
        $arrErr['email'] = "このメールアドレスは既に登録されています";
    }

    if ($login_pass == "") {
        $arrErr['login_pass'] = "パスワードを入力してください";
    } elseif (mb_strlen($login_pass, "UTF-8") < 6 || mb_strlen($login_pass, "UTF-8") > 20) {
        $arrErr['login_pass'] = "6〜20文字で入力してください";
    }

    if ($login_pass2 == "") {
        $arrErr['login_pass2'] = "パスワードを入力してください";
    } elseif (mb_strlen($login_pass2, "UTF-8") < 6 || mb_strlen($login_pass2, "UTF-8") > 20) {
        $arrErr['login_pass2'] = "パスワードは6〜20文字で入力してください";
    } elseif ($login_pass != $login_pass2) {
        $arrErr['login_pass2'] = "確認用パスワードが一致しません";
    }

    if ($class_id == "") {
        $arrErr['class_id'] = "クラスを選択してください";
    }

    // エラーがなければセッションにデータを保存し、ステップ2へ
    if (empty($arrErr)) {
        $_SESSION['email'] = $email;
        $_SESSION['login_pass'] = $login_pass;
        $_SESSION['class_id'] = $class_id;
        header("Location: sign_up_confirm.php");
        exit();
    }
}
//**************************************************
// HTMLを出力
//**************************************************
    //画面へ表示
    require_once('../view/sign_up.php');
?>