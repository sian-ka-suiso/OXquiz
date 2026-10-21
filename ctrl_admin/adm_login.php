<?php
//**************************************************
// 初期処理
//**************************************************
    session_start();

    require_once('../model/dbconnect.php');

    require_once('../model/dbfunction_admin.php');
//**************************************************
// 変数取得
//**************************************************
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $pw = isset($_POST['pw']) ? $_POST['pw'] : "";
    $sign_out = isset($_POST['sign_out']) ? $_POST['sign_out'] : "";
    $step = isset($_POST['step']) ? $_POST['step'] : "";
    $arrErr = array();
//**************************************************
// 入力確認（ログインボタンが押されたときだけ行う）
//**************************************************
    if($step == 1){
        if($email == ""){
            $arrErr['email'] = "メールアドレスを入力してください";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $arrErr['email'] = "メールアドレスの形式が正しくありません";
        }

        if($pw == ""){
            $arrErr['login_pass'] = "パスワードを入力してください";
        } elseif(mb_strlen($pw,"UTF-8") < 6 || mb_strlen($pw,"UTF-8") > 20){
            $arrErr['login_pass'] = "パスワードは6〜20文字で入力してください";
        }

        if(empty($arrErr)){
            //**************************************************
            // ログインチェック
            // 管理者・教員のどちらかの権限を持つユーザーのみログインできる
            //**************************************************
            $staff = staffLoginCheck($email, $pw);

            if ($staff) {
                // ログイン成功！セッションハイジャック対策を実行
                session_regenerate_id(true);

                // セッションに「管理者ログイン済みフラグ」と「ユーザー情報」を保存
                // ※一般ユーザー側のセッション（is_login等）とは別名で管理し、
                //   一般ユーザーのログイン状態のまま管理者ページへ直接アクセスできてしまう問題を防ぐ
                $_SESSION['admin_is_login'] = true;
                $_SESSION['admin_user_id']    = $staff['id'];
                $_SESSION['admin_email']      = $email;
                // 権限フラグ（1 または 0）。画面の出し分けにはこの値を使う
                $_SESSION['admin_is_admin']   = $staff['is_admin'];
                $_SESSION['admin_is_teacher'] = $staff['is_teacher'];

                header("location: admin.php");
                exit();
            } else if ($email != "" || $pw != "") {
                $arrErr['common'] = "メールアドレスもしくはパスワードが間違っています。";
            } else {
                $arrErr['common'] = "管理者権限ではありません";
            }
        }
    }
//**************************************************
// サインアウト処理
//**************************************************
    if($sign_out){
        unset($_SESSION['admin_is_login']);
        unset($_SESSION['admin_user_id']);
        unset($_SESSION['admin_email']);
        unset($_SESSION['admin_is_admin']);
        unset($_SESSION['admin_is_teacher']);
    }
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view_admin/adm_login.php');
?>