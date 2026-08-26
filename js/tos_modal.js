document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('tosModal');
    if (!modal) return;

    document.querySelectorAll('[data-tos-open]').forEach(btn => {
        btn.addEventListener('click', () => modal.showModal());
    });

    const closeBtn = modal.querySelector('.tos-modal-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => modal.close());
    }

    // 背景（::backdrop）クリックで閉じる
    modal.addEventListener('click', (event) => {
        if (event.target === modal) modal.close();
    });
});
