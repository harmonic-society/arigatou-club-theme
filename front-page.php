<?php
/**
 * フロントページテンプレート
 * ありがとう倶楽部の温かみのある和のデザイン
 */

get_header(); ?>

<main id="main" class="site-main wa-style">
    
    <!-- ヒーローセクション -->
    <section class="hero-section">
        <?php
        // ヒーロー画像を取得
        $hero_query = new WP_Query(array(
            'post_type' => 'hero_slider',
            'posts_per_page' => -1, // すべての画像を取得
            'meta_key' => '_slide_order',
            'orderby' => 'meta_value_num',
            'order' => 'ASC'
        ));
        
        $thumbnails = array(); // Initialize thumbnails array
        
        if ($hero_query->have_posts()) : 
            $hero_posts = array();
            while ($hero_query->have_posts()) : 
                $hero_query->the_post();
                $hero_posts[] = array(
                    'id' => get_the_ID(),
                    'title' => get_post_meta(get_the_ID(), '_slide_title', true) ?: get_the_title(),
                    'subtitle' => get_post_meta(get_the_ID(), '_slide_subtitle', true),
                    'button_text' => get_post_meta(get_the_ID(), '_slide_button_text', true),
                    'button_url' => get_post_meta(get_the_ID(), '_slide_button_url', true),
                    'image_url' => get_the_post_thumbnail_url(get_the_ID(), 'full'),
                    'thumbnail_url' => get_the_post_thumbnail_url(get_the_ID(), 'large')
                );
            endwhile;
            
            // メイン画像とサムネイル画像を設定
            $main_hero = !empty($hero_posts) ? $hero_posts[0] : null;
            
            // サムネイルは常に利用可能なすべての画像を使用（最初の画像を除く）
            // ただし、最大4枚まで
            if (count($hero_posts) > 1) {
                $thumbnails = array_slice($hero_posts, 1, 4);
            } else {
                // 画像が1枚しかない場合は、その画像をサムネイルとしても使用
                $thumbnails = $hero_posts;
            }
            ?>
            
            <!-- メインヒーロー画像 -->
            <?php if ($main_hero) : ?>
                <div class="hero-main">
                    <div class="hero-main-image" style="background-image: url('<?php echo esc_url($main_hero['image_url']); ?>')">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <div class="container">
                                <h1 class="hero-title"><?php echo esc_html($main_hero['title']); ?></h1>
                                <?php if ($main_hero['subtitle']) : ?>
                                    <p class="hero-subtitle"><?php echo esc_html($main_hero['subtitle']); ?></p>
                                <?php endif; ?>
                                <?php if ($main_hero['button_text'] && $main_hero['button_url']) : ?>
                                    <div class="hero-buttons">
                                        <a href="<?php echo esc_url($main_hero['button_url']); ?>" class="btn-hero-primary">
                                            <?php echo esc_html($main_hero['button_text']); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- サムネイルギャラリー -->
            <?php if (!empty($thumbnails)) : ?>
                <div class="hero-thumbnails">
                    <div class="container">
                        <!-- デスクトップ用グリッド -->
                        <div class="thumbnails-grid desktop-only">
                            <?php foreach ($thumbnails as $thumb) : ?>
                                <div class="thumbnail-item">
                                    <div class="thumbnail-image" style="background-image: url('<?php echo esc_url($thumb['thumbnail_url']); ?>')">
                                        <div class="thumbnail-overlay"></div>
                                        <div class="thumbnail-content">
                                            <h3 class="thumbnail-title"><?php echo esc_html($thumb['title']); ?></h3>
                                            <?php if ($thumb['subtitle']) : ?>
                                                <p class="thumbnail-subtitle"><?php echo esc_html($thumb['subtitle']); ?></p>
                                            <?php endif; ?>
                                            <?php if ($thumb['button_url']) : ?>
                                                <a href="<?php echo esc_url($thumb['button_url']); ?>" class="thumbnail-link">
                                                    詳細を見る →
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- モバイル用スライダー -->
                        <div class="swiper thumbnails-slider mobile-only">
                            <div class="swiper-wrapper">
                                <?php foreach ($thumbnails as $thumb) : ?>
                                    <div class="swiper-slide">
                                        <div class="thumbnail-item">
                                            <div class="thumbnail-image" style="background-image: url('<?php echo esc_url($thumb['thumbnail_url']); ?>')">
                                                <div class="thumbnail-overlay"></div>
                                                <div class="thumbnail-content">
                                                    <h3 class="thumbnail-title"><?php echo esc_html($thumb['title']); ?></h3>
                                                    <?php if ($thumb['subtitle']) : ?>
                                                        <p class="thumbnail-subtitle"><?php echo esc_html($thumb['subtitle']); ?></p>
                                                    <?php endif; ?>
                                                    <?php if ($thumb['button_url']) : ?>
                                                        <a href="<?php echo esc_url($thumb['button_url']); ?>" class="thumbnail-link">
                                                            詳細を見る →
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
        <?php else : ?>
            <!-- デフォルトのヒーローセクション -->
            <div class="default-hero">
                <div class="wa-pattern-overlay"></div>
                <div class="container">
                    <h1 class="hero-title">ありがとう倶楽部</h1>
                    <p class="hero-subtitle">感謝の言葉で広がれ、お好み焼き社会</p>
                </div>
            </div>
        <?php endif;
        wp_reset_postdata();
        ?>
    </section>

    <!-- ニュースティッカー -->
    <?php
    $news_query = new WP_Query(array(
        'post_type' => 'news_ticker',
        'posts_per_page' => -1,
        'meta_key' => '_news_ticker_order',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => '_news_ticker_active',
                'value' => '1',
                'compare' => '='
            )
        )
    ));

    if ($news_query->have_posts()) : ?>
        <section class="news-ticker-section">
            <div class="news-ticker-container">
                <div class="news-ticker-label">
                    <i class="fas fa-bullhorn"></i>
                    <span>お知らせ</span>
                </div>
                <div class="news-ticker-content">
                    <div class="news-ticker-items">
                        <?php while ($news_query->have_posts()) : $news_query->the_post();
                            $news_url = get_post_meta(get_the_ID(), '_news_ticker_url', true);
                            $news_date = get_post_meta(get_the_ID(), '_news_ticker_date', true);
                        ?>
                            <div class="news-ticker-item">
                                <?php if ($news_url) : ?>
                                    <a href="<?php echo esc_url($news_url); ?>">
                                        <?php if ($news_date) : ?>
                                            <span class="news-date"><?php echo date('Y.m.d', strtotime($news_date)); ?></span>
                                        <?php endif; ?>
                                        <span class="news-text"><?php the_title(); ?></span>
                                    </a>
                                <?php else : ?>
                                    <?php if ($news_date) : ?>
                                        <span class="news-date"><?php echo date('Y.m.d', strtotime($news_date)); ?></span>
                                    <?php endif; ?>
                                    <span class="news-text"><?php the_title(); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif;
    wp_reset_postdata();
    ?>

    <!-- ミッションステートメントセクション -->
    <section class="mission-statement-section section">
        <div class="container">
            <div class="section-header">
                <span class="section-marker">◯</span>
                <h2 class="section-title">広がれ! お好み焼き社会<br>ありがとう倶楽部</h2>
            </div>

            <div class="mission-content">
                <div class="vision-block">
                    <h3 class="mission-subtitle">ヴィジョン</h3>
                    <p class="vision-text">平たく・まあるく・温かい、人の違い・天才活かし合いのお好み焼き社会を目指す</p>
                </div>

                <div class="mission-block">
                    <h3 class="mission-subtitle">ミッション</h3>
                    <ul class="mission-list">
                        <li>人が持つオリジナルの天才を発見、発揮し、人同士が尊重しあって、活かし合い、助け合うための場を創っていく</li>
                        <li>身の回りに溢れる「ありがとう」に氣づいて、それを表現する人を増やす</li>
                    </ul>
                </div>

                <div class="recommend-block">
                    <h3 class="mission-subtitle">こんな人におすすめ</h3>
                    <ul class="recommend-list">
                        <li>向上心があり 自分のことをもっと詳しく知り、自分の天才を生かしたい人</li>
                        <li>悩みがあり 自分のことを 誠実に助けてくれる人と知り合いたい人</li>
                        <li>安心安全の売買をするための相手を見つけたい人</li>
                    </ul>
                </div>

                <div class="mission-footer">
                    <p class="mission-invitation">ありがとう倶楽部には、新たな出会い・氣づきの場があります。<br>ご一緒に楽しい場を作ってください。</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ブログセクション -->
    <section class="home-blog-section section">
        <div class="container">
            <div class="section-header">
                <span class="section-marker">◯</span>
                <h2 class="section-title">最新のブログ</h2>
                <p class="section-subtitle">感謝のエピソードや日々の気づきをお届けします</p>
            </div>
            
            <div class="home-blog-grid">
                <?php
                $recent_posts = new WP_Query(array(
                    'posts_per_page' => 3,
                    'post_status' => 'publish'
                ));
                
                if ($recent_posts->have_posts()) :
                    while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                        <article class="home-blog-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="home-blog-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                    <?php
                                    $categories = get_the_category();
                                    if (!empty($categories)) : ?>
                                        <span class="blog-category-label">
                                            <?php echo esc_html($categories[0]->name); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="home-blog-content">
                                <div class="blog-meta">
                                    <span class="blog-date"><?php echo get_the_date('Y.m.d'); ?></span>
                                </div>
                                
                                <h3 class="home-blog-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                
                                <div class="home-blog-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), 50, '...'); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="read-more-link">
                                    続きを読む →
                                </a>
                            </div>
                        </article>
                    <?php endwhile;
                    wp_reset_postdata();
                else : ?>
                    <p class="no-posts-message">まだブログ記事がありません。</p>
                <?php endif; ?>
            </div>
            
            <?php if ($recent_posts->have_posts()) : ?>
                <div class="section-footer">
                    <a href="<?php echo home_url('/blog/'); ?>" class="btn-more">
                        もっと見る
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- イベントセクション -->
    <section class="home-events-section section">
        <div class="container">
            <div class="section-header">
                <span class="section-marker">◯</span>
                <h2 class="section-title">開催予定のイベント</h2>
                <p class="section-subtitle">ありがとうカフェやセミナーなどの最新情報</p>
            </div>
            
            <div class="home-events-grid">
                <?php
                $upcoming_events = new WP_Query(array(
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
                
                if ($upcoming_events->have_posts()) :
                    while ($upcoming_events->have_posts()) : $upcoming_events->the_post();
                        $event_date = get_post_meta(get_the_ID(), '_event_date', true);
                        $event_time = get_post_meta(get_the_ID(), '_event_time', true);
                        $event_location = get_post_meta(get_the_ID(), '_event_location', true);
                        $event_fee = get_post_meta(get_the_ID(), '_event_fee', true);
                        ?>
                        <article class="home-event-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="home-event-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="home-event-content">
                                <?php if ($event_date) : ?>
                                    <div class="event-date-badge">
                                        <span class="event-month"><?php echo date('n月', strtotime($event_date)); ?></span>
                                        <span class="event-day"><?php echo date('j', strtotime($event_date)); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <h3 class="home-event-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                
                                <div class="event-details">
                                    <?php if ($event_time) : ?>
                                        <div class="event-detail-item">
                                            <i class="far fa-clock"></i>
                                            <span><?php echo esc_html($event_time); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($event_location) : ?>
                                        <div class="event-detail-item">
                                            <i class="far fa-map-marker-alt"></i>
                                            <span><?php echo esc_html($event_location); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($event_fee) : ?>
                                        <div class="event-detail-item">
                                            <i class="far fa-yen-sign"></i>
                                            <span><?php echo esc_html($event_fee); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="home-event-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), 40, '...'); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="event-more-link">
                                    詳細を見る →
                                </a>
                            </div>
                        </article>
                    <?php endwhile;
                    wp_reset_postdata();
                else : ?>
                    <p class="no-events-message">現在、開催予定のイベントはありません。</p>
                <?php endif; ?>
            </div>
            
            <div class="section-footer">
                <a href="<?php echo get_post_type_archive_link('event'); ?>" class="btn-more">
                    もっと見る
                </a>
            </div>
        </div>
    </section>
    
    <!-- 活動内容セクション -->
    <?php
    // カスタマイザーから活動内容セクション画像を取得
    $activities_image = get_theme_mod('activities_section_image');
    ?>
    <section class="activities-section section" <?php if ($activities_image) : ?>style="background-image: url('<?php echo esc_url($activities_image); ?>');"<?php endif; ?>>
        <div class="activities-section-overlay">
            <div class="container">
                <div class="section-header">
                    <span class="section-marker">◯</span>
                    <h2 class="section-title">活動内容</h2>
                </div>

                <div class="activities-grid">
                <div class="activity-item">
                    <div class="activity-icon"><i class="fas fa-mug-hot"></i></div>
                    <h3>ありがとうカフェ</h3>
                    <p class="activity-description">有料会員向けの座談会。お互いの天才を探したり、お互いの得意や仕事を紹介してありがとうを大事に、助け合い。リアルとオンラインで開催。</p>
                </div>
                <div class="activity-item">
                    <div class="activity-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <h3>ありがとうセミナー</h3>
                    <p class="activity-description">ありがとう倶楽部に参加してくれてる皆さんの中で、天才を発揮している人が皆さんの知りたいことでセミナーを行ってくれます。</p>
                </div>
                <div class="activity-item">
                    <div class="activity-icon"><i class="fas fa-hands-helping"></i></div>
                    <h3>ありがとう体験ワークショップ</h3>
                    <p class="activity-description">ありがとうを感じるには体感が大事。体感を得るには体験が大事。多くの体験体感をしましょう。</p>
                </div>
                <div class="activity-item">
                    <div class="activity-icon"><i class="fas fa-gift"></i></div>
                    <h3>ありがとうグッズの販売</h3>
                    <p class="activity-description">ありがとうカード 2025、野口さんのカレンダー、野口さんの絵本、ありがとう T シャツ、ありがとうシールなど</p>
                </div>
                <div class="activity-item">
                    <div class="activity-icon"><i class="fab fa-facebook"></i></div>
                    <h3>ありがとう Facebook グループ</h3>
                    <p class="activity-description">有料会員無料会員を問わず、ありがとうを大事にする人たちが自由に交流できるFacebookのグループです。</p>
                </div>
                <div class="activity-item">
                    <div class="activity-icon"><i class="fas fa-blog"></i></div>
                    <h3>ありがとうブログ</h3>
                    <p class="activity-description">会員の皆様が感じる自分自身や身の回りのヒト・モノ・コトに対するありがとうをまとめて記事にしていきます。</p>
                </div>
            </div>
        </div>
        </div>
    </section>
    
    <!-- 参加方法セクション -->
    <section class="membership-section section">
        <div class="container">
            <div class="section-header">
                <span class="section-marker">◯</span>
                <h2 class="section-title">参加方法</h2>
            </div>

            <?php
            // カスタマイザーから参加方法セクション画像を取得
            $membership_image = get_theme_mod('membership_section_image');
            if ($membership_image) :
            ?>
                <div class="membership-header-image">
                    <img src="<?php echo esc_url($membership_image); ?>" alt="参加方法" loading="lazy">
                </div>
            <?php endif; ?>

            <div class="membership-options">
                <div class="membership-card free">
                    <div class="membership-header">
                        <h3>無料会員</h3>
                    </div>
                    <div class="membership-body">
                        <p>Facebook グループに登録してありがとう倶楽部の活動を知ることができます。またスポットで料金を払うことでありがとうカフェや セミナーに参加することもできます。</p>
                    </div>
                </div>
                
                <div class="membership-card premium">
                    <div class="membership-header">
                        <h3>有料会員</h3>
                        <p class="membership-price">月額1,000円　年額10,000円</p>
                        <p class="membership-note">（年金生活者、学生は無料）</p>
                    </div>
                    <div class="membership-body">
                        <p>各地で開催される ありがとうカフェに、どこでも、何度でも参加することが可能です。またセミナーやグッズは 会員価格で参加したり購入したりすることが可能です。</p>
                    </div>
                </div>
            </div>
            
            <div class="membership-notice">
                <span class="note-marker">※</span>
                <p>会員の皆さんに対して、ご自身のご商売やご活動を積極的にご案内いただきます。しかしながら、会員の皆様に対して不誠実な対応や、嘘偽りの内容があってトラブルに発展したような場合、情報を共有させていただき、場合によってはご退会いただくこともございます。お互い様の精神で、氣持ちの良い関係性を保てる倶楽部にしてまいりましょう。</p>
            </div>
        </div>
    </section>
    
    <!-- 企業の皆様へセクション -->
    <section class="corporate-section section">
        <div class="container">
            <div class="section-header">
                <span class="section-marker">◯</span>
                <h2 class="section-title">企業の皆様へ</h2>
            </div>

            <?php
            // カスタマイザーから企業の皆様へセクション画像を取得
            $corporate_image = get_theme_mod('corporate_section_image');
            if ($corporate_image) :
            ?>
                <div class="corporate-header-image">
                    <img src="<?php echo esc_url($corporate_image); ?>" alt="企業の皆様へ" loading="lazy">
                </div>
            <?php endif; ?>

            <div class="corporate-content">
                <p class="corporate-intro">ありがとう を企業理念として大切にされている企業の皆様にお願いがあります。</p>
                
                <p class="corporate-appeal">是非ありがとう倶楽部と連携していただきたく存じます。</p>
                
                <p>ありがとう倶楽部に協賛いただくことで広告として、当ホームページをご活用していただけないでしょうか。</p>
                
                <p>このホームページに皆様の考え方あり方やり方を動画に撮って掲載することで、ありがとうを大事に活動をされている会員の皆様へ 訴求することができます。</p>
                
                <p class="corporate-closing">是非ご一緒に、ありがとうを大切にする 住みよい大和（だいわ）の社会を作っていきましょう。</p>
                
                <div class="corporate-cta">
                    <a href="<?php echo home_url('/contact/'); ?>" class="btn btn-primary">お問い合わせはこちら</a>
                </div>
            </div>
        </div>
    </section>
    
</main>

<?php get_footer(); ?>