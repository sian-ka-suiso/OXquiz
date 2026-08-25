// 通常モード / 詳細モード 切り替え
(function () {
    const container = document.getElementById('chapterContainer');
    const buttons = document.querySelectorAll('.mode-btn');
    if (!container || !buttons.length) return;

    function applyMode(mode) {
        container.dataset.mode = mode;
        document.body.dataset.mode = mode;
        buttons.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.modeValue === mode);
        });
        try {
            localStorage.setItem('chapterViewMode', mode);
        } catch (e) {
            // localStorageが使用できない場合は何もしない
        }
    }

    let savedMode = 'simple';
    try {
        savedMode = localStorage.getItem('chapterViewMode') || 'simple';
    } catch (e) {
        // localStorageが使用できない場合は既定値のまま
    }
    applyMode(savedMode === 'detail' ? 'detail' : 'simple');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => applyMode(btn.dataset.modeValue));
    });
})();

document.querySelector('.top-button')?.addEventListener('click', function(e) {
    e.preventDefault(); // デフォルトの動作を無効化
    window.scrollTo({
        top: 0,
        behavior: 'smooth' // スムーズにスクロールする動作を指定
    });
});

document.querySelectorAll('.index a').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();  // デフォルトのリンク動作を防ぐ

        // hrefの値からターゲットIDを取得
        const targetId = this.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);

        if (targetElement) {
            // スムーズにスクロール（中央に配置）
            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'center'   // 'start'(先頭) / 'center'(中央) / 'end'(末尾) / 'nearest'
            });
        }
    });
});



