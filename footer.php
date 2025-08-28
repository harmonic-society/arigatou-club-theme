    <footer id="colophon" class="site-footer">
        <div class="footer-decoration"></div>
        <div class="wave-animation"></div>
        
        <div class="container">
            <div class="footer-content">
                <!-- メインカラム -->
                <div class="footer-column footer-main">
                    <?php $site_icon_url = get_site_icon_url(96); ?>
                    <div class="footer-brand-wrapper">
                        <?php if ($site_icon_url) : ?>
                            <img src="<?php echo esc_url($site_icon_url); ?>" alt="<?php bloginfo('name'); ?> アイコン" class="footer-site-icon">
                        <?php endif; ?>
                        <h3>ありがとう倶楽部</h3>
                    </div>
                    <p>感謝の心を広げ、<br>世界を「ありがとう」で満たすコミュニティです。</p>
                    <p>私たちは日々の小さな感謝から始まる<br>大きな幸せの輪を広げています。</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/koh.akiyama.1" target="_blank" rel="noopener" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://x.com/kohcore3" target="_blank" rel="noopener" aria-label="X (Twitter)">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.instagram.com/arigatoh_okotoba_coach/" target="_blank" rel="noopener" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.youtube.com/@arigatoh_club" target="_blank" rel="noopener" aria-label="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                
                <!-- クイックリンク -->
                <?php if (is_active_sidebar('footer-1')) : ?>
                    <div class="footer-column">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                <?php else : ?>
                    <div class="footer-column">
                        <h3>メニュー</h3>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'menu_class' => 'footer-menu',
                            'container' => false,
                            'fallback_cb' => function() {
                                echo '<ul class="footer-menu">';
                                echo '<li><a href="' . home_url('/') . '">TOP</a></li>';
                                echo '<li><a href="' . home_url('/about/') . '">倶楽部について</a></li>';
                                echo '<li><a href="' . get_post_type_archive_link('event') . '">イベント</a></li>';
                                echo '<li><a href="' . home_url('/blog/') . '">ブログ</a></li>';
                                echo '<li><a href="' . get_post_type_archive_link('sponsor') . '">協賛企業</a></li>';
                                echo '<li><a href="https://arigatou-goods.stores.jp/" target="_blank" rel="noopener">グッズ販売</a></li>';
                                echo '<li><a href="' . home_url('/contact/') . '">お問い合わせ</a></li>';
                                echo '</ul>';
                            }
                        ));
                        ?>
                    </div>
                <?php endif; ?>
                
                <!-- 最新情報 -->
                <?php if (is_active_sidebar('footer-2')) : ?>
                    <div class="footer-column">
                        <?php dynamic_sidebar('footer-2'); ?>
                    </div>
                <?php else : ?>
                    <div class="footer-column">
                        <h3>最新のイベント</h3>
                        <?php
                        $recent_events = new WP_Query(array(
                            'post_type' => 'event',
                            'posts_per_page' => 3,
                            'meta_key' => '_event_date',
                            'orderby' => 'meta_value',
                            'order' => 'ASC',
                            'meta_query' => array(
                                array(
                                    'key' => '_event_date',
                                    'value' => date('Y-m-d'),
                                    'compare' => '>=',
                                    'type' => 'DATE'
                                )
                            )
                        ));
                        
                        if ($recent_events->have_posts()) : ?>
                            <ul class="recent-events">
                                <?php while ($recent_events->have_posts()) : $recent_events->the_post(); ?>
                                    <li>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                        <?php
                                        $event_date = get_post_meta(get_the_ID(), '_event_date', true);
                                        if ($event_date) :
                                            $formatted_date = date('n月j日', strtotime($event_date));
                                        ?>
                                            <span class="event-date"><?php echo esc_html($formatted_date); ?></span>
                                        <?php endif; ?>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        <?php
                        wp_reset_postdata();
                        else :
                        ?>
                            <p>現在予定されているイベントはありません。<br>新しいイベント情報をお楽しみに！</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <!-- お問い合わせ -->
                <?php if (is_active_sidebar('footer-3')) : ?>
                    <div class="footer-column">
                        <?php dynamic_sidebar('footer-3'); ?>
                    </div>
                <?php else : ?>
                    <div class="footer-column">
                        <h3>お問い合わせ</h3>
                        <a href="<?php echo home_url('/contact/'); ?>" class="btn btn-secondary">
                            お問い合わせフォーム
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- フッター下部 -->
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <span class="footer-heart">♥</span> すべての権利を保護しています。</p>
                <ul class="footer-links">
                    <li><a href="<?php echo home_url('/privacy/'); ?>">プライバシーポリシー</a></li>
                    <li><a href="<?php echo home_url('/terms/'); ?>">利用規約</a></li>
                    <li><a href="<?php echo home_url('/sitemap/'); ?>">サイトマップ</a></li>
                </ul>
            </div>
        </div>
        
        <!-- スクロールトップボタン -->
        <div class="scroll-to-top" id="scrollToTop" aria-label="ページトップへ戻る"></div>
    </footer>
</div>

<?php wp_footer(); ?>

<script>
// スクロールトップボタン
document.addEventListener('DOMContentLoaded', function() {
    const scrollToTopBtn = document.getElementById('scrollToTop');
    
    // スクロール時の表示/非表示
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.classList.add('visible');
        } else {
            scrollToTopBtn.classList.remove('visible');
        }
    });
    
    // クリック時のスクロール
    scrollToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});

// フッターアニメーション
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // フッターカラムをobserve
    document.querySelectorAll('.footer-column').forEach(column => {
        column.style.opacity = '0';
        column.style.transform = 'translateY(30px)';
        column.style.transition = 'all 0.8s ease-out';
        observer.observe(column);
    });
});
</script>

</body>
</html>