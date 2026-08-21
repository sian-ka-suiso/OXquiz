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
    $sign_up = isset($_POST['sign_up']) ? $_POST['sign_up'] : "";
    $sign_out = isset($_POST['sign_out']) ? $_POST['sign_out'] : "";
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $login_pass = isset($_POST['login_pass']) ? $_POST['login_pass'] : "";
    $class_id = isset($_POST['class_id']) ? $_POST['class_id'] : "";
    $step = isset($_POST['step']) ? $_POST['step'] : "";
    $arrErr = array();
//**************************************************
// サインアウト処理
//**************************************************
    if($sign_out){
        // セッション変数をすべてクリアして破棄するのが最も安全
        $_SESSION = array(); 
        session_destroy();
        // ログアウト後は再読み込み
        header("Location: index.php");
        exit();
    }
//**************************************************
// 入力確認
//**************************************************
    if($step == 1){
        if($email == ""){
            $arrErr['email'] = "メールアドレスを入力してください";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $arrErr['email'] = "メールアドレスの形式が正しくありません";
        }

        if($login_pass == ""){
            $arrErr['login_pass'] = "パスワードを入力してください";
        } elseif(mb_strlen($login_pass,"UTF-8") < 6 || mb_strlen($login_pass,"UTF-8") > 20){
            $arrErr['login_pass'] = "パスワードは6〜20文字で入力してください";
        }

        if(empty($arrErr) || empty($is_guest)){
            //**************************************************
            // ログインチェック
            //**************************************************
            $id = loginCheck($email, $login_pass);
            if ($id) {
                // ログイン成功！セッションハイジャック対策を実行
                session_regenerate_id(true);

                // セッションに「ログイン済みフラグ」と「ユーザー情報」を保存
                $_SESSION['is_login'] = true;
                $_SESSION['user_id']  = $id;
                $_SESSION['email']    = $email;

                header("location: chapter.php");
                exit();
            } else if ($email != "" || $login_pass != "") {
                $arrErr['common'] = "メールアドレスもしくはパスワードが間違っています。";
            } else {
                $arrErr['common'] = "";
            }
        }
    }
//**************************************************
// 新規登録チェック
//**************************************************
    if($sign_up == true && $email != "" && $login_pass != ""){
        $result = insertUser($email, $login_pass, $class_id !== "" ? (int)$class_id : null);
    }
//**************************************************
// ゲストログインチェック
//**************************************************
    //ゲストログインフラグ
    if(isset($_POST['guest'])){
        $_SESSION['is_login'] = true; // ゲストもログイン済み扱いにする
        $_SESSION['guest'] = true;
        header("location: chapter.php");
        exit();
    }
//**************************************************
// HTMLを出力
//**************************************************
    //画面へ表示
    require_once('../view/index.html');

?>