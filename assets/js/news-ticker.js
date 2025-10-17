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
    const isSmallMobile = window.innerWidth <= 480;

    if (items.length >= 1) {
        // アイテムを複製して無限ループを実現
        const clonedItems = tickerItems.innerHTML;
        tickerItems.innerHTML += clonedItems + clonedItems; // 3回複製してスムーズに

        // アニメーションを確実に開始
        tickerItems.style.animation = 'none';
        setTimeout(() => {
            tickerItems.style.animation = '';
        }, 10);

        // PC: ホバー時の一時停止
        if (!isMobile) {
            tickerItems.addEventListener('mouseenter', function() {
                this.style.animationPlayState = 'paused';
            });

            tickerItems.addEventListener('mouseleave', function() {
                this.style.animationPlayState = 'running';
            });
        }

        // モバイル: タッチ時の一時停止
        if (isMobile) {
            let touchStartX = 0;
            let touchEndX = 0;

            tickerContent.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
                tickerItems.style.animationPlayState = 'paused';
            }, { passive: true });

            tickerContent.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                tickerItems.style.animationPlayState = 'running';

                // スワイプ検出（将来的な機能拡張用）
                const swipeThreshold = 50;
                if (Math.abs(touchEndX - touchStartX) > swipeThreshold) {
                    // 左右スワイプ時の処理（現在は何もしない）
                }
            }, { passive: true });
        }

        // アニメーション速度の動的調整（ゆっくりスクロール）
        const totalWidth = tickerItems.scrollWidth;
        const viewportWidth = tickerItems.parentElement.offsetWidth;

        if (totalWidth > viewportWidth) {
            // デバイスに応じて速度を調整（より遅く）
            let baseDuration = isMobile ? (isSmallMobile ? 80 : 70) : 60;
            const duration = Math.max(baseDuration, (totalWidth / 100) * 3);
            tickerItems.style.animationDuration = duration + 's';
        }

    } else if (items.length === 1) {
        // 1件のみの場合は中央表示
        tickerItems.style.animation = 'none';
        tickerItems.style.justifyContent = 'center';
        tickerItems.style.paddingLeft = '0';

        // モバイルでは折り返し許可
        if (isMobile) {
            tickerItems.style.whiteSpace = 'normal';
            tickerItems.style.textAlign = 'center';
        }
    }

    // ウィンドウリサイズ時の再計算
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            location.reload(); // シンプルにリロード
        }, 250);
    });
});