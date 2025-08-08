    <footer id="colophon" class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>ありがとう倶楽部</h3>
                    <p>感謝の心を広げ、世界を「ありがとう」で満たすコミュニティです。</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <?php if (is_active_sidebar('footer-1')) : ?>
                    <div class="footer-column">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                <?php else : ?>
                    <div class="footer-column">
                        <h3>クイックリンク</h3>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'menu_class' => 'footer-menu',
                            'container' => false,
                            'fallback_cb' => function() {
                                echo '<ul class="footer-menu">';
                                echo '<li><a href="' . home_url('/events/') . '">イベント一覧</a></li>';
                                echo '<li><a href="' . home_url('/stories/') . '">ストーリー</a></li>';
                                echo '<li><a href="' . home_url('/goods/') . '">グッズ</a></li>';
                                echo '<li><a href="' . home_url('/about/') . '">私たちについて</a></li>';
                                echo '</ul>';
                            }
                        ));
                        ?>
                    </div>
                <?php endif; ?>
                
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
                            'orderby' => 'date',
                            'order' => 'DESC'
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
                                        ?>
                                            <span class="event-date"><?php echo esc_html($event_date); ?></span>
                                        <?php endif; ?>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        <?php
                        wp_reset_postdata();
                        else :
                        ?>
                            <p>現在予定されているイベントはありません。</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (is_active_sidebar('footer-3')) : ?>
                    <div class="footer-column">
                        <?php dynamic_sidebar('footer-3'); ?>
                    </div>
                <?php else : ?>
                    <div class="footer-column">
                        <h3>お問い合わせ</h3>
                        <ul class="contact-info">
                            <li><i class="fas fa-envelope"></i> info@arigatou-club.com</li>
                            <li><i class="fas fa-phone"></i> 03-1234-5678</li>
                            <li><i class="fas fa-map-marker-alt"></i> 東京都千代田区</li>
                        </ul>
                        <a href="<?php echo home_url('/contact/'); ?>" class="btn btn-secondary">お問い合わせフォーム</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
                <ul class="footer-links">
                    <li><a href="<?php echo home_url('/privacy/'); ?>">プライバシーポリシー</a></li>
                    <li><a href="<?php echo home_url('/terms/'); ?>">利用規約</a></li>
                    <li><a href="<?php echo home_url('/sitemap/'); ?>">サイトマップ</a></li>
                </ul>
            </div>
        </div>
    </footer>
</div>

<?php wp_footer(); ?>

</body>
</html>