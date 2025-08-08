<?php
/**
 * フロントページテンプレート
 */

get_header(); ?>

<main id="main" class="site-main">
    
    <!-- ヒーローセクション -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title"><?php echo esc_html(get_theme_mod('hero_title', 'ありがとうで世界を満たそう')); ?></h1>
            <p class="hero-subtitle"><?php echo esc_html(get_theme_mod('hero_subtitle', '感謝の心を広げるコミュニティ')); ?></p>
            <div class="hero-buttons">
                <a href="#events" class="btn btn-primary">イベントに参加する</a>
                <a href="#about" class="btn btn-secondary">詳しく見る</a>
            </div>
        </div>
    </section>
    
    <!-- イベント案内セクション -->
    <section id="events" class="section events-section">
        <div class="container">
            <h2 class="section-title">イベント案内</h2>
            <p class="section-description">ありがとうカフェ、セミナー、コーチングなど様々なイベントを開催しています</p>
            
            <?php
            $events = new WP_Query(array(
                'post_type' => 'event',
                'posts_per_page' => 6,
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
            
            if ($events->have_posts()) : ?>
                <div class="card-grid">
                    <?php while ($events->have_posts()) : $events->the_post(); 
                        $event_date = get_post_meta(get_the_ID(), '_event_date', true);
                        $event_time = get_post_meta(get_the_ID(), '_event_time', true);
                        $event_location = get_post_meta(get_the_ID(), '_event_location', true);
                        $event_fee = get_post_meta(get_the_ID(), '_event_fee', true);
                    ?>
                        <div class="card event-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="card-image">
                                    <?php the_post_thumbnail('medium'); ?>
                                </div>
                            <?php endif; ?>
                            <h3 class="card-title"><?php the_title(); ?></h3>
                            <div class="event-meta">
                                <?php if ($event_date) : ?>
                                    <p class="event-date"><i class="far fa-calendar"></i> <?php echo esc_html($event_date); ?></p>
                                <?php endif; ?>
                                <?php if ($event_time) : ?>
                                    <p class="event-time"><i class="far fa-clock"></i> <?php echo esc_html($event_time); ?></p>
                                <?php endif; ?>
                                <?php if ($event_location) : ?>
                                    <p class="event-location"><i class="fas fa-map-marker-alt"></i> <?php echo esc_html($event_location); ?></p>
                                <?php endif; ?>
                                <?php if ($event_fee) : ?>
                                    <p class="event-fee"><i class="fas fa-yen-sign"></i> <?php echo esc_html($event_fee); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="card-content">
                                <?php the_excerpt(); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="btn btn-small">詳細を見る</a>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="section-footer">
                    <a href="<?php echo get_post_type_archive_link('event'); ?>" class="btn">すべてのイベントを見る</a>
                </div>
            <?php else : ?>
                <p class="no-content">現在予定されているイベントはありません。</p>
            <?php endif;
            wp_reset_postdata();
            ?>
        </div>
    </section>
    
    <!-- ストーリーセクション -->
    <section id="stories" class="section story-section">
        <div class="container">
            <h2 class="section-title">ありがとう物語</h2>
            <p class="section-description">感謝のエピソード、ブログ、業界の秘話をお届けします</p>
            
            <?php
            $stories = new WP_Query(array(
                'post_type' => 'story',
                'posts_per_page' => 4
            ));
            
            if ($stories->have_posts()) : ?>
                <div class="story-grid">
                    <?php while ($stories->have_posts()) : $stories->the_post(); ?>
                        <article class="story-item">
                            <h3 class="story-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <div class="story-meta">
                                <span class="story-author"><?php the_author(); ?></span>
                                <span class="story-date"><?php echo get_the_date(); ?></span>
                            </div>
                            <div class="story-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="read-more">続きを読む →</a>
                        </article>
                    <?php endwhile; ?>
                </div>
                <div class="section-footer">
                    <a href="<?php echo get_post_type_archive_link('story'); ?>" class="btn">すべてのストーリーを見る</a>
                </div>
            <?php else : ?>
                <p class="no-content">ストーリーはまだ投稿されていません。</p>
            <?php endif;
            wp_reset_postdata();
            ?>
        </div>
    </section>
    
    <!-- スポンサーセクション -->
    <section id="sponsors" class="section sponsor-section">
        <div class="container">
            <h2 class="section-title">ありがとう企業</h2>
            <p class="section-description">私たちの活動を支援してくださる企業様</p>
            
            <?php
            $sponsors = new WP_Query(array(
                'post_type' => 'sponsor',
                'posts_per_page' => -1
            ));
            
            if ($sponsors->have_posts()) : ?>
                <div class="sponsor-grid">
                    <?php while ($sponsors->have_posts()) : $sponsors->the_post(); ?>
                        <div class="sponsor-item">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" class="sponsor-logo">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            <?php else : ?>
                                <h3 class="sponsor-name">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <p class="no-content">スポンサー情報は準備中です。</p>
            <?php endif;
            wp_reset_postdata();
            ?>
            
            <div class="section-footer">
                <p>スポンサーとして支援をご検討の企業様は、<a href="<?php echo home_url('/contact/'); ?>">お問い合わせ</a>ください。</p>
            </div>
        </div>
    </section>
    
    <!-- グッズセクション -->
    <section id="goods" class="section goods-section">
        <div class="container">
            <h2 class="section-title">ありがとうグッズ</h2>
            <p class="section-description">感謝の気持ちを形にしたオリジナルグッズ</p>
            
            <?php
            $goods = new WP_Query(array(
                'post_type' => 'goods',
                'posts_per_page' => 6
            ));
            
            if ($goods->have_posts()) : ?>
                <div class="goods-grid">
                    <?php while ($goods->have_posts()) : $goods->the_post(); 
                        $goods_price = get_post_meta(get_the_ID(), '_goods_price', true);
                        $goods_link = get_post_meta(get_the_ID(), '_goods_link', true);
                    ?>
                        <div class="goods-item">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" alt="<?php the_title(); ?>" class="goods-image">
                            <?php endif; ?>
                            <h3 class="goods-title"><?php the_title(); ?></h3>
                            <?php if ($goods_price) : ?>
                                <p class="goods-price"><?php echo esc_html($goods_price); ?></p>
                            <?php endif; ?>
                            <?php if ($goods_link) : ?>
                                <a href="<?php echo esc_url($goods_link); ?>" class="btn btn-small" target="_blank" rel="noopener">購入する</a>
                            <?php else : ?>
                                <a href="<?php the_permalink(); ?>" class="btn btn-small">詳細を見る</a>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="section-footer">
                    <a href="<?php echo get_post_type_archive_link('goods'); ?>" class="btn">すべてのグッズを見る</a>
                </div>
            <?php else : ?>
                <p class="no-content">グッズは準備中です。</p>
            <?php endif;
            wp_reset_postdata();
            ?>
        </div>
    </section>
    
    <!-- アバウトセクション -->
    <section id="about" class="section about-section">
        <div class="container">
            <h2 class="section-title">ありがとう倶楽部とは</h2>
            
            <div class="about-content">
                <div class="about-text">
                    <h3>私たちの目的</h3>
                    <p>ありがとう倶楽部は、日常の中で忘れがちな「感謝の心」を大切にし、それを広げていくことを目的としたコミュニティです。「ありがとう」という言葉には、人と人をつなぎ、心を温かくする不思議な力があります。</p>
                    
                    <h3>活動内容</h3>
                    <p>定期的なイベント開催、感謝のエピソード共有、コーチングセッションなどを通じて、メンバー同士が互いに支え合い、成長できる場を提供しています。</p>
                    
                    <h3>未来へのビジョン</h3>
                    <p>私たちは、「ありがとう」の輪を日本全国、そして世界へと広げていきたいと考えています。一人ひとりの小さな感謝の気持ちが集まれば、きっと大きな力となり、より良い社会を作ることができると信じています。</p>
                </div>
                
                <div class="about-features">
                    <div class="feature-item">
                        <i class="fas fa-heart"></i>
                        <h4>感謝の心</h4>
                        <p>日々の生活の中で感謝の気持ちを大切にします</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-users"></i>
                        <h4>コミュニティ</h4>
                        <p>同じ価値観を持つ仲間とつながります</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-globe"></i>
                        <h4>社会貢献</h4>
                        <p>感謝の輪を広げて社会に貢献します</p>
                    </div>
                </div>
                
                <div class="about-cta">
                    <h3>一緒に「ありがとう」を広げませんか？</h3>
                    <p>ありがとう倶楽部は、いつでも新しいメンバーを歓迎しています。</p>
                    <a href="<?php echo home_url('/membership/'); ?>" class="btn btn-primary">メンバー登録</a>
                </div>
            </div>
        </div>
    </section>
    
</main>

<?php get_footer(); ?>