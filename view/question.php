<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>よくある間違いOXクイズ QuestionPage</title>
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/variables.css">
    <link rel="stylesheet" type="text/css" href="../css/question.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&family=RocknRoll+One&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Zen+Kaku+Gothic+New&display=swap" rel="stylesheet">
</head>

<body>
    <div class="top-container">

        <?php require_once __DIR__ . '/parts/header.php'; ?>

        <form id="reloadQuestionForm" action="../ctrl/question.php" method="get">
            <input type="hidden" name="chapter_id" value="<?= V2H($chapter_id) ?>">
            <input type="hidden" name="section_id" value="<?= V2H($section_id) ?>">
            <input type="hidden" name="question_id" value="<?= V2H($question_id) ?>">
            <input type="hidden" name="qn" value="<?= V2H($question_number) ?>">
        </form>

        <div class="page-heading">
            <div class="breadcrumb-row">
                <nav class="top-breadcrumb" aria-label="パンくずリスト">
                    <a href="../ctrl/chapter.php">章一覧</a>
                    <span class="chev">›</span>
                    <a href="../ctrl/section.php?chapter_id=<?= V2H($chapter_id) ?>"><?= V2H($chapter_names["name"]) ?></a>
                    <span class="chev">›</span>
                    <a href="../ctrl/section.php?chapter_id=<?= V2H($chapter_id) ?>"><?= V2H($section_names["name"]) ?></a>
                    <span class="chev">›</span>
                    <button type="submit" form="reloadQuestionForm" class="crumb-current">Q<?= V2H($question_number) ?></button>
                </nav>
                <a href="../ctrl/section.php?chapter_id=<?= V2H($chapter_id) ?>" class="back-link">節一覧に戻る</a>
            </div>
            <div class="guid-text">正しいと思う解答を選択してください.</div>
        </div>

        <main>

            <?php
                // user_question_statusから現在の問題のステータスを取得
                $current_status = '';
                if (!$is_guest) {
                    $statuses = getQuestionStatuses($user_id, [(int)$question_id]);
                    $current_status = $statuses[(int)$question_id] ?? '';
                }
            ?>

            <?php if (!$is_guest): ?>
            <!-- ブックマーク登録／解除（ボタンはパンくず内に配置し、form属性でこのフォームに紐付ける） -->
            <form id="bookmarkForm" action="" method="post">
                <input type="hidden" name="chapter_id" value="<?= $chapter_id ?>">
                <input type="hidden" name="section_id" value="<?= $section_id ?>">
                <input type="hidden" name="question_id" value="<?= $question_id ?>">
                <input type="hidden" name="qn" value="<?= htmlspecialchars($question_number); ?>">
                <input type="hidden" name="toggle_bookmark" value="1">
            </form>
            <?php endif; ?>

            <form id="quizForm" action="" method="post">

                <!-- 正答率・ブックマークなどのステータス表示 -->
                <div class="question-toolbar">
                    <?php if ($current_status === 'correct' && !$show_explanation): ?>
                    <span class="answered-badge">正解済み</span>
                    <?php endif; ?>
                    <span class="accuracy-badge">正答率：<?= V2H($accuracy_text) ?></span>

                    <?php if (!$is_guest): ?>
                    <button type="submit" form="bookmarkForm" class="bookmark-btn <?= $is_bookmarked ? 'active' : '' ?>" aria-label="ブックマーク">
                        <?= $is_bookmarked ? '復習リスト追加済み' : '復習リストに追加' ?>
                    </button>
                    <?php endif; ?>
                </div>

                <div class="question-container">

                    <!-- 問題画像 -->
                    <div class="question-area">
                        <img src="<?= htmlspecialchars($questionPath) ?>" alt="問題画像がありません">
                    </div>

                    <!-- 選択肢 -->
                    <div class="option-area">
                        <input type="hidden" name="chapter_id" value="<?= $chapter_id; ?>">
                        <input type="hidden" name="section_id" value="<?= $section_id; ?>">
                        <input type="hidden" name="question_id" value="<?= $question_id; ?>">
                        <input type="hidden" name="qn" value="<?= $question_number; ?>">
                        <?php $orderList = implode(',', array_column($shuffledOptions, 'id')); ?>
                        <input type="hidden" name="option_order" value="<?= $orderList ?>">

                        <?php foreach($shuffledOptions as $opt):
                            $i = $opt['id'];
                            $id = "option" . $i;
                            $value = $i;
                            $currentPath = $opt['path'];
                            $class = "option-label";

                            if (isset($_POST['selected_option'])) {
                                $selected = (int)$_POST['selected_option'];
                                if ($value === (int)$questions["correct_answer"] && $value === $selected) {
                                    $class = "correct-selected";
                                } elseif ($value !== (int)$questions["correct_answer"] && $value === $selected) {
                                    $class = "incorrect-selected";
                                } elseif ($value === (int)$questions["correct_answer"] && $value !== $selected) {
                                    $class = "correct-unselected";
                                } else {
                                    $class = "unselected";
                                }
                            }
                        ?>

                        <?php if (!isset($selected)): ?>
                            <input type="radio" id="<?= $id ?>" name="selected_option" value="<?= $value ?>" hidden>
                            <label for="<?= $id ?>" class="<?= $class ?>">
                                <img src="<?= htmlspecialchars($currentPath) ?>" alt="選択肢画像">
                            </label>
                        <?php else: ?>
                            <div class="option-selected <?= $class ?>">
                                <img src="<?= htmlspecialchars($currentPath) ?>" alt="選択肢画像">
                            </div>
                        <?php endif; ?>

                        <?php endforeach; ?>
                    </div>

                </div>
            </form>

            <!-- 正誤フィードバック -->
            <?php if ($show_explanation): ?>
            <?php
                $is_correct = isset($_POST['selected_option']) && (int)$_POST['selected_option'] === (int)$questions["correct_answer"];
            ?>
            <div class="feedback <?= $is_correct ? 'correct' : 'incorrect' ?>">
                <h2><?= $feedback ?></h2>
            </div>
            <?php endif; ?>

            <!-- 解説エリア -->
            <div class="explanation-container<?= $show_explanation ? ' show' : '' ?>" id="exp">
                <div class="exp_h3">
                    <h3>正答・解説</h3>
                    <div class="button-container">
                        <?php $exp_index = 1; ?>
                        <?php foreach ($explanation_ids as $exp): ?>
                        <button class="exp-btn" onclick="changeImage(<?= $exp_index - 1 ?>, this)">解説 <?= $exp_index ?></button>
                        <?php $exp_index++; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <img id="image-viewer" src="<?= htmlspecialchars($explanationPath[1]); ?>" alt="解説画像がありません" />
            </div>

            <!-- 数学ナビリンク -->
            <div class="math_nav-container<?= $show_explanation ? ' show' : '' ?>" id="nav">
                <h3>KIT数学ナビゲーションの関連リンク</h3>
                <ul>
                    <?php if(empty($math_navs)): ?>
                    <li><span class="empty">関連するリンクはありません</span></li>
                    <?php endif; ?>
                    <?php foreach ($math_navs as $nav): ?>
                    <li>
                        <a href="<?= htmlspecialchars($nav['link_url']); ?>" target="_blank" rel="noopener noreferrer">
                            <?= $nav['link_name']; ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        </main>

        <!-- 前後ナビゲーション -->
        <div class="question-nav-button">
            <form action="../ctrl/question.php" method="get" class="<?= $question_number == 1 ? 'hidden' : '' ?>">
                <input type="hidden" name="chapter_id" value="<?= $chapter_id ?>">
                <input type="hidden" name="section_id" value="<?= $section_id ?>">
                <input type="hidden" name="question_id" value="<?= $question_ids[$question_number - 2] ?>">
                <input type="hidden" name="qn" value="<?= htmlspecialchars($question_number - 1); ?>">
                <button class="prev" type="submit">← 前の問題</button>
            </form>
            <form action="../ctrl/question.php" method="get" class="<?= $question_number == $question_count ? 'hidden' : '' ?>">
                <input type="hidden" name="chapter_id" value="<?= $chapter_id; ?>">
                <input type="hidden" name="section_id" value="<?= $section_id; ?>">
                <input type="hidden" name="question_id" value="<?= $question_ids[$question_number] ?>">
                <input type="hidden" name="qn" value="<?= htmlspecialchars($question_number + 1); ?>">
                <button class="next" type="submit">次の問題 →</button>
            </form>
        </div>

    </div>

    <script>
        const explanationPath = <?= json_encode(array_values($explanationPath)) ?>;
    </script>
    <script src="../js/question.js"></script>
</body>
</html>