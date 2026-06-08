document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('bookmark_sort');
    const list = document.querySelector('.bookmark-list');

    if (!select || !list) return;

    // ページ遷移なしで並び替えるため、各 li の data属性を比較してDOM上の順番を入れ替える
    const comparators = {
        chapter: (a, b) =>
            (Number(a.dataset.chapterOrder) - Number(b.dataset.chapterOrder)) ||
            (Number(a.dataset.sectionOrder) - Number(b.dataset.sectionOrder)) ||
            (Number(a.dataset.questionOrder) - Number(b.dataset.questionOrder)),
        created_asc: (a, b) => a.dataset.createdAt.localeCompare(b.dataset.createdAt),
        created_desc: (a, b) => b.dataset.createdAt.localeCompare(a.dataset.createdAt),
    };

    select.addEventListener('change', () => {
        const comparator = comparators[select.value];
        if (!comparator) return;

        const items = Array.from(list.querySelectorAll('.bookmark-item'));
        items.sort(comparator);
        items.forEach(item => list.appendChild(item));
    });
});
