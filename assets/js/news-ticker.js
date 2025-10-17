/**
 * ニュースティッカーのスクロール制御
 */
document.addEventListener('DOMContentLoaded', function() {
    const tickerItems = document.querySelector('.news-ticker-items');
    const tickerContent = document.querySelector('.news-ticker-content');

    if (!tickerItems || !tickerContent) return;

    // ニュースアイテムが複数ある場合のみアニメーション
    const items = tickerItems.querySelectorAll('.news-ticker-item');
    const isMobile = window.innerWidth <= 768;

    if (items.length >= 1) {
        // アイテムを複製して無限ループを実現
        const clonedItems = tickerItems.innerHTML;
        tickerItems.innerHTML += clonedItems + clonedItems; // 3回複製してスムーズに

        // PC: ホバー時の一時停止
        if (!isMobile) {
            tickerItems.addEventListener('mouseenter', function() {
                this.style.animationPlayState = 'paused';
            });

            tickerItems.addEventListener('mouseleave', function() {
                this.style.animationPlayState = 'running';
            });
        }
    }
});