<?php
require_once __DIR__ . '/dbfunction.php';

####################################################################################
// 管理画面（ctrl_admin配下）専用の関数
####################################################################################

####################################################################################
### 認証
####################################################################################
//**************************************************
// 管理画面ログインチェック（管理者・教員共通）
// 成功時は id と権限フラグ（is_admin / is_teacher）を返す
//**************************************************
function staffLoginCheck($email = "", $login_pass = ""){
    $pdo = db_connect();

    try {
        $sSql = "SELECT id, login_pass, is_admin, is_teacher FROM user_table WHERE email = :email AND (is_admin = 1 OR is_teacher = 1)";
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':email', $email, PDO::PARAM_STR);
        $stmh->execute();
        $user = $stmh->fetch(PDO::FETCH_ASSOC);

        if ($user !== false) {
            $staff = [
                'id'         => $user['id'],
                'is_admin'   => (int)$user['is_admin'],
                'is_teacher' => (int)$user['is_teacher'],
            ];

            if (password_verify($login_pass, $user['login_pass'])) {
                return $staff;
            }

            // 移行期間中の平文パスワードとの一致も許可し、一致した場合はハッシュ化して保存し直す
            if ($login_pass === $user['login_pass']) {
                $newHash = password_hash($login_pass, PASSWORD_DEFAULT);
                $updateSql = "UPDATE user_table SET login_pass = :new_pass WHERE id = :id";
                $updateStmh = $pdo->prepare($updateSql);
                $updateStmh->bindValue(':new_pass', $newHash, PDO::PARAM_STR);
                $updateStmh->bindValue(':id', $user['id'], PDO::PARAM_INT);
                $updateStmh->execute();
                return $staff;
            }
        }
    } catch (PDOException $Exception) {
        dbError(__FUNCTION__, $Exception);
    }

    return false; // ユーザーがいない、権限がない、またはパスワード不一致
}

####################################################################################
### 権限表示
####################################################################################
//**************************************************
// 権限フラグから表示用のロール（ラベルとバッジのクラス）を作る
// $user には is_admin / is_teacher が含まれていること
//**************************************************
function getRoleLabels(array $user): array {
    $roles = [];
    if ($user['is_admin'] == 1) { $roles[] = ['label' => '管理者', 'class' => 'badge-admin']; }
    if ($user['is_teacher'] == 1) { $roles[] = ['label' => '教員', 'class' => 'badge-teacher']; }
    if (empty($roles)) { $roles[] = ['label' => '一般', 'class' => 'badge-user']; }
    return $roles;
}

####################################################################################
### ユーザー管理
####################################################################################
//**************************************************
// 全ユーザーの情報取得（検索・並び替え対応）
// $search_id : ID検索（空なら条件なし）
// $role      : ロール検索 'admin' / 'teacher' / 'general'（空なら条件なし）
// $sort      : 並び順 'id_asc' / 'id_desc'
//**************************************************
function getUsers($search_id = "", $role = "", $sort = "id_asc"){
    $pdo = db_connect();
    try {
        $where = [];
        $params = [];

        if ($search_id !== "") {
            $where[] = "id = :id";
            $params[':id'] = (int)$search_id;
        }

        if ($role === "admin") {
            $where[] = "is_admin = 1";
        } elseif ($role === "teacher") {
            $where[] = "is_teacher = 1";
        } elseif ($role === "general") {
            $where[] = "is_admin = 0 AND is_teacher = 0";
        }

        // ORDER BY にはプレースホルダーが使えないため、決められた候補からのみ選ぶ
        // （候補にない値が来た場合はID昇順にする）
        $sortList = [
            'id_asc'          => 'id ASC',
            'id_desc'         => 'id DESC',
        ];
        $orderBy = isset($sortList[$sort]) ? $sortList[$sort] : $sortList['id_asc'];

        $sSql = "SELECT id, email, login_pass, user_name, created_at, update_at, is_admin, is_teacher, class_id FROM user_table";
        if (!empty($where)) {
            $sSql .= " WHERE " . implode(" AND ", $where);
        }
        $sSql .= " ORDER BY " . $orderBy;

        $stmh = $pdo->prepare($sSql);
        foreach ($params as $key => $value) {
            $stmh->bindValue($key, $value, PDO::PARAM_INT);
        }
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
function updateUser(int $id, int $is_admin, int $is_teacher) {
    $pdo = db_connect();
    try {
        $sql = "UPDATE user_table SET is_admin = :is_admin, is_teacher = :is_teacher WHERE id = :id";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':id', $id, PDO::PARAM_STR);
        $stmh->bindValue(':is_admin', $is_admin, PDO::PARAM_STR);
        $stmh->bindValue(':is_teacher', $is_teacher, PDO::PARAM_STR);
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
// チャプター一覧取得（検索・並び替え付き、管理ページ用）
//**************************************************
function getChapters($search_id = "", $sort = "order_asc"){
    $pdo = db_connect();
    try {
        $where = [];
        $params = [];

        if ($search_id !== "") {
            $where[] = "id = :id";
            $params[':id'] = (int)$search_id;
        }

        // ORDER BY にはプレースホルダーが使えないため、決められた候補からのみ選ぶ
        // （候補にない値が来た場合は表示順の昇順にする）
        $sortList = [
            'order_asc'  => 'order_number ASC, id ASC',
            'order_desc' => 'order_number DESC, id DESC',
            'id_asc'     => 'id ASC',
            'id_desc'    => 'id DESC',
        ];
        $orderBy = isset($sortList[$sort]) ? $sortList[$sort] : $sortList['order_asc'];

        $sSql = "SELECT * FROM chapter_table";
        if (!empty($where)) {
            $sSql .= " WHERE " . implode(" AND ", $where);
        }
        $sSql .= " ORDER BY " . $orderBy;

        $stmh = $pdo->prepare($sSql);
        foreach ($params as $key => $value) {
            $stmh->bindValue($key, $value, PDO::PARAM_INT);
        }
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
function checkSectionOrder($order_number, $chapter_id) {
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM section_table WHERE order_number = :order_number AND chapter_id = :chapter_id");
    $stmt->bindValue(':order_number', $order_number, PDO::PARAM_STR);
    $stmt->bindValue(':chapter_id', $chapter_id, PDO::PARAM_STR);
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
// セクション一覧取得（検索・並び替え付き、管理ページ用）
//**************************************************
function getSections($search_id = "", $sort = "order_asc"){
    $pdo = db_connect();
    try {
        $where = [];
        $params = [];

        if ($search_id !== "") {
            $where[] = "s.id = :id";
            $params[':id'] = (int)$search_id;
        }

        // ORDER BY にはプレースホルダーが使えないため、決められた候補からのみ選ぶ
        // （候補にない値が来た場合は表示順の昇順にする）
        // 表示順はユーザー画面と同じく「チャプターの表示順 → セクションの表示順」
        $sortList = [
            'order_asc'  => 'c.order_number ASC, s.order_number ASC, s.id ASC',
            'order_desc' => 'c.order_number DESC, s.order_number DESC, s.id DESC',
            'id_asc'     => 's.id ASC',
            'id_desc'    => 's.id DESC',
        ];
        $orderBy = isset($sortList[$sort]) ? $sortList[$sort] : $sortList['order_asc'];

        $sSql = "SELECT s.* FROM section_table s ";
        $sSql .= "LEFT JOIN chapter_table c ON c.id = s.chapter_id";
        if (!empty($where)) {
            $sSql .= " WHERE " . implode(" AND ", $where);
        }
        $sSql .= " ORDER BY " . $orderBy;

        $stmh = $pdo->prepare($sSql);
        foreach ($params as $key => $value) {
            $stmh->bindValue($key, $value, PDO::PARAM_INT);
        }
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
