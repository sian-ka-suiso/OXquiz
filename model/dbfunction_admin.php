<?php
require_once __DIR__ . '/dbfunction.php';

####################################################################################
// 管理画面（ctrl_admin配下）専用の関数
####################################################################################

####################################################################################
### 認証
####################################################################################
//**************************************************
// 管理者ログインチェック
//**************************************************
function admLoginCheck($email = "", $login_pass = ""){
    $pdo = db_connect();

    try {
        $sSql = "SELECT id, login_pass FROM user_table WHERE email = :email AND is_admin = 1";
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
// 教員ログインチェック
//**************************************************
function teacherLoginCheck($email = "", $login_pass = ""){
    $pdo = db_connect();

    try {
        $sSql = "SELECT id, login_pass FROM user_table WHERE email = :email AND is_teacher = 1";
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

####################################################################################
### ユーザー管理
####################################################################################
//**************************************************
// 全ユーザーの情報取得
//**************************************************
function getUsers(){
    $pdo = db_connect();
    try {
        $sSql = "SELECT id, email, login_pass, user_name, created_at, update_at, is_admin FROM user_table";
        $stmh = $pdo->prepare($sSql);
        $stmh->execute();
        return $stmh->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        error_log("PDO Error in " . __FUNCTION__ . ": " . $Exception->getMessage());
        return [];
    }
}
//**************************************************
// ユーザー更新（管理ページ用）
//**************************************************
function updateUser(int $id, int $is_admin) {
    $pdo = db_connect();
    try {
        $sql = "UPDATE user_table SET is_admin = :is_admin WHERE id = :id";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':id', $id, PDO::PARAM_STR);
        $stmh->bindValue(':is_admin', $is_admin, PDO::PARAM_STR);
        $stmh->execute();
        return true;
    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
        return false;
    }
}
//**************************************************
// ユーザー削除（管理ページ用）
//**************************************************
function deleteUser(int $id){
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
### チャプター管理
####################################################################################
//**************************************************
// チャプター名重複確認
//**************************************************
function checkChapterName($name) {
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM chapter_table WHERE name = :name");
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count > 0; // true = 存在する
}
//**************************************************
// フォルダーー名重複確認
//**************************************************
function checkFolder($folder_name) {
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM chapter_table WHERE folder_name = :folder_name");
    $stmt->bindValue(':folder_name', $folder_name, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count > 0; // true = 存在する
}
//**************************************************
// オーダー番号重複確認
//**************************************************
function checkOrder($order_number) {
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM chapter_table WHERE order_number = :order_number");
    $stmt->bindValue(':order_number', $order_number, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count > 0; // true = 存在する
}
//**************************************************
// チャプター追加
//**************************************************
function insertChapter($name, $folder_name, $order_number, $is_published) {
    $pdo = db_connect();
    try {
        $sql = "INSERT INTO chapter_table (name, folder_name, order_number, is_published) VALUES (:name, :folder_name, :order_number, :is_published)";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':name',  $name,  PDO::PARAM_STR);
        $stmh->bindValue(':folder_name',  $folder_name,  PDO::PARAM_STR);
        $stmh->bindValue(':order_number',  $order_number,  PDO::PARAM_STR);
        $stmh->bindValue(':is_published',  $is_published,  PDO::PARAM_STR);
        $stmh->execute();
        return true;
    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
        return false;
    }
}
//**************************************************
// チャプターテーブルの全情報を取り出す
//**************************************************
function getChapterAll(){
    $pdo = db_connect();
    try {
        $sSql = "SELECT * FROM chapter_table";
        $stmh = $pdo->prepare($sSql);
        $stmh->execute();
        return $stmh->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
        return [];
    }
}
//**************************************************
// チャプター更新（管理ページ用）
//**************************************************
function updateChapter($id, $name, $folder_name, $order_number, $is_published) {
    $pdo = db_connect();
    try {
        $sql = "UPDATE chapter_table SET name = :name, folder_name = :folder_name, order_number = :order_number, is_published = :is_published WHERE id = :id";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':id', $id, PDO::PARAM_STR);
        $stmh->bindValue(':name', $name, PDO::PARAM_STR);
        $stmh->bindValue(':folder_name', $folder_name, PDO::PARAM_STR);
        $stmh->bindValue(':order_number', $order_number, PDO::PARAM_STR);
        $stmh->bindValue(':is_published', $is_published, PDO::PARAM_STR);
        $stmh->execute();
        return true;
    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
        return false;
    }
}
//**************************************************
// チャプター削除（管理ページ用）
//**************************************************
function deleteChapter($id){
    $pdo = db_connect();
    try {
        $sql = "DELETE FROM chapter_table WHERE id = :id";
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
### セクション管理
####################################################################################
//**************************************************
// チャプターID重複確認
//**************************************************
function checkSectionChap($chapter_id) {
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM chapter_table WHERE id = :chapter_id");
    $stmt->bindValue(':chapter_id', $chapter_id, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count > 0; // true = 存在する
}
//**************************************************
// チャプター名重複確認
//**************************************************
function checkSectionName($name) {
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM section_table WHERE name = :name");
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count > 0; // true = 存在する
}
//**************************************************
// フォルダーー名重複確認
//**************************************************
function checkSectionFolder($folder_name) {
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM section_table WHERE folder_name = :folder_name");
    $stmt->bindValue(':folder_name', $folder_name, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count > 0; // true = 存在する
}
//**************************************************
// オーダー番号重複確認
//**************************************************
function checkSectionOrder($order_number) {
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM section_table WHERE order_number = :order_number");
    $stmt->bindValue(':order_number', $order_number, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count > 0; // true = 存在する
}
//**************************************************
// セクション追加
//**************************************************
function insertSection($chapter_id, $name, $folder_name, $order_number, $is_published) {
    $pdo = db_connect();
    try {
        $sql = "INSERT INTO section_table (chapter_id, name, folder_name, order_number, is_published) VALUES (:chapter_id, :name, :folder_name, :order_number, :is_published)";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':chapter_id', $chapter_id, PDO::PARAM_STR);
        $stmh->bindValue(':name',  $name,  PDO::PARAM_STR);
        $stmh->bindValue(':folder_name',  $folder_name,  PDO::PARAM_STR);
        $stmh->bindValue(':order_number',  $order_number,  PDO::PARAM_STR);
        $stmh->bindValue(':is_published',  $is_published,  PDO::PARAM_STR);
        $stmh->execute();
        return true;
    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
        return false;
    }
}
//**************************************************
// セクションテーブルの全情報を取り出す
//**************************************************
function getSectionAll(){
    $pdo = db_connect();
    try {
        $sSql = "SELECT * FROM section_table";
        $stmh = $pdo->prepare($sSql);
        $stmh->execute();
        return $stmh->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
        return [];
    }
}
//**************************************************
// セクション更新（管理ページ用）
//**************************************************
function updateSection($id, $chapter_id, $name, $folder_name, $order_number, $is_published) {
    $pdo = db_connect();
    try {
        $sql = "UPDATE section_table SET chapter_id = :chapter_id, name = :name, folder_name = :folder_name, order_number = :order_number, is_published = :is_published WHERE id = :id";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':id', $id, PDO::PARAM_STR);
        $stmh->bindValue(':chapter_id', $chapter_id, PDO::PARAM_STR);
        $stmh->bindValue(':name', $name, PDO::PARAM_STR);
        $stmh->bindValue(':folder_name', $folder_name, PDO::PARAM_STR);
        $stmh->bindValue(':order_number', $order_number, PDO::PARAM_STR);
        $stmh->bindValue(':is_published', $is_published, PDO::PARAM_STR);
        $stmh->execute();
        return true;
    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
        return false;
    }
}
//**************************************************
// セクション削除（管理ページ用）
//**************************************************
function deleteSection($id){
    $pdo = db_connect();
    try {
        $sql = "DELETE FROM section_table WHERE id = :id";
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
### ダッシュボード
####################################################################################
//**************************************************
// 章、節、問の全情報を取り出す
//**************************************************
function getAll(){
    $pdo = db_connect();
    try {
        $sSql  = "SELECT * ";
        $sSql .= "FROM chapter_table c ";
        $sSql .= "LEFT JOIN section_table s ON s.chapter_id = c.id ";
        $sSql .= "LEFT JOIN question_table q ON q.section_id = s.id";
        $stmh = $pdo->prepare($sSql);
        $stmh->execute();
        return $stmh->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
        return [];
    }
}
?>
