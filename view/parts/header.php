<header>
    <a href="../ctrl/chapter.php" class="site-logo">
        <img src="../images/other/logo.png" alt="よくある間違い〇✕クイズ">
    </a>
    <nav>
        <div class="links-area">
            <?php if (!$is_guest): ?>
                <a href="../ctrl/mypage.php" class="nav-link">マイページ</a>
            <?php endif; ?>
            <a href="https://w3e.kanazawa-it.ac.jp/math/" target="_blank" rel="noopener noreferrer" class="nav-link">KIT数学ナビゲーション <span class="ext-icon">↗</span></a>
            <span class="nav-divider" aria-hidden="true"></span>
            <form action="../ctrl/" method="post" class="logoutBtn">
                <input type="hidden" name="sign_out" value="true">
                <button type="submit" class="nav-link logout">ログアウト</button>
            </form>
        </div>
    </nav>
</header>
