document.addEventListener('DOMContentLoaded', () => {
    const labels = document.querySelectorAll('.option-label');
    labels.forEach(label => {
        label.addEventListener('click', (event) => {
            // 少し遅らせてフォーム送信（ラジオボタンの反映を待つ）
            setTimeout(() => {
                document.getElementById('quizForm').submit();
            }, 10); // 10ミリ秒遅らせて送信
        });
    });
});

function changeImage(index, btn) {
    const viewer = document.getElementById('image-viewer');
    viewer.src = explanationPath[index];

    // すべてのボタンから active を削除
    const buttons = document.querySelectorAll('.exp-btn');
    buttons.forEach(b => b.classList.remove('active'));

    // クリックしたボタンに active を追加
    btn.classList.add('active');
}

// ページロード時に最初のボタンを active にする
window.addEventListener('DOMContentLoaded', () => {
    const firstBtn = document.querySelector('.exp-btn');
    if (firstBtn) firstBtn.classList.add('active');
});
