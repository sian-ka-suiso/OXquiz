<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>よくある間違いOXクイズ Chapter Page</title>
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/variables.css">
    <link rel="stylesheet" type="text/css" href="../css/chapter.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Noto+Serif+JP:wght@200..900&family=Zen+Kaku+Gothic+New&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=RocknRoll+One&display=swap" rel="stylesheet">
</head>

<body>
    <div class="top-container">

        <?php require_once __DIR__ . '/parts/header.php'; ?>

        <div class="page-heading">
            <nav class="top-breadcrumb" aria-label="パンくずリスト">
                <button type="button" class="crumb-current" onclick="location.reload()">章一覧</button>
            </nav>
            <div class="guid-text">単元を選択してください.</div>
        </div>

        <?php
            // 表示対象チャプター数（トップバーの件数表示用）
            $visible_chapters = array_values(array_filter($chapters, function ($c) use ($is_admin, $is_teacher) {
                return $c['chapter_published'] || $is_admin || $is_teacher;
            }));
            $visible_chapter_count = count($visible_chapters);

            $circle_r = 25;
            $circumference = round(2 * M_PI * $circle_r, 2);
        ?>

        <!-- 表示モード切り替え + 件数 -->
        <div class="grid-toolbar">
            <p class="grid-count">全<?= $visible_chapter_count ?>章</p>
            <div class="mode-switch" role="group" aria-label="表示モード切り替え">
                <button type="button" class="mode-btn active" data-mode-value="simple">簡易表示</button>
                <button type="button" class="mode-btn" data-mode-value="detail">進捗表示</button>
            </div>
        </div>

        <!-- 凡例（詳細モードのみ表示） -->
        <div class="legend">
            <span class="legend-item"><span class="legend-dot correct"></span>正解</span>
            <span class="legend-item"><span class="legend-dot incorrect"></span>不正解</span>
            <span class="legend-item"><span class="legend-dot blank"></span>未回答</span>
            <?php if($is_guest): ?><span class="guest-note">ゲストログインでは進捗は表示されません．</span><?php endif; ?>
        </div>

        <!-- チャプター一覧（Grid） -->
        <div class="chapter-container" id="chapterContainer" data-mode="simple">
            <?php $chapter_index = 1; ?>
            <?php foreach ($chapters as $chapter): ?>
            <?php if ($chapter['chapter_published'] || $is_admin || $is_teacher): ?>
            <?php
                $chapter_id   = $chapter['id'];
                $chapter_name = $chapter['chapter_name'];
                $section_names = $chapter['sections'];

                // 章全体の進捗計算
                $progress        = $chapter_progress[$chapter_id] ?? ['total' => 0, 'correct_count' => 0, 'wrong_count' => 0];
                $total           = $progress['total'];
                $correct         = $progress['correct_count'];
                $wrong           = $progress['wrong_count'] ?? 0;
                $answered        = $correct + $wrong;
                $correct_percent = ($total > 0) ? (int)round($correct / $total * 100) : 0;

                // 進捗リング（詳細モード用）
                $card_state    = ($answered === 0) ? 'new' : 'active';
                $correct_len   = round(($total > 0 ? $correct / $total : 0) * $circumference, 2);
                $wrong_len     = round(($total > 0 ? $wrong   / $total : 0) * $circumference, 2);
            ?>
            <form action="section.php" method="get" class="chapter-card" data-state="<?= $card_state ?>">
                <input type="hidden" name="chapter_id" value="<?= V2H($chapter_id); ?>">
                <button type="submit">

                    <!-- Chapter番号バッジ -->
                    <div class="chapter-number-badge">
                        Chapter <?= $chapter_index ?>
                    </div>

                    <div class="card-body">

                        <!-- 章名 + 進捗リング（詳細モードのみ表示） -->
                        <div class="chapter-line">
                            <div class="chapter-name-container">
                                <span><?= V2H($chapter_name); ?></span>
                            </div>
                            <div class="progress-ring">
                                <svg viewBox="0 0 56 56">
                                    <circle class="ring-track" cx="28" cy="28" r="25" transform="rotate(-90 28 28)"/>
                                    <?php if ($card_state !== 'new'): ?>
                                    <circle class="ring-seg" cx="28" cy="28" r="25" stroke="var(--color-correct)" stroke-dasharray="<?= $correct_len ?> <?= $circumference ?>" stroke-dashoffset="0" transform="rotate(-90 28 28)"/>
                                    <circle class="ring-seg" cx="28" cy="28" r="25" stroke="var(--color-incorrect)" stroke-dasharray="<?= $wrong_len ?> <?= $circumference ?>" stroke-dashoffset="-<?= $correct_len ?>" transform="rotate(-90 28 28)"/>
                                    <?php endif; ?>
                                </svg>
                                <span class="ring-pct"><?= $card_state === 'new' ? 'ー' : $correct_percent . '%' ?></span>
                            </div>
                        </div>

                        <hr class="divider" />

                        <!-- セクション名リスト -->
                        <div class="section-name-container">
                            <?php $section_index = 1; ?>
                            <?php foreach ($section_names as $section): ?>
                                <?php if ($section['section_published'] || $is_admin || $is_teacher): ?>
                                    <?php
                                        $section_id  = $section['id'];
                                        $sec_progress = $section_progress[$section_id] ?? ['total' => 0, 'correct_count' => 0, 'wrong_count' => 0];
                                        $sec_total    = $sec_progress['total'];
                                        $sec_correct  = $sec_progress['correct_count'];
                                        $sec_wrong    = $sec_progress['wrong_count'] ?? 0;
                                        $sec_correct_pct = ($sec_total > 0) ? round($sec_correct / $sec_total * 100, 2) : 0;
                                        $sec_wrong_pct   = ($sec_total > 0) ? round($sec_wrong   / $sec_total * 100, 2) : 0;
                                    ?>
                                    <div class="section-row">
                                        <span class="sec-num"><?= V2H($section_index) ?></span>
                                        <span class="sec-name font-Noto"><?= V2H($section['name']); ?></span>
                                        <?php foreach ($section_categories[$section_id] ?? [] as $suuri): ?>
                                            <?php
                                                $cat_class = 'cat-1';
                                                if (mb_strpos($suuri, 'Ⅱ') !== false) {
                                                    $cat_class = 'cat-2';
                                                } elseif (mb_strpos($suuri, 'Ⅲ') !== false) {
                                                    $cat_class = 'cat-3';
                                                }
                                            ?>
                                            <span class="suuri-tag <?= $cat_class ?>"><?= V2H($suuri) ?></span>
                                        <?php endforeach; ?>
                                        <span class="sec-bar">
                                            <span class="b-correct" style="width: <?= $sec_correct_pct ?>%;"></span>
                                            <span class="b-incorrect" style="width: <?= $sec_wrong_pct ?>%;"></span>
                                        </span>
                                        <span class="sec-frac"><?= $sec_correct ?>/<?= $sec_total ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php $section_index++; ?>
                            <?php endforeach; ?>
                        </div>

                    </div>

                </button>
            </form>
            <?php $chapter_index++; ?>
            <?php endif; ?>
            <?php endforeach; ?>

        </div>

    </div>
    <script src="../js/chapter.js"></script>
</body>
</html>
