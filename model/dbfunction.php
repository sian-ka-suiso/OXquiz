<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/html; charset=UTF-8');

####################################################################################
// コーディング規則
// 「命名規則」
// 〇〇ごとの〇〇　→　〇〇By〇〇
// 〇〇に対応する〇〇　→　〇〇For〇〇
// 「配列の扱い」
// 特に理由がなければ、添字配列（[0]からスタート）
####################################################################################

####################################################################################
### ユーザー関連（ログイン機能が実装されたら）
####################################################################################
//**************************************************
// ログインチェック(戻り値はid)
//**************************************************
function loginCheck($email = "", $login_pass = ""){
    //データベース接続関数の呼び出し
    $pdo = db_connect();
    
    try {
        // 1. SQLを修正：メールアドレスのみでユーザーを特定する
        $sSql = "SELECT id, login_pass FROM user_table WHERE email = :email";
        
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':email', $email, PDO::PARAM_STR);
        $stmh->execute();
        
        // ユーザー情報を取得
        $user = $stmh->fetch(PDO::FETCH_ASSOC);

        // 2. 判定ロジックを修正
        if($user !== false){
            // password_verify(入力された平文, DBにあるハッシュ値)
            // カラム名が 'login_pass' の場合、以下のように照合します
            if (password_verify($login_pass, $user['login_pass'])) {
                return $user['id']; // ログイン成功
            }
        }
        
    } catch (PDOException $Exception) {
        die('実行エラー（' . __FUNCTION__."）：".$Exception->getMessage()."<br />");
    }
    
    return false; // ユーザーがいない、またはパスワード不一致
}
//**************************************************
// 全ユーザーの情報取得
//**************************************************
function getUsers(){
    $result = [];
    $pdo = db_connect();

    try {
        $sSql = "SELECT id, email, login_pass, user_name, created_at, update_at, is_admin FROM user_table";
        $stmh = $pdo->prepare($sSql);
        $stmh->execute();
        $rows = $stmh->fetchAll(PDO::FETCH_ASSOC);
        $result = $rows;
    } catch (PDOException $Exception) {
        error_log("PDO Error in " . __FUNCTION__ . ": " . $Exception->getMessage());
        return [];
    }
    return $result;
}
//**************************************************
// ユーザー情報（ユーザー名、登録日、更新日、管理者フラグ）取得
//**************************************************
function getUserInfo($id) {
    $pdo = db_connect();
    try {
        $sSql = "SELECT user_name, created_at, update_at, is_admin ";
        $sSql .= "FROM user_table ";
        $sSql .= "WHERE id = :id";

        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':id', $id, PDO::PARAM_INT);
        $stmh->execute();

        // FETCH_ASSOC を使うことで、カラム名をキーとした連想配列で取得
        return $stmh->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $Exception) {
        die('実行エラー（' . __FUNCTION__."）：".$Exception->getMessage()."<br />");
    }
}
//**************************************************
// メアド重複確認
//**************************************************
function checkEmail($email) {
    $pdo = db_connect();  // ここで接続を確保
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_table WHERE email = :email");
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count > 0; // true = 存在する
}
//**************************************************
// 新規登録
//**************************************************
function insertUser($email, $login_pass) {

	//データベース接続関数の呼び出し
	$pdo = db_connect();

	try {
        // PASSWORD_DEFAULT を指定すると、その時点のPHPバージョンで最も安全なアルゴリズムが自動選択されます
        $hashed_pass = password_hash($login_pass, PASSWORD_DEFAULT);
		//データ検索の条件
		$sql = "INSERT INTO user_table (email, login_pass) VALUES (:email, :login_pass)";
		//ステートメントハンドラを作成
		$stmh = $pdo->prepare($sql);
		//バインドの実行
		$stmh->bindValue(':email', $email, PDO::PARAM_STR);
        $stmh->bindValue(':login_pass',  $hashed_pass,  PDO::PARAM_STR);
		//SQL文の実行
		$stmh->execute();
		//登録成功を返却
		return true;
	} catch (PDOException $Exception) {
		//例外が発生したらエラーを出力
		die('実行エラー :' . $Exception->getMessage()."<br />");
		//登録失敗を返却
		return false;
	}
}
//**************************************************
// ユーザー名変更(マイページ用)
//**************************************************
function ChangeUserName($id, $change_name) {
	$pdo = db_connect();
	try {
		//データ検索の条件
		$sql = "UPDATE user_table SET user_name = :change_name WHERE id = :id";
		//ステートメントハンドラを作成
		$stmh = $pdo->prepare($sql);
		//バインドの実行
		$stmh->bindValue(':id', $id, PDO::PARAM_INT);
		$stmh->bindValue(':change_name', $change_name, PDO::PARAM_STR);
		//SQL文の実行
		$stmh->execute();
		//登録成功を返却
		return true;
	} catch (PDOException $Exception) {
		//例外が発生したらエラーを出力
		die('実行エラー :' . $Exception->getMessage()."<br />");
		//登録失敗を返却
		return false;
	}
}
//**************************************************
// パスワード再設定(マイページ用)
//**************************************************
function ResetLoginPass($id, $reset_pass) {
    $pdo = db_connect();
    try {
        // --- 追加：新しいパスワードをハッシュ化する ---
        $hashed_pass = password_hash($reset_pass, PASSWORD_DEFAULT);

        $sql = "UPDATE user_table SET login_pass = :reset_pass WHERE id = :id";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':id', $id, PDO::PARAM_INT);
        // --- 修正：ハッシュ化した値をバインドする ---
        $stmh->bindValue(':reset_pass', $hashed_pass, PDO::PARAM_STR);
        
        $stmh->execute();
        return true;
    } catch (PDOException $Exception) {
        die('実行エラー :' . $Exception->getMessage()."<br />");
        return false;
    }
}
//**************************************************
// ユーザー更新（管理ページ用）
//**************************************************
function updateUser($id, $is_admin) {
	$pdo = db_connect();
	try {
		//データ検索の条件
		$sql = "UPDATE user_table SET is_admin = :is_admin WHERE id = :id";
		//ステートメントハンドラを作成
		$stmh = $pdo->prepare($sql);
		//バインドの実行
		$stmh->bindValue(':id', $id, PDO::PARAM_STR);
        $stmh->bindValue(':is_admin', $is_admin, PDO::PARAM_STR);
		//SQL文の実行
		$stmh->execute();
		//登録成功を返却
		return true;
	} catch (PDOException $Exception) {
		//例外が発生したらエラーを出力
		die('実行エラー :' . $Exception->getMessage()."<br />");
		//登録失敗を返却
		return false;
	}
}
//**************************************************
// ユーザー削除（管理ページ用）
//**************************************************
function deleteUser($id){
	$pdo = db_connect();
	try {
		$sql = "DELETE FROM user_table WHERE id = :id";
		$stmh = $pdo->prepare($sql);
		$stmh->bindValue(':id', $id,  PDO::PARAM_INT);
		$stmh->execute();
		return true;
	} catch (PDOException $Exception) {
        error_log("PDO Error in " . __FUNCTION__ . ": " . $Exception->getMessage());
		return false;
	}
}
####################################################################################
### 管理者
####################################################################################
//**************************************************
// 管理者ログインチェック
//**************************************************
function admLoginCheck($email = "", $pw = ""){
    //初期化
    $arrUser = array();
    //データベース接続関数の呼び出し
    $pdo = db_connect();
    try {
        //変数の準備
        $sSql  = "";
        //データ検索のSQLを作成
        $sSql .= "SELECT ";
        $sSql .= "   * ";
        $sSql .= "FROM ";
        $sSql .= "   user_table ";
        $sSql .= "WHERE ";
        $sSql .= "  email = :email AND ";
        $sSql .= "  login_pass = :pw AND ";
        $sSql .= "  is_admin = 1 ";
        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':email',   $email,   PDO::PARAM_STR);
        $stmh->bindValue(':pw', $pw, PDO::PARAM_STR);
        //SQL文の実行
        $stmh->execute();
        //実行結果を取得
        $arrUser = $stmh->fetch(PDO::FETCH_ASSOC);
        //ログイン情報の有無を判定
        if($arrUser !== false){
            return true;
        }
    } catch (PDOException $Exception) {
        //例外が発生したらエラーを出力
        die('実行エラー（' . __FUNCTION__."）：".$Exception->getMessage()."<br />");
    }
    return false;
}
//**************************************************
// 章、節、問の全情報を取り出す
//**************************************************
function getAll(){
    $array_result = array();
    $pdo = db_connect();
    try {
        $sSql = "";
        $sSql .= "SELECT ";
        $sSql .= "* ";
        $sSql .= "FROM ";
        $sSql .= "chapter_table c ";
        $sSql .= "LEFT JOIN section_table s ON s.chapter_id = c.id ";
        $sSql .= "LEFT JOIN question_table q ON q.section_id = s.id";
        $stmh = $pdo->prepare($sSql);

        $stmh->execute();

        $array_result = $stmh->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        die('実行エラー :' . $Exception->getMessage() . "<br/>");
    }

    return $array_result;
}


####################################################################################
### チャプター関連
####################################################################################
//**************************************************
// チャプターテーブルの全情報を取り出す
//**************************************************
function getChapterAll(){
    $array_result = array();
    $pdo = db_connect();
    try {
        $sSql = "";
        $sSql .= "SELECT ";
        $sSql .= "* ";
        $sSql .= "FROM ";
        $sSql .= "chapter_table ";
        $stmh = $pdo->prepare($sSql);

        $stmh->execute();

        $array_result = $stmh->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        die('実行エラー :' . $Exception->getMessage() . "<br/>");
    }

    return $array_result;
}
//**************************************************
// チャプター名を取り出す
//**************************************************
function getChapterNames($chapter_id)
{
    $array_result = array();
    $pdo = db_connect();
    try {
        $sSql = "";
        $sSql .= "SELECT ";
        $sSql .= "name, folder_name ";
        $sSql .= "FROM ";
        $sSql .= "chapter_table ";
        $sSql .= "WHERE id = :chapter_id"; 
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':chapter_id', $chapter_id, PDO::PARAM_INT);

        $stmh->execute();

        $array_result = $stmh->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        die('実行エラー :' . $Exception->getMessage() . "<br/>");
    }

    return $array_result;
}
//**************************************************
// チャプター名（セクション名付き）を取り出す
//**************************************************
function getChaptersWithSections()
{
    $array_result = array();
    $pdo = db_connect();

    try {
        // SQLは完璧です！
        $sSql  = "SELECT c.id, c.name AS chapter_name, c.is_published AS c_is_pub, s.name AS section_name, s.is_published AS s_is_pub ";
        $sSql .= "FROM chapter_table c ";
        $sSql .= "JOIN section_table s ON c.id = s.chapter_id ";
        $sSql .= "ORDER BY c.order_number, s.order_number";

        $stmh = $pdo->prepare($sSql);
        $stmh->execute();
        $rows = $stmh->fetchAll(PDO::FETCH_ASSOC);

        $array_result = [];
        foreach ($rows as $row) {
            $chapter_id = $row['id'];
            
            // 初めて出てきた章の場合、土台を作る
            if (!isset($array_result[$chapter_id])) {
                $array_result[$chapter_id] = [
                    'id'           => $chapter_id,
                    'chapter_name' => $row['chapter_name'],
                    'chapter_published' => $row['c_is_pub'],
                    'sections' => [], // セクション用の空配列を用意
                ];
            }
            // セクション名を追加（'sections' キーの配列に追加する）
            $array_result[$chapter_id]['sections'][] = [
                'name'   => $row['section_name'],
                'section_published' => $row['s_is_pub']
            ];
        }

        // 最後に数字添字の配列に戻す
        $array_result = array_values($array_result);

    } catch (PDOException $Exception) {
        die('実行エラー :' . $Exception->getMessage() . "<br/>");
    }

    return $array_result;
}


####################################################################################
### セクション関連
####################################################################################
//**************************************************
// セクションテーブルの全情報を取り出す
//**************************************************
function getSectionAll(){
    $array_result = array();
    $pdo = db_connect();
    try {
        $sSql = "";
        $sSql .= "SELECT ";
        $sSql .= "* ";
        $sSql .= "FROM ";
        $sSql .= "section_table ";
        $stmh = $pdo->prepare($sSql);

        $stmh->execute();

        $array_result = $stmh->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        die('実行エラー :' . $Exception->getMessage() . "<br/>");
    }

    return $array_result;
}
//**************************************************
// セクション名を取り出す（ファイルパス用）
//**************************************************
function getSectionNames($section_id)
{
    $array_result = array();
    $pdo = db_connect();
    try {
        $sSql = "";
        $sSql .= "SELECT ";
        $sSql .= "name, folder_name ";
        $sSql .= "FROM ";
        $sSql .= "section_table ";
        $sSql .= "WHERE id = :section_id"; 
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':section_id', $section_id, PDO::PARAM_INT);

        $stmh->execute();

        $array_result = $stmh->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        die('実行エラー :' . $Exception->getMessage() . "<br/>");
    }

    return $array_result;
}
//**************************************************
// チャプターIdに対応する、セクション名（クエスチョンId付き）を取り出す
//**************************************************
function getSectionsWithQuestions($chapter_id)
{
    // 初期化
    $array_result = array();
    $pdo = db_connect();

    try {
        // SQLのカラム名とAS（別名）を確認
        $sSql  = "SELECT s.id, s.name, s.is_published AS is_pub, q.id AS question_id ";
        $sSql .= "FROM section_table s ";
        $sSql .= "JOIN question_table q ON s.id = q.section_id AND s.chapter_id = :chapter_id ";
        $sSql .= "ORDER BY s.order_number, q.order_number";

        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':chapter_id', $chapter_id, PDO::PARAM_INT);
        $stmh->execute();
        $rows = $stmh->fetchAll(PDO::FETCH_ASSOC);

        // 整形
        $array_result = [];
        foreach ($rows as $row) {
            $section_id = $row['id'];

            // 土台を作る
            if (!isset($array_result[$section_id])) {
                $array_result[$section_id] = [
                    'id'   => $section_id,
                    'name'         => $row['name'],
                    'is_published' => $row['is_pub'],
                    'question_ids' => [] // クエスチョンIDを入れるための空配列
                ];
            }

            // クエスチョンIdを 'question_ids' キーの配列に追加
            $array_result[$section_id]['question_ids'][] = $row['question_id'];
        }

        // 連想配列をインデックス配列に変換
        $array_result = array_values($array_result);

    } catch (PDOException $Exception) {
        die('実行エラー :' . $Exception->getMessage() . "<br/>");
    }

    return $array_result;
}

####################################################################################
### 問題
####################################################################################
//**********************************************************************************
// 選択肢数、正答の情報を取得
//**********************************************************************************
function getQuestions($question_id)
{
    $array_result = array();
    if (empty($question_id)) {
        return $array_result;
    }
    $pdo = db_connect();
    try {
        $sSql = "SELECT options, correct_answer ";
        $sSql .= "FROM question_table ";
        $sSql .= "WHERE id = :question_id";

        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':question_id', $question_id, PDO::PARAM_INT);
        $stmh->execute();
        $array_result = $stmh->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $Exception) {
        // 例外が発生したらエラー処理を出力
        die('実行エラー :' . $Exception->getMessage() . "<br/>");
    }

    return $array_result;
}
//**********************************************************************************
// 問題id配列
//**********************************************************************************
function getQuestionIds($section_id){
    $array_result = array();
    $pdo = db_connect();
    try {
        $sSql = "";
        $sSql .= "SELECT ";
        $sSql .= "id ";
        $sSql .= "FROM ";
        $sSql .= "question_table ";
        $sSql .= "WHERE section_id = :section_id "; 
        $sSql .= "ORDER BY order_number";
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':section_id', $section_id, PDO::PARAM_INT);
        $stmh->execute();

        $array_result = $stmh->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $Exception) {
        die('実行エラー :' . $Exception->getMessage() . "<br/>");
    }

    return $array_result;
}
//**********************************************************************************
// 問題数カウント
//**********************************************************************************
function getQuestionCount($section_id){
    $result = 0;
    if (empty($section_id)) {
        return $result;
    }

    $pdo = db_connect();
    try {
        $sSql = "SELECT COUNT(*) AS question_count
                 FROM question_table
                 WHERE section_id = :section_id";

        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':section_id', $section_id, PDO::PARAM_INT);
        $stmh->execute();

        $result = (int)$stmh->fetchColumn();

    } catch (PDOException $Exception) {
        die('実行エラー :' . $Exception->getMessage() . "<br/>");
    }

    return $result;
}

####################################################################################
### 解説
####################################################################################
function getExplanationIds($question_id)
{
    $array_result = array();
    if (empty($question_id)) {
        return $array_result;
    }
    $pdo = db_connect();
    try {
        $sSql = "SELECT id ";
        $sSql .= "FROM explanation_table ";
        $sSql .= "WHERE question_id = :question_id";

        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':question_id', $question_id, PDO::PARAM_INT);
        $stmh->execute();
        $array_result = $stmh->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $Exception) {
        // 例外が発生したらエラー処理を出力
        die('実行エラー :' . $Exception->getMessage() . "<br/>");
    }

    return $array_result;
}

####################################################################################
### math_nav関連
####################################################################################
//********************************************************************************************
// 数学ナビのリンク名とURLを取得
//********************************************************************************************
function getNavs($question_id)
{
    $array_result = array();

    if (empty($question_id)) {
        return $array_result;
    }

    $pdo = db_connect();
    try {
        $sSql = "SELECT m.link_name, m.link_url ";
        $sSql .= "FROM question_mathnav qm ";
        $sSql .= "JOIN mathnav_table m ON qm.mathnav_id = m.id ";
        $sSql .= "WHERE qm.question_id = :question_id ";
        $sSql .= "ORDER BY qm.order_number";

        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':question_id', $question_id, PDO::PARAM_INT);
        $stmh->execute();
        $array_result = $stmh->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $Exception) {
        // 例外が発生したらエラー処理を出力
        die('実行エラー :' . $Exception->getMessage() . "<br/>");
    }

    return $array_result;
}
####################################################################################
### その他
####################################################################################
//********************************************************************************************
// htmlへの文字列出力用
//********************************************************************************************
function V2H($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>