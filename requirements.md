# よくある間違い〇✕クイズ - 要件定義書

## 1. プロジェクト概要

高校〜大学数学においてよくある誤答を題材にした、選択式（〇✕）クイズWebアプリケーション。大学の授業内で履修者の学習目的として利用される。

---

## 2. 技術スタック

| 項目 | 内容 |
|------|------|
| 言語 | PHP, HTML, CSS, JavaScript |
| アーキテクチャ | MVC（Model / View / Controller） |
| ディレクトリ構成 | `ctrl/`（Controller）, `view/`（View）, `model/`（Model）, `css/`, `js/`, `images/` |
| データベース | MySQL（PDO接続） |
| セッション管理 | PHP Session |

---

## 3. ディレクトリ構成

```
Oxquiz_MVC/
├── ctrl/
│   ├── chapter.php
│   ├── section.php
│   ├── question.php
│   └── ...（login, mypage, admin など）
├── view/
│   ├── chapter.html
│   ├── section.html
│   ├── question.html
│   └── ...
├── model/
│   ├── dbconnect.php   # DB接続関数
│   └── dbfunction.php  # DB操作関数群
├── css/
│   ├── common.css      # 全ページ共通スタイル
│   ├── chapter.css
│   ├── section.css
│   └── question.css
├── js/
│   ├── chapter.js
│   ├── section.js
│   └── question.js
└── images/
    ├── other/          # ロゴ等
    └── {chapter_folder}/{section_folder}/{question_folder}/
        ├── q.png           # 問題画像
        ├── opt1.png        # 選択肢画像
        ├── opt2.png
        └── exp1.png        # 解説画像
```

---

## 4. ページ構成とURL設計

### 4-1. Chapterページ（`ctrl/chapter.php`）
- 全Chapterをカードグリッドで一覧表示
- 各カードにChapter番号・章名・セクション名リスト・進捗バーを表示
- クリックでSectionページへ遷移（GETパラメータ: `chapter_id`）

### 4-2. Sectionページ（`ctrl/section.php`）
- GETパラメータ: `chapter_id`
- 指定ChapterのSection一覧を表示
- 各Section内の問題を番号ボタンで表示（Q1, Q2, ...）
- ボタンの色で回答状況を視覚化（緑：正解済み／赤：誤答／デフォルト：未回答）
- Chapter進捗バーを上部に表示
- 「Chapter一覧に戻る」ボタンあり

### 4-3. Questionページ（`ctrl/question.php`）
- GETパラメータ: `chapter_id`, `section_id`, `question_id`, `qn`（問題番号）
- 問題文・選択肢・解説はすべて画像で表示
- 選択肢をクリックでフォームをPOST送信し、正誤フィードバックを表示
- 解説画像を切り替えるボタンあり（`exp1.png`, `exp2.png`, ...）
- KIT数学ナビゲーションの関連リンクを表示
- 前の問題・次の問題ナビゲーションボタンあり
- 「Section一覧に戻る」ボタンあり
- 回答ステータスバッジ（正解済み／前回不正解）を表示

---

## 5. データベース設計

### 5-1. 既存テーブル（主要なもの）

```sql
-- ユーザー
user_table (id, email, login_pass, user_name, created_at, update_at, is_admin)

-- 章
chapter_table (id, name, folder_name, order_number, is_published)

-- 節
section_table (id, chapter_id, name, folder_name, order_number, is_published)

-- 問題
question_table (id, section_id, options, correct_answer, order_number)

-- 解説
explanation_table (id, question_id, order_number)

-- 数学ナビリンク
mathnav_table (id, link_name, link_url)
question_mathnav (id, question_id, mathnav_id, order_number)
```

### 5-2. 進捗管理テーブル（新規追加）

```sql
-- 進捗サマリー（最新状態を保持・UIの高速表示用）
CREATE TABLE user_question_status (
  user_id          INT NOT NULL,
  question_id      INT NOT NULL,
  status           ENUM('correct', 'wrong', 'unanswered') DEFAULT 'unanswered',
  attempt_count    INT DEFAULT 0,
  last_answered_at DATETIME,
  PRIMARY KEY (user_id, question_id),
  FOREIGN KEY (user_id)     REFERENCES user_table(id),
  FOREIGN KEY (question_id) REFERENCES question_table(id)
);

-- 初回回答のみ記録（初見正答率の算出用）
CREATE TABLE first_answers (
  user_id      INT NOT NULL,
  question_id  INT NOT NULL,
  is_correct   TINYINT(1) NOT NULL,
  answered_at  DATETIME,
  PRIMARY KEY (user_id, question_id),
  FOREIGN KEY (user_id)     REFERENCES user_table(id),
  FOREIGN KEY (question_id) REFERENCES question_table(id)
);
```

---

## 6. セッション設計

| セッションキー | 内容 |
|--------------|------|
| `$_SESSION['is_login']` | ログイン済みなら `true` |
| `$_SESSION['user_id']` | ユーザーID |
| `$_SESSION['email']` | メールアドレス |
| `$_SESSION['guest']` | ゲストログインなら `1`、それ以外は未定義 |

ゲストユーザーは回答結果をDBに保存しない。進捗バー・回答状況の色分けも非表示。

---

## 7. 主要な Model 関数（dbfunction.php）

```php
// ユーザー
loginCheck($email, $login_pass)          // ログイン認証
getUserInfo(int $id)                     // ユーザー情報取得

// Chapter
getChapterAll()                          // 全Chapter取得
getChapterNames(int $chapter_id)         // 章名・フォルダ名取得
getChaptersWithSections()               // 章＋節名一覧取得
getChapterProgressList(int $user_id)    // 章ごとの進捗（全問数・正解数）

// Section
getSectionNames(int $section_id)         // 節名・フォルダ名取得
getSectionsWithQuestions(int $chapter_id) // 節＋問題ID一覧取得

// Question
getQuestions(int $question_id)           // 選択肢数・正答取得
getQuestionIds(int $section_id)          // 問題ID配列取得
getQuestionStatuses(int $user_id, array $question_ids) // 回答状況取得

// 解説・ナビ
getExplanationIds(int $question_id)      // 解説ID取得
getNavs(int $question_id)               // 数学ナビリンク取得

// 回答記録
updateQuestionStatus(int $user_id, int $question_id, bool $is_correct)
insertFirstAnswer(int $user_id, int $question_id, bool $is_correct)

// ユーティリティ
V2H(string $str)  // htmlspecialchars ラッパー
```

---

## 8. デザインシステム

### 8-1. カラーパレット（CSS変数）

```css
:root {
    /* Primary（青） */
    --primary:        #185FA5;   /* rgb(24, 95, 165)   メインアクセント */
    --primary-light:  #E6F1FB;   /* rgb(230, 241, 251) 背景・ホバー */
    --primary-mid:    #85B7EB;   /* rgb(133, 183, 235) ボーダー・ホバー枠 */

    /* Success（緑：正解・完了） */
    --success:        #3a8a3a;   /* rgb(58, 138, 58)   テキスト */
    --success-light:  #e8f7e8;   /* rgb(232, 247, 232) 背景 */
    --success-mid:    #a8d8a8;   /* rgb(168, 216, 168) ボーダー */

    /* Warning（橙：進行中） */
    --warning:        #9a6800;   /* rgb(154, 104, 0)   テキスト */
    --warning-light:  #fff8e1;   /* rgb(255, 248, 225) 背景 */
    --warning-mid:    #ffd54f;   /* rgb(255, 213, 79)  プログレスバー */

    /* Danger（赤：誤答） */
    --danger:         #a33a3a;   /* rgb(163, 58, 58)   テキスト */
    --danger-light:   #fdecea;   /* rgb(253, 236, 234) 背景 */
    --danger-mid:     #f5b8b8;   /* rgb(245, 184, 184) ボーダー */

    /* Neutral（グレー：未着手） */
    --neutral:        #888888;   /* rgb(136, 136, 136) テキスト */
    --neutral-light:  #f0f0f0;   /* rgb(240, 240, 240) 背景 */
    --neutral-mid:    #c8c8c8;   /* rgb(200, 200, 200) ボーダー */

    /* Layout */
    --card-radius:    12px;
    --transition:     0.22s ease;
}
```

### 8-2. 背景・ベース色

| 要素 | 色コード |
|------|---------|
| ページ背景 | `#ffffff` |
| カード背景 | `#ffffff` |
| カードヘッダー背景 | `#fafafa` |
| カードボーダー | `#e8e8e8` |
| セクション区切り線 | `#ebebeb` |

### 8-3. タイポグラフィ

| 用途 | フォント |
|------|---------|
| 見出し・章名・節名 | RocknRoll One |
| 本文・UI全般 | Noto Sans JP |
| 問題番号ボタン（Qボタン） | Roboto Italic |

### 8-4. カードコンポーネント共通スタイル

```css
/* 標準カード */
background-color: #ffffff;
border: 1.5px solid #e8e8e8;
border-radius: 12px;
box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);

/* カードホバー */
border-color: #85B7EB;
box-shadow: 0 6px 20px rgba(24, 95, 165, 0.12);
transform: translateY(-2px);

/* カードヘッダー部（薄グレー帯） */
background-color: #fafafa;
border-bottom: 1px solid #ebebeb;
padding: 12px 18px;
```

### 8-5. ヘッダー

```css
position: fixed;
width: 100%;
padding: 16px 5% 8px 5%;
border-bottom: 1px solid #e2e2e2;
background-color: rgba(255, 255, 255, 0.97);
backdrop-filter: blur(8px);
```

### 8-6. パンくずリスト

| クラス | 意味 | スタイル |
|--------|------|---------|
| `.current-location` | 現在ページ | `color: #185FA5` + `border-bottom: 2px solid #185FA5` |
| `.visited-location` | 訪問済みページ（リンク有効） | `color: #185FA5`（下線なし） |
| `.location` | 未到達ページ | `color: #aac4e0`（薄い青） |

### 8-7. リンク・ボタン共通（`.nav-link`）

```css
color: #185FA5;
font-size: 0.88rem;
background: transparent;
border: none;
cursor: pointer;
transition: opacity 0.18s;

/* ホバー */
opacity: 0.5;
```

### 8-8. 進捗表示ルール

| 状態 | ラベル | クラス | バー色 |
|------|--------|--------|--------|
| 未着手（正解数0） | 「未着手」 | `progress-none` | `#c8c8c8` |
| 進行中 | 「X / Y 完了」 | `progress-ongoing` | `#ffd54f` |
| 完了（100%） | 「完了！」 | `progress-done` | `#a8d8a8` |

---

## 9. 問題ページの処理フロー

```
1. GETパラメータからchapter_id, section_id, question_id, qnを取得
2. DBから問題情報・選択肢数・正答を取得
3. 選択肢画像をシャッフル（初回のみ）
4. フォームPOST時：
   a. 正誤判定
   b. $is_guestでなければDB保存
      - updateQuestionStatus()  → user_question_status を UPSERT
      - insertFirstAnswer()     → first_answers に INSERT IGNORE
   c. $show_explanation = true にして解説を表示
5. ページレンダリング
```

---

## 10. 画像ファイルのパス規則

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

## 11. 今後の実装予定

- 正答数ランキング機能（`user_question_status` の集計で実現可能）
- 初見正答率の表示（`first_answers` テーブルから算出）
- マイページ（ユーザー名変更・パスワード変更）
