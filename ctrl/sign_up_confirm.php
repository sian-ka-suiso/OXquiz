<?php
//**************************************************
// 初期処理
//**************************************************
    session_start();

    require_once('../model/dbconnect.php');

    require_once('../model/dbfunction.php');
//**************************************************
// 変数取得
//**************************************************
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : "";
    $login_pass = isset($_SESSION['login_pass']) ? $_SESSION['login_pass'] : "";
    $class_id = isset($_SESSION['class_id']) ? $_SESSION['class_id'] : "";
    $tos_agree = isset($_SESSION['tos_agree']) ? $_SESSION['tos_agree'] : false;

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
    require_once('../view/sign_up_confirm.php');

?>