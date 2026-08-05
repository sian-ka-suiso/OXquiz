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
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : "";
    //ログインパスワード
    $login_pass = isset($_SESSION['login_pass']) ? $_SESSION['login_pass'] : "";
    //クラスID
    $class_id = isset($_SESSION['class_id']) ? $_SESSION['class_id'] : "";
    //クラス名
    if ($class_id === "" ) {
        $class_name = "";
    } elseif ((int)$class_id === 0) {
        $class_name = "所属無し";
    } else {
        $class_name = getClassName((int)$class_id);
    }
//**************************************************
// HTMLを出力
//**************************************************
    //画面へ表示
    require_once('../view/sign_up_confirm.html');

?>