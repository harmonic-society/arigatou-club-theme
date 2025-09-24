/**
 * ニュースティッカーのスクロール制御
 */
document.addEventListener('DOMContentLoaded', function() {
    const tickerItems = document.querySelector('.news-ticker-items');

    if (!tickerItems) return;

    // ニュースアイテムが複数ある場合のみアニメーション
    const items = tickerItems.querySelectorAll('.news-ticker-item');

    if (items.length > 1) {
        // アイテムを複製して無限ループを実現
        const clonedItems = tickerItems.innerHTML;
        tickerItems.innerHTML += clonedItems;

        // ホバー時の一時停止
        tickerItems.addEventListener('mouseenter', function() {
            this.style.animationPlayState = 'paused';
        });

        tickerItems.addEventListener('mouseleave', function() {
            this.style.animationPlayState = 'running';
        });

        // アニメーション速度の動的調整（テキスト量に応じて）
        const totalWidth = tickerItems.scrollWidth;
        const viewportWidth = tickerItems.parentElement.offsetWidth;

        if (totalWidth > viewportWidth) {
            // テキストが長い場合は速度を調整
            const duration = Math.max(20, (totalWidth / 100) * 3);
            tickerItems.style.animationDuration = duration + 's';
        }
    } else if (items.length === 1) {
        // 1件のみの場合は中央表示
        tickerItems.style.animation = 'none';
        tickerItems.style.justifyContent = 'center';
        tickerItems.style.paddingLeft = '0';
    }
});