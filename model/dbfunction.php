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
//
// 管理者専用の関数は model/dbfunction_admin.php にまとめている。
####################################################################################

####################################################################################
### ユーザー関連
####################################################################################
//**************************************************
// メアド重複確認
//**************************************************
function checkEmail(string $email) {
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_table WHERE email = :email");
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count > 0; // true = 存在する
}
//**************************************************
// 新規登録
//**************************************************
function insertUser(string $email, string $login_pass, ?int $class_id = null, bool $tos_agreed = false) {
    $pdo = db_connect();

    try {
        // PASSWORD_DEFAULT を指定すると、その時点のPHPバージョンで最も安全なアルゴリズムが自動選択される
        $hashed_pass = password_hash($login_pass, PASSWORD_DEFAULT);
        $tos_agreed_at = $tos_agreed ? date('Y-m-d H:i:s') : null;

        $sql = "INSERT INTO user_table (email, login_pass, class_id, tos_agreed_at) VALUES (:email, :login_pass, :class_id, :tos_agreed_at)";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':email', $email, PDO::PARAM_STR);
        $stmh->bindValue(':login_pass', $hashed_pass, PDO::PARAM_STR);
        $stmh->bindValue(':class_id', $class_id, PDO::PARAM_INT);
        $stmh->bindValue(':tos_agreed_at', $tos_agreed_at, $tos_agreed_at === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmh->execute();
        return true;
    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
        return false;
    }
}
//**************************************************
// ログインチェック(戻り値はid)
//**************************************************
function loginCheck($email = "", $login_pass = ""){
    $pdo = db_connect();

    try {
        $sSql = "SELECT id, login_pass FROM user_table WHERE email = :email";
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':email', $email, PDO::PARAM_STR);
        $stmh->execute();
        $user = $stmh->fetch(PDO::FETCH_ASSOC);

        if ($user !== false) {
            if (password_verify($login_pass, $user['login_pass'])) {
                return $user['id'];
            }

            // 移行期間中の平文パスワードとの一致も許可し、一致した場合はハッシュ化して保存し直す
            if ($login_pass === $user['login_pass']) {
                $newHash = password_hash($login_pass, PASSWORD_DEFAULT);
                $updateSql = "UPDATE user_table SET login_pass = :new_pass WHERE id = :id";
                $updateStmh = $pdo->prepare($updateSql);
                $updateStmh->bindValue(':new_pass', $newHash, PDO::PARAM_STR);
                $updateStmh->bindValue(':id', $user['id'], PDO::PARAM_INT);
                $updateStmh->execute();
                return $user['id'];
            }
        }
    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }

    return false; // ユーザーがいない、またはパスワード不一致
}
//**************************************************
// ユーザー情報（ユーザー名、登録日、更新日、管理者フラグ）取得
//**************************************************
function getUserInfo(int $id) {
    $pdo = db_connect();
    try {
        $sSql = "SELECT user_name, created_at, update_at, is_admin, is_teacher, class_id, tos_agreed_at ";
        $sSql .= "FROM user_table ";
        $sSql .= "WHERE id = :id";

        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':id', $id, PDO::PARAM_INT);
        $stmh->execute();

        return $stmh->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }
}
//**************************************************
// ユーザー名変更(マイページ用)
//**************************************************
function ChangeUserName(int $id, string $change_name) {
    $pdo = db_connect();
    try {
        $sql = "UPDATE user_table SET user_name = :change_name WHERE id = :id";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':id', $id, PDO::PARAM_INT);
        $stmh->bindValue(':change_name', $change_name, PDO::PARAM_STR);
        $stmh->execute();
        return true;
    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
        return false;
    }
}
//**************************************************
// パスワード再設定(マイページ＆仮パスワード用)
//**************************************************
function ResetLoginPass(int $id, string $reset_pass) {
    $pdo = db_connect();
    try {
        $hashed_pass = password_hash($reset_pass, PASSWORD_DEFAULT);
        $sql = "UPDATE user_table SET login_pass = :reset_pass WHERE id = :id";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':id', $id, PDO::PARAM_INT);
        $stmh->bindValue(':reset_pass', $hashed_pass, PDO::PARAM_STR);
        $stmh->execute();
        return true;
    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
        return false;
    }
}
//**************************************************
// 利用規約への同意状況を確認
//**************************************************
function hasTosAgreed(int $id): bool {
    $pdo = db_connect();
    try {
        $sSql = "SELECT tos_agreed_at FROM user_table WHERE id = :id";
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':id', $id, PDO::PARAM_INT);
        $stmh->execute();
        $value = $stmh->fetchColumn();
        return $value !== false && $value !== null;
    } catch (PDOException $Exception) {
        die('実行エラー（' . __FUNCTION__."）：".$Exception->getMessage()."<br />");
    }
}
//**************************************************
// 利用規約への同意を記録
//**************************************************
function agreeToS(int $id): bool {
    $pdo = db_connect();
    try {
        $sSql = "UPDATE user_table SET tos_agreed_at = :tos_agreed_at WHERE id = :id";
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':tos_agreed_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmh->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmh->execute();
    } catch (PDOException $Exception) {
        die('実行エラー（' . __FUNCTION__."）：".$Exception->getMessage()."<br />");
    }
}

####################################################################################
### クラス関連
####################################################################################
//**************************************************
// 有効なクラス（is_active = 1）を全件取得
//**************************************************
function getActiveClasses(){
    $array_result = array();
    $pdo = db_connect();
    try {
        $sSql = "SELECT id, class_name FROM class_table WHERE is_active = 1";
        $stmh = $pdo->prepare($sSql);
        $stmh->execute();
        $array_result = $stmh->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }

    return $array_result;
}
//**************************************************
// クラス名を取得
//**************************************************
function getClassName(int $class_id){
    $pdo = db_connect();
    try {
        $sSql = "SELECT class_name FROM class_table WHERE id = :class_id";
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':class_id', $class_id, PDO::PARAM_INT);
        $stmh->execute();

        return $stmh->fetchColumn();
    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }
}

####################################################################################
### チャプター関連
####################################################################################
//**************************************************
// チャプター名を取り出す
//**************************************************
function getChapterNames(int $chapter_id)
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
        dbError(__FUNCTION__, $Exception);
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
        $sSql  = "SELECT c.id, c.name AS chapter_name, c.is_published AS c_is_pub, s.id AS section_id, s.name AS section_name, s.is_published AS s_is_pub ";
        $sSql .= "FROM chapter_table c ";
        $sSql .= "JOIN section_table s ON c.id = s.chapter_id ";
        $sSql .= "ORDER BY c.order_number, s.order_number";

        $stmh = $pdo->prepare($sSql);
        $stmh->execute();
        $rows = $stmh->fetchAll(PDO::FETCH_ASSOC);

        // チャプターごとにセクションをまとめる
        $array_result = [];
        foreach ($rows as $row) {
            $chapter_id = $row['id'];

            if (!isset($array_result[$chapter_id])) {
                $array_result[$chapter_id] = [
                    'id'           => $chapter_id,
                    'chapter_name' => $row['chapter_name'],
                    'chapter_published' => $row['c_is_pub'],
                    'sections' => [],
                ];
            }
            $array_result[$chapter_id]['sections'][] = [
                'id'     => $row['section_id'],
                'name'   => $row['section_name'],
                'section_published' => $row['s_is_pub']
            ];
        }

        $array_result = array_values($array_result);

    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }

    return $array_result;
}
//**************************************************
// チャプターごとの進捗状況取得
//**************************************************
function getChapterProgressList(int $user_id)
{
    $result = array();
    if (empty($user_id)) {
        return $result;
    }

    $pdo = db_connect();
    try {
        $sSql  = "SELECT c.id AS chapter_id, ";
        $sSql .= "COUNT(q.id) AS total, ";
        $sSql .= "SUM(CASE WHEN uqs.status = 'correct' THEN 1 ELSE 0 END) AS correct_count, ";
        $sSql .= "SUM(CASE WHEN uqs.status = 'wrong'   THEN 1 ELSE 0 END) AS wrong_count ";
        $sSql .= "FROM chapter_table c ";
        $sSql .= "JOIN section_table s ON s.chapter_id = c.id ";
        $sSql .= "JOIN question_table q ON q.section_id = s.id ";
        $sSql .= "LEFT JOIN user_question_status uqs ";
        $sSql .= "  ON uqs.question_id = q.id AND uqs.user_id = :user_id ";
        $sSql .= "GROUP BY c.id";

        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmh->execute();

        foreach ($stmh->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $result[$row['chapter_id']] = [
                'total'         => (int)$row['total'],
                'correct_count' => (int)$row['correct_count'],
                'wrong_count'   => (int)$row['wrong_count'],
            ];
        }

    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }

    return $result;
}

####################################################################################
### セクション関連
####################################################################################
//**************************************************
// セクション名を取り出す（ファイルパス用）
//**************************************************
function getSectionNames(int $section_id)
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
        dbError(__FUNCTION__, $Exception);
    }

    return $array_result;
}
//**************************************************
// チャプターIdに対応する、セクション名（クエスチョンId付き）を取り出す
//**************************************************
function getSectionsWithQuestions(int $chapter_id)
{
    $array_result = array();
    $pdo = db_connect();

    try {
        $sSql  = "SELECT s.id, s.name, s.is_published AS is_pub, q.id AS question_id ";
        $sSql .= "FROM section_table s ";
        $sSql .= "JOIN question_table q ON s.id = q.section_id AND s.chapter_id = :chapter_id ";
        $sSql .= "ORDER BY s.order_number, q.order_number";

        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':chapter_id', $chapter_id, PDO::PARAM_INT);
        $stmh->execute();
        $rows = $stmh->fetchAll(PDO::FETCH_ASSOC);

        // セクションごとに問題IDをまとめる
        $array_result = [];
        foreach ($rows as $row) {
            $section_id = $row['id'];

            if (!isset($array_result[$section_id])) {
                $array_result[$section_id] = [
                    'id'   => $section_id,
                    'name'         => $row['name'],
                    'is_published' => $row['is_pub'],
                    'question_ids' => []
                ];
            }

            $array_result[$section_id]['question_ids'][] = $row['question_id'];
        }

        $array_result = array_values($array_result);

    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }

    return $array_result;
}
//**************************************************
// 数理カテゴリー（section_category）を全件取得
// 戻り値: [section_id => [suuri, ...], ...]
//**************************************************
function getSectionCategories()
{
    $result = [];
    $pdo = db_connect();
    try {
        $sSql = "SELECT section_id, suuri FROM section_category ORDER BY id";
        $stmh = $pdo->prepare($sSql);
        $stmh->execute();
        $rows = $stmh->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $result[(int)$row['section_id']][] = $row['suuri'];
        }
    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }
    return $result;
}
//**************************************************
// セクションごとの進捗状況取得
//**************************************************
function getSectionProgressList(int $user_id)
{
    $result = array();
    if (empty($user_id)) {
        return $result;
    }

    $pdo = db_connect();
    try {
        $sSql  = "SELECT s.id AS section_id, ";
        $sSql .= "COUNT(q.id) AS total, ";
        $sSql .= "SUM(CASE WHEN uqs.status = 'correct' THEN 1 ELSE 0 END) AS correct_count, ";
        $sSql .= "SUM(CASE WHEN uqs.status = 'wrong'   THEN 1 ELSE 0 END) AS wrong_count ";
        $sSql .= "FROM section_table s ";
        $sSql .= "JOIN question_table q ON q.section_id = s.id ";
        $sSql .= "LEFT JOIN user_question_status uqs ";
        $sSql .= "  ON uqs.question_id = q.id AND uqs.user_id = :user_id ";
        $sSql .= "GROUP BY s.id";

        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmh->execute();

        foreach ($stmh->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $result[$row['section_id']] = [
                'total'         => (int)$row['total'],
                'correct_count' => (int)$row['correct_count'],
                'wrong_count'   => (int)$row['wrong_count'],
            ];
        }

    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }

    return $result;
}

####################################################################################
### クエスチョン関連
####################################################################################
//**********************************************************************************
// 選択肢数、正答の情報を取得
//**********************************************************************************
function getQuestions(int $question_id)
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
        dbError(__FUNCTION__, $Exception);
    }

    return $array_result;
}
//**********************************************************************************
// 問題id配列
//**********************************************************************************
function getQuestionIds(int $section_id){
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
        dbError(__FUNCTION__, $Exception);
    }

    return $array_result;
}
//**********************************************************************************
// 問題数カウント
//**********************************************************************************
function getQuestionCount(int $section_id){
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
        dbError(__FUNCTION__, $Exception);
    }

    return $result;
}
//**************************************************
// 回答状況(done/wrong)を取得
//**************************************************
function getQuestionStatuses(int $user_id, array $question_ids)
{
    $result = array();
    if (empty($user_id) || empty($question_ids)) {
        return $result;
    }

    $pdo = db_connect();
    try {
        // IN句のプレースホルダーを動的に生成
        $placeholders = implode(',', array_fill(0, count($question_ids), '?'));
        $sSql  = "SELECT question_id, status ";
        $sSql .= "FROM user_question_status ";
        $sSql .= "WHERE user_id = ? ";
        $sSql .= "AND question_id IN ($placeholders)";

        $stmh = $pdo->prepare($sSql);
        // 第一引数にuser_id、残りにquestion_idsを展開して渡す
        $stmh->execute(array_merge([$user_id], $question_ids));

        foreach ($stmh->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $result[$row['question_id']] = $row['status'];
        }

    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }

    return $result;
}
//**************************************************
// セクション内での問題の表示番号を取得（order_number順での並び位置）
//**************************************************
function getQuestionNumberInSection(int $section_id, int $question_id)
{
    $question_ids = getQuestionIds($section_id);
    $index = array_search($question_id, $question_ids);

    return $index === false ? null : $index + 1;
}
//********************************************************************************************
// 回答結果を記録
//********************************************************************************************
function updateQuestionStatus(int $user_id, int $question_id, bool $is_correct)
{
    $status = $is_correct ? 'correct' : 'wrong';
    $pdo = db_connect();
    try {
        $sSql  = "INSERT INTO user_question_status (user_id, question_id, status, attempt_count, last_answered_at) ";
        $sSql .= "VALUES (:user_id, :question_id, :status, 1, NOW()) ";
        $sSql .= "ON DUPLICATE KEY UPDATE ";
        $sSql .= "status = VALUES(status), ";
        $sSql .= "attempt_count = attempt_count + 1, ";
        $sSql .= "last_answered_at = NOW()";

        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':user_id',     $user_id,     PDO::PARAM_INT);
        $stmh->bindValue(':question_id', $question_id, PDO::PARAM_INT);
        $stmh->bindValue(':status',      $status,      PDO::PARAM_STR);
        $stmh->execute();

    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }
}
//********************************************************************************************
// 初回回答を記録（同じ問題への2回目以降の回答は無視）
//********************************************************************************************
function insertFirstAnswer(int $user_id, int $question_id, bool $is_correct)
{
    $pdo = db_connect();
    try {
        $sSql  = "INSERT IGNORE INTO first_answers (user_id, question_id, is_correct, answered_at) ";
        $sSql .= "VALUES (:user_id, :question_id, :is_correct, NOW())";

        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':user_id',     $user_id,        PDO::PARAM_INT);
        $stmh->bindValue(':question_id', $question_id,    PDO::PARAM_INT);
        $stmh->bindValue(':is_correct',  (int)$is_correct, PDO::PARAM_INT);
        $stmh->execute();

    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }
}
//********************************************************************************************
// 正答率の集計（first_answersから回答数と正解数を取得）
//********************************************************************************************
function getQuestionAccuracy(int $question_id)
{
    $result = ['answer_count' => 0, 'correct_count' => 0];
    $pdo = db_connect();
    try {
        $sSql  = "SELECT COUNT(*) AS answer_count, COALESCE(SUM(is_correct), 0) AS correct_count ";
        $sSql .= "FROM first_answers ";
        $sSql .= "WHERE question_id = :question_id";

        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':question_id', $question_id, PDO::PARAM_INT);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);

        $result['answer_count']  = (int)$row['answer_count'];
        $result['correct_count'] = (int)$row['correct_count'];

    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }

    return $result;
}

####################################################################################
### 解説
####################################################################################
//**************************************************
// 解説IDを取得
//**************************************************
function getExplanationIds(int $question_id)
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
        dbError(__FUNCTION__, $Exception);
    }

    return $array_result;
}

####################################################################################
### math_nav関連
####################################################################################
//********************************************************************************************
// 数学ナビのリンク名とURLを取得
//********************************************************************************************
function getNavs(int $question_id)
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
        dbError(__FUNCTION__, $Exception);
    }

    return $array_result;
}

####################################################################################
### ブックマーク関連
####################################################################################
//********************************************************************************************
// ブックマーク済みかどうか判定
//********************************************************************************************
function isBookmarked(int $user_id, int $question_id)
{
    $pdo = db_connect();
    try {
        $sSql = "SELECT 1 FROM bookmark_table WHERE user_id = :user_id AND question_id = :question_id";

        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmh->bindValue(':question_id', $question_id, PDO::PARAM_INT);
        $stmh->execute();

        return (bool)$stmh->fetchColumn();

    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }
}
//********************************************************************************************
// ブックマーク登録
//********************************************************************************************
function insertBookmark(int $user_id, int $question_id)
{
    $pdo = db_connect();
    try {
        $sSql  = "INSERT IGNORE INTO bookmark_table (user_id, question_id, created_at) ";
        $sSql .= "VALUES (:user_id, :question_id, NOW())";

        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmh->bindValue(':question_id', $question_id, PDO::PARAM_INT);
        $stmh->execute();

    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }
}
//********************************************************************************************
// ブックマーク解除
//********************************************************************************************
function deleteBookmark(int $user_id, int $question_id)
{
    $pdo = db_connect();
    try {
        $sSql = "DELETE FROM bookmark_table WHERE user_id = :user_id AND question_id = :question_id";

        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmh->bindValue(':question_id', $question_id, PDO::PARAM_INT);
        $stmh->execute();

    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }
}
//********************************************************************************************
// ユーザーのブックマーク一覧（章・セクション情報付き）を取得
//********************************************************************************************
function getUserBookmarks(int $user_id)
{
    $array_result = array();
    $pdo = db_connect();
    try {
        // 並び替えはクライアント側(JS)で行うため、各順序キーも一緒に取得しておく
        $sSql  = "SELECT q.id AS question_id, q.section_id, q.order_number AS question_order, ";
        $sSql .= "s.name AS section_name, s.order_number AS section_order, ";
        $sSql .= "c.id AS chapter_id, c.name AS chapter_name, c.order_number AS chapter_order, ";
        $sSql .= "b.created_at ";
        $sSql .= "FROM bookmark_table b ";
        $sSql .= "JOIN question_table q ON b.question_id = q.id ";
        $sSql .= "JOIN section_table s ON q.section_id = s.id ";
        $sSql .= "JOIN chapter_table c ON s.chapter_id = c.id ";
        $sSql .= "WHERE b.user_id = :user_id ";
        $sSql .= "ORDER BY c.order_number, s.order_number, q.order_number";

        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmh->execute();
        $array_result = $stmh->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }

    return $array_result;
}

####################################################################################
### その他（DBに依存しない共通処理）
####################################################################################
//**************************************************
// 利用規約（ToS.md）を簡易HTMLへ変換
//**************************************************
function renderTosHtml(string $markdown): string {
    $lines = preg_split('/\r\n|\r|\n/', $markdown);
    $html = '';
    $listType = null; // 'ul' | 'ol' | null

    $closeList = function () use (&$html, &$listType) {
        if ($listType !== null) {
            $html .= "</{$listType}>\n";
            $listType = null;
        }
    };

    foreach ($lines as $line) {
        $trimmed = trim($line);

        if ($trimmed === '' || preg_match('/^# /', $trimmed)) {
            // 空行、および先頭の大見出し（呼び出し側で別途表示するため）は読み飛ばす
            $closeList();
            continue;
        }

        if (preg_match('/^## (.+)/u', $trimmed, $m)) {
            $closeList();
            $html .= '<h3>' . htmlspecialchars($m[1], ENT_QUOTES, 'UTF-8') . "</h3>\n";
        } elseif (preg_match('/^- (.+)/u', $trimmed, $m)) {
            if ($listType !== 'ul') { $closeList(); $html .= "<ul>\n"; $listType = 'ul'; }
            $html .= '<li>' . htmlspecialchars($m[1], ENT_QUOTES, 'UTF-8') . "</li>\n";
        } elseif (preg_match('/^\d+\.\s+(.+)/u', $trimmed, $m)) {
            if ($listType !== 'ol') { $closeList(); $html .= "<ol>\n"; $listType = 'ol'; }
            $html .= '<li>' . htmlspecialchars($m[1], ENT_QUOTES, 'UTF-8') . "</li>\n";
        } else {
            $closeList();
            $html .= '<p>' . htmlspecialchars($trimmed, ENT_QUOTES, 'UTF-8') . "</p>\n";
        }
    }
    $closeList();

    return $html;
}
//**************************************************
// htmlへの文字列出力用
//**************************************************
function V2H(string $str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
