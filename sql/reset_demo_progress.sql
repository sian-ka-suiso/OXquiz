-- =============================================
-- デモアカウント（demo@email.com）の進捗リセット
-- =============================================
-- レンタルサーバー（ロリポップ）のcron機能から定期的に実行する想定。
-- ポートフォリオサイトの来訪者がデモアカウントを自由に操作できるため、
-- 定期的にこのSQLを流して「最初の3セクションだけ回答済み」という
-- 決まった状態に戻す。
--
-- user_id は環境（ローカル／本番）によってオートインクリメント値が
-- 異なるため、決め打ちにせずメールアドレスから毎回引き直す。
--
-- user_question_status / first_answers / bookmark_table（復習リスト）を
-- 一度全削除してから、ベースラインの21問分を入れ直す
-- （cron/reset_demo_progress.php と同内容）。
-- 復習リストは、間違えた問題（q1, q7, q13, q84）のうち3問（q1, q13, q84）を
-- デフォルトで登録した状態に戻す。
-- 併せて、マイページの変更操作は無効化済みだが、念のため
-- user_name・login_pass もデモ用の初期値に戻しておく
-- （login_passは 'demo0920' をpassword_hash()でハッシュ化した値）。
-- デモアカウントは利用規約への同意を求めない仕様のため、tos_agreed_at も未同意（NULL）に戻す。

START TRANSACTION;

SET @demo_user_id = (SELECT id FROM user_table WHERE email = 'demo@email.com' LIMIT 1);

DELETE FROM user_question_status WHERE user_id = @demo_user_id;
DELETE FROM first_answers WHERE user_id = @demo_user_id;
DELETE FROM bookmark_table WHERE user_id = @demo_user_id;

UPDATE user_table
SET user_name     = 'デモユーザー',
    login_pass    = '$2y$10$.92wlnobQxeFNA8H1MvwtuwLHC9FbWLt2uzI2h1RLvHqYGZcg6Vpu',
    tos_agreed_at = NULL
WHERE id = @demo_user_id;

-- section_id=1（計算法則・全14問）：q1,7,13を不正解、他を正解に
INSERT INTO user_question_status (user_id, question_id, status, attempt_count, last_answered_at) VALUES
(@demo_user_id, 1,  'wrong',   1, NOW() - INTERVAL 58 MINUTE),
(@demo_user_id, 2,  'correct', 1, NOW() - INTERVAL 57 MINUTE),
(@demo_user_id, 3,  'correct', 1, NOW() - INTERVAL 56 MINUTE),
(@demo_user_id, 4,  'correct', 1, NOW() - INTERVAL 55 MINUTE),
(@demo_user_id, 5,  'correct', 1, NOW() - INTERVAL 54 MINUTE),
(@demo_user_id, 6,  'correct', 1, NOW() - INTERVAL 53 MINUTE),
(@demo_user_id, 7,  'wrong',   1, NOW() - INTERVAL 52 MINUTE),
(@demo_user_id, 8,  'correct', 1, NOW() - INTERVAL 51 MINUTE),
(@demo_user_id, 9,  'correct', 1, NOW() - INTERVAL 50 MINUTE),
(@demo_user_id, 10, 'correct', 1, NOW() - INTERVAL 49 MINUTE),
(@demo_user_id, 11, 'correct', 1, NOW() - INTERVAL 48 MINUTE),
(@demo_user_id, 12, 'correct', 1, NOW() - INTERVAL 47 MINUTE),
(@demo_user_id, 13, 'wrong',   1, NOW() - INTERVAL 46 MINUTE),
(@demo_user_id, 14, 'correct', 1, NOW() - INTERVAL 45 MINUTE);

INSERT INTO first_answers (user_id, question_id, is_correct, answered_at) VALUES
(@demo_user_id, 1,  0, NOW() - INTERVAL 58 MINUTE),
(@demo_user_id, 2,  1, NOW() - INTERVAL 57 MINUTE),
(@demo_user_id, 3,  1, NOW() - INTERVAL 56 MINUTE),
(@demo_user_id, 4,  1, NOW() - INTERVAL 55 MINUTE),
(@demo_user_id, 5,  1, NOW() - INTERVAL 54 MINUTE),
(@demo_user_id, 6,  1, NOW() - INTERVAL 53 MINUTE),
(@demo_user_id, 7,  0, NOW() - INTERVAL 52 MINUTE),
(@demo_user_id, 8,  1, NOW() - INTERVAL 51 MINUTE),
(@demo_user_id, 9,  1, NOW() - INTERVAL 50 MINUTE),
(@demo_user_id, 10, 1, NOW() - INTERVAL 49 MINUTE),
(@demo_user_id, 11, 1, NOW() - INTERVAL 48 MINUTE),
(@demo_user_id, 12, 1, NOW() - INTERVAL 47 MINUTE),
(@demo_user_id, 13, 0, NOW() - INTERVAL 46 MINUTE),
(@demo_user_id, 14, 1, NOW() - INTERVAL 45 MINUTE);

-- section_id=2（部分分数分解・全1問）：q15を正解に
INSERT INTO user_question_status (user_id, question_id, status, attempt_count, last_answered_at) VALUES
(@demo_user_id, 15, 'correct', 1, NOW() - INTERVAL 44 MINUTE);

INSERT INTO first_answers (user_id, question_id, is_correct, answered_at) VALUES
(@demo_user_id, 15, 1, NOW() - INTERVAL 44 MINUTE);

-- section_id=15（平方根・全6問）：q84を不正解、他を正解に
INSERT INTO user_question_status (user_id, question_id, status, attempt_count, last_answered_at) VALUES
(@demo_user_id, 80, 'correct', 1, NOW() - INTERVAL 43 MINUTE),
(@demo_user_id, 81, 'correct', 1, NOW() - INTERVAL 42 MINUTE),
(@demo_user_id, 82, 'correct', 1, NOW() - INTERVAL 41 MINUTE),
(@demo_user_id, 83, 'correct', 1, NOW() - INTERVAL 40 MINUTE),
(@demo_user_id, 84, 'wrong',   1, NOW() - INTERVAL 39 MINUTE),
(@demo_user_id, 85, 'correct', 1, NOW() - INTERVAL 38 MINUTE);

INSERT INTO first_answers (user_id, question_id, is_correct, answered_at) VALUES
(@demo_user_id, 80, 1, NOW() - INTERVAL 43 MINUTE),
(@demo_user_id, 81, 1, NOW() - INTERVAL 42 MINUTE),
(@demo_user_id, 82, 1, NOW() - INTERVAL 41 MINUTE),
(@demo_user_id, 83, 1, NOW() - INTERVAL 40 MINUTE),
(@demo_user_id, 84, 0, NOW() - INTERVAL 39 MINUTE),
(@demo_user_id, 85, 1, NOW() - INTERVAL 38 MINUTE);

-- 復習リスト（ブックマーク）：間違えた問題（q1, q7, q13, q84）のうち3問を登録
INSERT INTO bookmark_table (user_id, question_id, created_at) VALUES
(@demo_user_id, 1,  NOW() - INTERVAL 30 MINUTE),
(@demo_user_id, 13, NOW() - INTERVAL 29 MINUTE),
(@demo_user_id, 84, NOW() - INTERVAL 28 MINUTE);

COMMIT;
