/**
 * ニュースティッカーのスクロール制御
 */
document.addEventListener('DOMContentLoaded', function() {
    const tickerItems = document.querySelector('.news-ticker-items');
    const tickerContent = document.querySelector('.news-ticker-content');

    if (!tickerItems || !tickerContent) {
        console.log('News ticker elements not found');
        return;
    }

    // ニュースアイテムを取得
    const items = tickerItems.querySelectorAll('.news-ticker-item');
    console.log('News ticker items found:', items.length);

    const isMobile = window.innerWidth <= 768;

    // アイテムを複製して無限ループを実現
    const clonedItems = tickerItems.innerHTML;
    tickerItems.innerHTML += clonedItems + clonedItems; // 3回複製してスムーズに
    console.log('Items cloned for infinite scroll');

    // アニメーションを確実に適用
    tickerItems.classList.remove('static');

    // PC: ホバー時の一時停止
    if (!isMobile) {
        tickerItems.addEventListener('mouseenter', function() {
            this.style.animationPlayState = 'paused';
        });

        tickerItems.addEventListener('mouseleave', function() {
            this.style.animationPlayState = 'running';
        });
    }
});