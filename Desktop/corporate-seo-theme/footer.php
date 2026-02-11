    <footer id="colophon" class="site-footer" role="contentinfo">
        <div class="container">
            <div class="footer-content">
                <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar( 'footer-1' ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar( 'footer-2' ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar( 'footer-3' ); ?>
                    </div>
                <?php endif; ?>

                <div class="footer-widget-area">
                    <h3><?php esc_html_e( '会社情報', 'corporate-seo-pro' ); ?></h3>
                    <?php if ( get_theme_mod( 'company_name' ) ) : ?>
                        <p><strong><?php echo esc_html( get_theme_mod( 'company_name' ) ); ?></strong></p>
                    <?php endif; ?>
                    
                    <?php if ( get_theme_mod( 'company_address' ) ) : ?>
                        <p><?php echo esc_html( get_theme_mod( 'company_address' ) ); ?></p>
                    <?php endif; ?>
                    
                    <?php if ( get_theme_mod( 'company_phone' ) ) : ?>
                        <p>TEL: <a href="tel:<?php echo esc_attr( get_theme_mod( 'company_phone' ) ); ?>"><?php echo esc_html( get_theme_mod( 'company_phone' ) ); ?></a></p>
                    <?php endif; ?>
                    
                    <?php if ( get_theme_mod( 'company_email' ) ) : ?>
                        <p>Email: <a href="mailto:<?php echo esc_attr( get_theme_mod( 'company_email' ) ); ?>"><?php echo esc_html( get_theme_mod( 'company_email' ) ); ?></a></p>
                    <?php endif; ?>
                </div>

            </div>

            <div class="footer-media-section">
                <h3><?php esc_html_e( 'メディアサイト', 'corporate-seo-pro' ); ?></h3>
                <ul class="footer-media-links">
                    <li>
                        <a href="https://harmonic-society.com" target="_blank" rel="noopener noreferrer">
                            <span class="media-link-name">Harmonic Society Records</span>
                            <span class="media-link-desc">アナログレコードの知識ベース。アルバムレビュー、アーティスト紹介、ジャンル解説など。</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://harmonic-society.net" target="_blank" rel="noopener noreferrer">
                            <span class="media-link-name">Harmonic Society Philosophy</span>
                            <span class="media-link-desc">古代ギリシャから現代に至る哲学の思想・哲学者・学派を網羅したオンライン哲学事典。</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://post-web3.com" target="_blank" rel="noopener noreferrer">
                            <span class="media-link-name">Post Web3</span>
                            <span class="media-link-desc">DeFi、NFT、ブロックチェーンインフラに関する専門的な分析・市場動向メディア。</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="footer-bottom">
                <div class="footer-navigation">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer',
                        'menu_id'        => 'footer-menu',
                        'depth'          => 1,
                        'container'      => false,
                        'fallback_cb'    => false,
                    ) );
                    ?>
                </div>
                
                <div class="site-info">
                    <p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'corporate-seo-pro' ); ?></p>
                </div>
            </div>
        </div>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>