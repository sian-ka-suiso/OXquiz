<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>よくある間違い〇✕クイズ マイページ</title>
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/variables.css">
    <link rel="stylesheet" type="text/css" href="../css/mypage.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&family=RocknRoll+One&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Zen+Kaku+Gothic+New&display=swap" rel="stylesheet">
</head>

<body>

    <?php require_once __DIR__ . '/parts/header.php'; ?>

    <!-- ページタイトル -->
    <h2 class="page-title">マイページ</h2>

    <!-- 更新メッセージ -->
    <?php if(isset($change_message)): ?>
    <span class="update-message">
        <?= V2H($change_message); ?>
    </span>
    <?php endif; ?>
    <?php if(isset($reset_message)): ?>
    <span class="update-message">
        <?= V2H($reset_message); ?>
    </span>
    <?php endif; ?>

    <div class="mypage-layout">

        <!-- 上部ツールバー：表示切り替えタブ + 戻るリンク -->
        <div class="mypage-toolbar">
            <nav class="mypage-tabs" role="group" aria-label="表示切り替え">
                <button type="button" class="mypage-tab active" data-tab="user-info">ユーザー情報</button>
                <button type="button" class="mypage-tab" data-tab="bookmarks">復習リスト</button>
                <button type="button" class="mypage-tab" data-tab="achievements" style="display: none;">実績</button>
            </nav>
            <a href="../ctrl/chapter.php" class="back-link">章一覧に戻る</a>
        </div>

        <!-- コンテンツ -->
        <div class="mypage-panels">

            <!-- ユーザー情報カード -->
            <section class="mypage-panel active" data-panel="user-info">
                <h3>ユーザー情報</h3>

                <!-- ユーザー名 -->
                <div class="info-category">
                    <div class="category-name">ユーザー名</div>
                    <div class="update-area">
                        <span>
                            <?= V2H($user_name); ?>
                        </span>
                        <form action="mypage.php" method="post">
                            <input type="text" name="change_name" placeholder="新しいユーザー名">
                            <button type="submit">変更</button>
                        </form>
                    </div>
                </div>

                <!-- メールアドレス -->
                <div class="info-category">
                    <div class="category-name">メールアドレス</div>
                    <span>
                        <?= V2H($email); ?>
                    </span>
                </div>

                <!-- パスワード -->
                <div class="info-category">
                    <div class="category-name">パスワード</div>
                    <div class="update-area">
                        <span>••••••••••</span>
                        <form action="mypage.php" method="post">
                            <input type="password" name="reset_pass" placeholder="新しいパスワード">
                            <button type="submit">再設定</button>
                        </form>
                    </div>
                </div>

                <!-- 所属クラス -->
                <div class="info-category">
                    <div class="category-name">所属クラス</div>
                    <span>
                        <?= V2H($class_name); ?>
                    </span>
                </div>

                <!-- 利用規約 -->
                <div class="info-category">
                    <div class="category-name">利用規約</div>
                    <div class="tos-status">
                        <span><?= $tos_agreed_at ? '同意済み（' . V2H(date('Y/m/d', strtotime($tos_agreed_at))) . '）' : '未同意' ?></span>
                        <button type="button" class="tos-open-btn" data-tos-open>利用規約を見る</button>
                    </div>
                </div>

                <!-- 登録日 -->
                <div class="info-category">
                    <div class="category-name">登録日</div>
                    <span>
                        <?= V2H($created_at); ?>
                    </span>
                </div>

                <!-- 最終更新日 -->
                <div class="info-category">
                    <div class="category-name">最終更新日</div>
                    <span>
                        <?= V2H($update_at); ?>
                    </span>
                </div>

            </section>

            <section class="mypage-panel" data-panel="bookmarks">
                <h3>リストに追加した問題</h3>

                <?php if (!empty($bookmarks)): ?>
                <div class="bookmark-sort-form">
                    <label for="bookmark_sort">並び替え</label>
                    <select id="bookmark_sort">
                        <option value="chapter" selected>番号順</option>
                        <option value="created_desc">日付の新しい順</option>
                        <option value="created_asc">日付の古い順</option>
                    </select>
                </div>
                <?php endif; ?>

                <?php if (empty($bookmarks)): ?>
                <div class="empty-message">リストに追加した問題はまだありません</div>
                <?php else: ?>
                <ul class="bookmark-list">
                    <?php foreach ($bookmarks as $bookmark): ?>
                    <li class="bookmark-item" data-created-at="<?= V2H($bookmark['created_at']) ?>"
                        data-chapter-order="<?= V2H($bookmark['chapter_order']) ?>"
                        data-section-order="<?= V2H($bookmark['section_order']) ?>"
                        data-question-order="<?= V2H($bookmark['question_order']) ?>">
                        <form action="../ctrl/question.php" method="get" class="bookmark-link-form">
                            <input type="hidden" name="chapter_id" value="<?= V2H($bookmark['chapter_id']) ?>">
                            <input type="hidden" name="section_id" value="<?= V2H($bookmark['section_id']) ?>">
                            <input type="hidden" name="question_id" value="<?= V2H($bookmark['question_id']) ?>">
                            <input type="hidden" name="qn" value="<?= V2H($bookmark['question_number']) ?>">
                            <button type="submit" class="bookmark-link">
                                <span class="bookmark-path">
                                    <?= V2H($bookmark['chapter_name']) ?> ▸
                                    <?= V2H($bookmark['section_name']) ?> ▸ Q
                                    <?= V2H($bookmark['question_number']) ?>
                                </span>
                                <span class="bookmark-date">
                                    <?= V2H($bookmark['created_at']) ?>
                                </span>
                            </button>
                        </form>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </section>

        <section class="mypage-panel" data-panel="achievements">
            <h3>獲得した実績</h3>
        </section>

        </div>
    </div>

    <?php require_once __DIR__ . '/parts/tos_modal.php'; ?>

    <script src="../js/mypage.js"></script>
</body>

</html>