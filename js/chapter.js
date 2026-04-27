document.querySelector('.top-button').addEventListener('click', function(e) {
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



