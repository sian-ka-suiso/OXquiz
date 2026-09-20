# よくある間違い〇✕クイズ - 要件定義書

## 1. プロジェクト概要

高校〜大学数学においてよくある誤答を題材にした、選択式（〇✕）クイズWebアプリケーション。大学の授業内で履修者の学習目的として利用される。

一般ユーザー（学生）向けの学習機能に加えて、教員・管理者向けの管理画面（章・節・ユーザーの追加/編集/削除）を備える。

---

## 2. 技術スタック

| 項目 | 内容 |
|------|------|
| 言語 | PHP, HTML, CSS, JavaScript |
| アーキテクチャ | MVC（Model / View / Controller） |
| ディレクトリ構成 | `ctrl/`・`ctrl_admin/`（Controller）, `view/`・`view_admin/`（View）, `model/`（Model）, `css/`, `js/`, `images/` |
| データベース | MySQL（PDO接続、プリペアドステートメント使用） |
| セッション管理 | PHP Session（一般ユーザーと管理者で別名のセッションキーを使用） |
| パスワード保存 | `password_hash()` によるハッシュ化（移行期間中は平文との比較にもフォールバック） |

---

## 3. ディレクトリ構成

```
Oxquiz_MVC/
├── ctrl/                       # 一般ユーザー向けコントローラー
│   ├── index.php               # ログイン／サインアウト／ゲストログイン
│   ├── sign_up.php             # 新規登録（STEP1：入力）
│   ├── sign_up_confirm.php     # 新規登録（STEP2：確認）
│   ├── tos_agree.php           # 利用規約への同意
│   ├── chapter.php             # 章一覧
│   ├── section.php             # 節一覧
│   ├── question.php            # 問題（回答・ブックマーク切替）
│   └── mypage.php              # マイページ（ユーザー情報・復習リスト）
├── ctrl_admin/                 # 管理者・教員向けコントローラー
│   ├── adm_login.php           # 管理者／教員ログイン
│   ├── admin.php               # 管理トップ
│   ├── chapter.php / chapter_insert.php / chapter_insert_confirm.php
│   │   / chapter_update.php / chapter_delete.php
│   ├── section.php / section_insert.php / section_insert_confirm.php
│   │   / section_delete.php     # ※section_update は未実装（view側は空ファイルのみ存在）
│   ├── user.php / user_update.php / user_delete.php
│   └── temp_pass.php           # 仮パスワード発行
├── view/                        # 一般ユーザー向けビュー
│   ├── parts/
│   │   ├── header.php          # 共通ヘッダー（ロゴ・マイページ・ログアウト等）
│   │   └── tos_modal.php       # 利用規約モーダル（ToS.mdを表示）
│   └── index.php, sign_up.php, sign_up_confirm.php, tos_agree.php,
│       chapter.php, section.php, question.php, mypage.php
├── view_admin/                  # 管理者向けビュー（ctrl_adminと1対1対応）
├── model/
│   ├── dbconnect.php           # DB接続関数・共通エラーハンドラ（dbError）
│   ├── db_config.php           # DB接続情報（git管理外。環境ごとに作成）
│   ├── db_config.sample.php    # ↑のテンプレート（git管理対象）
│   ├── dbfunction.php          # 一般ユーザー向けDB操作関数
│   └── dbfunction_admin.php    # 管理者向けDB操作関数（内部でdbfunction.phpを読み込む）
├── css/
│   ├── variables.css           # 共通CSS変数（フォント・カラーパレット等）
│   ├── common.css              # 全ページ共通スタイル（ヘッダー・パンくず等）
│   └── index.css, sign_up.css, tos_agree.css, tos_modal.css,
│       chapter.css, section.css, question.css, mypage.css
├── js/
│   ├── chapter.js              # 簡易／詳細モード切り替え
│   ├── section.js
│   ├── question.js             # 選択肢クリック時のフォーム送信
│   ├── mypage.js               # タブ切り替え
│   └── tos_modal.js            # 利用規約モーダルの開閉
├── images/
│   ├── other/                  # ロゴ等
│   └── {chapter_folder}/{section_folder}/{question_folder}/
│       ├── q.png                   # 問題画像
│       ├── opt1.png, opt2.png, ...  # 選択肢画像
│       └── exp1.png, exp2.png, ...  # 解説画像
├── ToS.md                       # 利用規約（Markdown。renderTosHtml()でHTML化）
└── .gitignore
```

---

## 4. ユーザー種別と権限

| 種別 | 判定 | できること |
|------|------|-----------|
| ゲスト | `$_SESSION['guest'] = true` | 問題閲覧・回答は可能。回答結果・ブックマークはDBに保存されない。利用規約同意も不要 |
| 一般ユーザー | `is_admin = 0` かつ `is_teacher = 0` | 回答記録・ブックマーク・マイページでの情報変更 |
| 教員 | `is_teacher = 1` | 一般ユーザーの機能に加え、管理者ログイン画面から`teacherLoginCheck()`でログイン可能（現状、管理画面のUI・権限は管理者と共通。役割ごとのUI出し分けは未実装） |
| 管理者 | `is_admin = 1` | 章・節・ユーザーの追加/編集/削除、仮パスワード発行 |

非公開（`is_published = 0`）の章・節は、管理者・教員以外には表示されない。

---

## 5. ページ構成とURL設計（一般ユーザー向け）

### 5-1. ログイン（`ctrl/index.php`）
- メールアドレス・パスワードでログイン、または「ゲストログイン」「新規登録」へ遷移
- ログアウトはセッション破棄で実装
- 利用規約未同意のユーザーは、ログイン後 `tos_agree.php` へリダイレクト

### 5-2. 新規登録（`ctrl/sign_up.php` → `sign_up_confirm.php` → `index.php`）
- STEP1：メールアドレス（大学メール想定）・パスワード（確認含む）・所属クラス・利用規約同意を入力し、重複チェック
- STEP2：入力内容の確認画面
- 確認画面から送信すると `ctrl/index.php` の登録処理（`insertUser()`）が実行される

### 5-3. 利用規約同意（`ctrl/tos_agree.php`）
- 未同意の場合、他ページへのアクセス前に強制的に表示
- 同意すると `tos_agreed_at` が記録される

### 5-4. Chapterページ（`ctrl/chapter.php`）
- 公開中の章をカードグリッドで一覧表示（簡易表示／進捗表示の切り替え可）
- 各カードに章名・数理カテゴリータグ・セクション名リスト・進捗リング（正解／不正解の割合）を表示
- クリックでSectionページへ遷移（GETパラメータ: `chapter_id`）

### 5-5. Sectionページ（`ctrl/section.php`）
- GETパラメータ: `chapter_id`
- 指定Chapter配下の公開中セクションと、各セクション内の問題を番号チップで表示
- チップの色で回答状況を可視化（緑：正解／赤：不正解／グレー：未回答）

### 5-6. Questionページ（`ctrl/question.php`）
- GETパラメータ: `chapter_id`, `section_id`, `question_id`, `qn`（問題番号）
- 問題文・選択肢・解説画像を表示。選択肢はページ表示ごとにシャッフルされる
- 選択肢クリックでPOST送信し、正誤判定・解説表示・回答記録（ゲストを除く）
- 正答率表示（一定回答数に達するまでは「集計中」）
- ブックマーク（復習リスト）の登録／解除
- KIT数学ナビゲーションの関連リンク表示、前後の問題への遷移

### 5-7. マイページ（`ctrl/mypage.php`）
- ユーザー情報（ユーザー名・メール・所属クラス・利用規約同意状況・登録日・最終更新日）の表示
- ユーザー名変更・パスワード再設定
- 復習リスト（ブックマークした問題）の一覧・並び替え

---

## 6. ページ構成とURL設計（管理者・教員向け）

### 6-1. 管理者ログイン（`ctrl_admin/adm_login.php`）
- メールアドレス・パスワードで認証（`admLoginCheck()` → 該当なければ `teacherLoginCheck()`）
- 一般ユーザー側とは別名のセッション（`admin_is_login` 等）で管理し、一般ユーザーのログイン状態のまま管理画面へ入れてしまう問題を防止

### 6-2. 管理トップ（`ctrl_admin/admin.php`）
- ユーザー・章（Chapter）・節（Section）管理へのリンク一覧

### 6-3. ユーザー管理（`ctrl_admin/user.php`）
- 全ユーザー一覧、管理者フラグの編集、削除、仮パスワード発行（`temp_pass.php`）

### 6-4. 章管理（`ctrl_admin/chapter*.php`）
- 一覧表示、新規追加（入力→確認の2ステップ）、更新、削除
- 追加時に章名・フォルダ名・order番号の重複チェックを実施

### 6-5. 節管理（`ctrl_admin/section*.php`）
- 章管理と同様の構成（一覧・追加・削除）
- 更新機能（`section_update`）は未実装（ビューファイルが空のまま存在）

---

## 7. データベース設計（現行スキーマ）

```sql
-- ユーザー
CREATE TABLE user_table (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  email         VARCHAR(255) NOT NULL UNIQUE,
  login_pass    VARCHAR(255) NOT NULL,
  user_name     VARCHAR(255) DEFAULT 'ログインユーザー',
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  update_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  is_admin      TINYINT(1) NOT NULL DEFAULT 0,
  is_teacher    TINYINT(1) NOT NULL DEFAULT 0,
  tos_agreed_at DATETIME DEFAULT NULL,
  class_id      INT NOT NULL
);

-- クラス（所属）
CREATE TABLE class_table (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  class_name  VARCHAR(255) NOT NULL,
  year        SMALLINT NOT NULL,
  semester    VARCHAR(10) NOT NULL,
  date        VARCHAR(10) NOT NULL,
  teacher_id  INT UNSIGNED NOT NULL,
  is_active   TINYINT(1) NOT NULL DEFAULT 0,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  update_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 章
CREATE TABLE chapter_table (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(255) NOT NULL,
  folder_name   VARCHAR(255) NOT NULL,
  order_number  INT NOT NULL,
  is_published  TINYINT(1) NOT NULL DEFAULT 0
);

-- 節
CREATE TABLE section_table (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  chapter_id    INT NOT NULL,
  name          VARCHAR(255) NOT NULL,
  folder_name   VARCHAR(255) NOT NULL,
  order_number  INT NOT NULL,
  is_published  TINYINT(1) NOT NULL DEFAULT 0
);

-- 節の数理カテゴリー（数理I／II／III等のタグ、1節に複数可）
CREATE TABLE section_category (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  section_id  INT NOT NULL,
  suuri       VARCHAR(255) NOT NULL
);

-- 問題
CREATE TABLE question_table (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  section_id      INT NOT NULL,
  order_number    INT NOT NULL,
  options         INT NOT NULL DEFAULT 2,
  correct_answer  INT DEFAULT 1,
  created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 解説
CREATE TABLE explanation_table (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  question_id INT NOT NULL,
  evaluation  INT NOT NULL DEFAULT 0,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 数学ナビリンク
CREATE TABLE mathnav_table (
  id        INT PRIMARY KEY,
  link_name VARCHAR(255) NOT NULL,
  link_url  VARCHAR(255) NOT NULL
);
CREATE TABLE question_mathnav (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  question_id   INT NOT NULL,
  mathnav_id    INT NOT NULL,
  order_number  INT NOT NULL
);

-- ブックマーク（復習リスト）
CREATE TABLE bookmark_table (
  user_id     INT NOT NULL,
  question_id INT NOT NULL,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, question_id)
);

-- 進捗サマリー（最新状態を保持・UIの高速表示用）
CREATE TABLE user_question_status (
  user_id          INT NOT NULL,
  question_id      INT NOT NULL,
  status           ENUM('correct', 'wrong', 'unanswered') DEFAULT 'unanswered',
  attempt_count    INT DEFAULT 0,
  last_answered_at DATETIME,
  PRIMARY KEY (user_id, question_id)
);

-- 初回回答のみ記録（初見正答率の算出用）
CREATE TABLE first_answers (
  user_id      INT NOT NULL,
  question_id  INT NOT NULL,
  is_correct   TINYINT(1) NOT NULL,
  answered_at  DATETIME,
  PRIMARY KEY (user_id, question_id)
);
```

`user_id` / `question_id` / `section_id` / `chapter_id` 等は、いずれもDB上の外部キー制約は張られておらず、アプリケーション側のロジックのみで整合性を保っている。

---

## 8. セッション設計

### 一般ユーザー側
| セッションキー | 内容 |
|--------------|------|
| `$_SESSION['is_login']` | ログイン済みなら `true`（ゲストもtrueになる） |
| `$_SESSION['user_id']` | ユーザーID |
| `$_SESSION['email']` | メールアドレス |
| `$_SESSION['guest']` | ゲストログインなら `true`、それ以外は未定義 |

ゲストユーザーは回答結果・ブックマークをDBに保存せず、進捗表示も行わない。

### 管理者側（一般ユーザー側と別名で管理）
| セッションキー | 内容 |
|--------------|------|
| `$_SESSION['admin_is_login']` | 管理者／教員としてログイン済みなら `true` |
| `$_SESSION['admin_user_id']` | ユーザーID |
| `$_SESSION['admin_email']` | メールアドレス |
| `$_SESSION['admin_role']` | `'admin'` または `'teacher'` |

ログイン成功時は `session_regenerate_id(true)` によりセッション固定化攻撃を防止している。

---

## 9. 主要な Model 関数

### 9-1. `model/dbfunction.php`（一般ユーザー向け・全ページ共通）

```php
// ユーザー
checkEmail(string $email)                 // メール重複確認
insertUser(string $email, string $login_pass, ?int $class_id, bool $tos_agreed)  // 新規登録
loginCheck($email, $login_pass)           // ログイン認証
getUserInfo(int $id)                      // ユーザー情報取得
ChangeUserName(int $id, string $change_name)
ResetLoginPass(int $id, string $reset_pass)
hasTosAgreed(int $id)                     // 利用規約同意状況
agreeToS(int $id)                         // 利用規約同意を記録

// クラス
getActiveClasses()
getClassName(int $class_id)

// Chapter
getChapterNames(int $chapter_id)
getChaptersWithSections()
getChapterProgressList(int $user_id)

// Section
getSectionNames(int $section_id)
getSectionsWithQuestions(int $chapter_id)
getSectionCategories()
getSectionProgressList(int $user_id)

// Question
getQuestions(int $question_id)
getQuestionIds(int $section_id)
getQuestionCount(int $section_id)
getQuestionStatuses(int $user_id, array $question_ids)
getQuestionNumberInSection(int $section_id, int $question_id)
updateQuestionStatus(int $user_id, int $question_id, bool $is_correct)
insertFirstAnswer(int $user_id, int $question_id, bool $is_correct)
getQuestionAccuracy(int $question_id)     // 正答率集計

// 解説・数学ナビ
getExplanationIds(int $question_id)
getNavs(int $question_id)

// ブックマーク
isBookmarked(int $user_id, int $question_id)
insertBookmark(int $user_id, int $question_id)
deleteBookmark(int $user_id, int $question_id)
getUserBookmarks(int $user_id)

// その他（DB非依存の共通処理）
renderTosHtml(string $markdown)           // ToS.md を簡易HTMLへ変換
V2H(string $str)                          // htmlspecialchars ラッパー
```

### 9-2. `model/dbfunction_admin.php`（管理画面専用。内部で dbfunction.php を読み込む）

```php
// 認証
admLoginCheck($email, $login_pass)
teacherLoginCheck($email, $login_pass)

// ユーザー管理
getUsers()
updateUser(int $id, int $is_admin)
deleteUser(int $id)

// チャプター管理
checkChapterName($name) / checkFolder($folder_name) / checkOrder($order_number)
insertChapter($name, $folder_name, $order_number, $is_published)
getChapterAll()
updateChapter($id, $name, $folder_name, $order_number, $is_published)
deleteChapter($id)

// セクション管理
checkSectionChap($chapter_id) / checkSectionName($name) / checkSectionFolder($folder_name) / checkSectionOrder($order_number)
insertSection($chapter_id, $name, $folder_name, $order_number, $is_published)
getSectionAll()
updateSection($id, $chapter_id, $name, $folder_name, $order_number, $is_published)  // ※呼び出し元は未実装
deleteSection($id)

// ダッシュボード
getAll()   // 章・節・問題の全件（LEFT JOIN）
```

---

## 10. スタイル・デザイン

共通のデザイントークン（フォント・カラーパレット・カード用の影や角丸など）は `css/variables.css` の CSS変数で一元管理している。個別ページのCSS（`chapter.css` 等）はこの変数を参照する形で実装されているため、配色やサイズを変更する場合は基本的に `variables.css` を編集する。

主なフォント：
- 見出し・数値表示（Chapter番号、正答率等）：JetBrains Mono / Noto Serif JP
- 本文・UI全般：Zen Kaku Gothic New / Noto Sans JP

---

## 11. 主要な処理フロー

### 11-1. 問題ページ（`ctrl/question.php`）
```
1. GETパラメータからchapter_id, section_id, question_id, qnを取得
2. DBから問題情報・選択肢数・正答・正答率・ユーザー情報を取得
3. 選択肢画像をシャッフル（初回表示時のみ。回答後は送信された順序を維持）
4. 回答フォームPOST時：
   a. 正誤判定・フィードバック文言を生成
   b. ゲストでなければDB保存
      - updateQuestionStatus()  → user_question_status を UPSERT
      - insertFirstAnswer()     → first_answers に INSERT IGNORE（初回のみ記録）
   c. $show_explanation = true にして解説を表示
5. ブックマーク切替フォームPOST時：登録／解除をDBに反映
6. ページレンダリング
```

### 11-2. 新規登録フロー
```
ctrl/sign_up.php（STEP1：入力・重複チェック）
  → セッションに保存 → sign_up_confirm.php（STEP2：確認表示）
    → ctrl/index.php（sign_up=trueで受信し insertUser() を実行）→ ログイン画面へ
```

### 11-3. 管理者による章・節の追加フロー
```
ctrl_admin/chapter_insert.php（STEP1：入力・重複チェック）
  → セッションに保存 → chapter_insert_confirm.php（確認表示）
    → ctrl_admin/chapter.php（chapter_insert=trueで受信し insertChapter() を実行）
```
（節の追加も同様の構成）

---

## 12. 画像ファイルのパス規則

```php
// 問題画像
"../images/{chapter_folder}/{section_folder}/{question_folder}/q.png"

// 選択肢画像（opt1.png, opt2.png, ...）
"../images/{chapter_folder}/{section_folder}/{question_folder}/opt{$i}.png"

// 解説画像（exp1.png, exp2.png, ...）
"../images/{chapter_folder}/{section_folder}/{question_folder}/exp{$j}.png"

// question_folderは5桁ゼロ埋め
$question_fname = sprintf("%05d", $question_id); // 例: "00003"
```

キャッシュバスター用に `filemtime()` を使ってクエリパラメータを付与する。
```php
$path = $path . "?v=" . filemtime($path);
```

---

## 13. 環境設定・秘密情報の管理

- DB接続情報は `model/db_config.php` に分離しており、**このファイルはgit管理対象外**（`.gitignore`で除外）。新しい環境でセットアップする場合は `model/db_config.sample.php` をコピーして値を設定する
- `model/dbconnect.php` の `APP_DEBUG` 定数で、DBエラー時の詳細表示（開発時）と一般向け文言（本番時）を切り替える
- パスワードは `password_hash()` でハッシュ化して保存。既存データとの互換のため、平文一致時のみ自動的にハッシュへ移行するロジックを`loginCheck()`系の関数に実装している

---

## 14. 既知の制約・今後の実装予定

- セクションの更新機能（`section_update`）はビューファイルのみ存在し、コントローラー・導線とも未実装
- 教員ロール（`is_teacher`）はログイン・セッション管理までは実装済みだが、管理画面のUI・操作権限は管理者と区別されていない（`admin_role` を今後のUI出し分けに使う想定）
- 正答数ランキング機能（`user_question_status` の集計で実現可能）
- 実績（`achievements`）タブはマイページにUIの枠のみ用意されており、内容は未実装
