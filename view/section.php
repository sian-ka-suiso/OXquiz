<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($chapter_number) ? "Chapter $chapter_number - セクション一覧" : "Chapter"; ?></title>
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/variables.css">
    <link rel="stylesheet" type="text/css" href="../css/section.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Zen+Kaku+Gothic+New&display=swap" rel="stylesheet">
</head>

<body>
    <div class="top-container">

        <?php require_once __DIR__ . '/parts/header.php'; ?>

        <div class="page-heading">
            <div class="breadcrumb-row">
                <nav class="top-breadcrumb" aria-label="パンくずリスト">
                    <a href="../ctrl/chapter.php">章一覧</a>
                    <span class="chev">›</span>
                    <button type="button" class="crumb-current" onclick="location.reload()"><?= V2H($chapter_names["name"]) ?></button>
                </nav>
                <a href="../ctrl/chapter.php" class="back-link">章一覧にもどる</a>
            </div>
            <div class="guid-text">問題を選択してください.</div>
        </div>

        <?php
            // 表示対象セクション数（ツールバーの件数表示用）
            $visible_sections = array_values(array_filter($sections, function ($s) use ($is_admin, $is_teacher) {
                return $s['is_published'] || $is_admin || $is_teacher;
            }));
            $visible_section_count = count($visible_sections);
        ?>

        <!-- 件数 + 凡例 -->
        <div class="grid-toolbar">
            <p class="grid-count">全<?= $visible_section_count ?>節</p>
            <div class="legend">
                <span class="legend-item"><span class="legend-dot correct"></span>正解</span>
                <span class="legend-item"><span class="legend-dot incorrect"></span>不正解</span>
                <span class="legend-item"><span class="legend-dot blank"></span>未回答</span>
            </div>
        </div>

        <div class="section-container">

            <!-- Section一覧 -->
            <?php foreach($sections as $section_index => $section): ?>
            <?php if ($section['is_published'] || $is_admin || $is_teacher): ?>
            <?php
                $section_id   = $section['id'];
                $section_name = $section['name'];
                $question_ids = $section['question_ids'];
            ?>
                <article class="section-card">
                    <div class="section-card-head">
                        <p class="section-card-eyebrow">
                            <?= V2H($section_index + 1) ?><span class="sep">・</span>全<?= V2H(count($question_ids)) ?>問
                            <?php foreach ($section_categories[$section_id] ?? [] as $suuri): ?>
                                <?php
                                    $cat_class = 'cat-1';
                                    if (mb_strpos($suuri, 'Ⅱ') !== false) {
                                        $cat_class = 'cat-2';
                                    } elseif (mb_strpos($suuri, 'Ⅲ') !== false) {
                                        $cat_class = 'cat-3';
                                    }
                                ?>
                                <span class="sep">・</span><span class="suuri-tag <?= $cat_class ?>"><?= V2H($suuri) ?></span>
                            <?php endforeach; ?>
                        </p>
                        <h2 class="section-card-title"><?= V2H($section_name) ?></h2>
                    </div>
                    <div class="question-grid">
                        <?php foreach($question_ids as $question_index => $question): ?>
                        <?php
                            $status = (!$is_guest)
                                ? ($question_statuses[$question] ?? 'unanswered')
                                : 'unanswered';
                            $chip_class = match($status) {
                                'correct' => 'q-chip correct',
                                'wrong'   => 'q-chip incorrect',
                                default   => 'q-chip blank',
                            };
                        ?>
                        <a class="<?= $chip_class ?>" href="../ctrl/question.php?chapter_id=<?= V2H($chapter_id) ?>&section_id=<?= V2H($section_id) ?>&question_id=<?= V2H($question) ?>&qn=<?= V2H($question_index + 1) ?>">
                            Q<?= V2H($question_index + 1); ?>
                            <?php if ($status === 'correct'): ?>
                                <span class="q-status correct">✓</span>
                            <?php elseif ($status === 'wrong'): ?>
                                <span class="q-status incorrect">✕</span>
                            <?php endif; ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </article>
            <?php endif; ?>
            <?php endforeach; ?>

        </div>
    </div>
    <script src="../js/section.js"></script>
</body>
</html>