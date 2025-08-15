<?php
/**
 * 協賛企業一覧ページテンプレート
 */

get_header(); ?>

<main id="main" class="site-main sponsor-archive">
    
    <!-- ページヘッダー -->
    <section class="sponsor-page-header">
        <div class="wa-pattern-overlay"></div>
        <div class="container">
            <h1 class="sponsor-page-title">協賛企業</h1>
            <p class="sponsor-page-subtitle">ありがとう倶楽部を支えてくださる企業様</p>
        </div>
    </section>
    
    <!-- 協賛企業一覧セクション -->
    <section class="sponsor-list-section section">
        <div class="container">
            
            <?php
            // 協賛レベル別に企業を取得
            $sponsor_levels = array(
                'platinum' => array(
                    'name' => 'プラチナスポンサー',
                    'icon' => '💎',
                    'class' => 'platinum-sponsors'
                ),
                'gold' => array(
                    'name' => 'ゴールドスポンサー',
                    'icon' => '🏆',
                    'class' => 'gold-sponsors'
                ),
                'silver' => array(
                    'name' => 'シルバースポンサー',
                    'icon' => '🥈',
                    'class' => 'silver-sponsors'
                ),
                'bronze' => array(
                    'name' => 'ブロンズスポンサー',
                    'icon' => '🥉',
                    'class' => 'bronze-sponsors'
                ),
                '' => array(
                    'name' => 'サポーター企業',
                    'icon' => '🤝',
                    'class' => 'supporter-sponsors'
                )
            );
            
            foreach ($sponsor_levels as $level_key => $level_info) :
                $sponsors = new WP_Query(array(
                    'post_type' => 'sponsor',
                    'posts_per_page' => -1,
                    'meta_key' => '_sponsor_level',
                    'meta_value' => $level_key,
                    'orderby' => 'title',
                    'order' => 'ASC'
                ));
                
                if ($sponsors->have_posts()) : ?>
                    <div class="sponsor-level-section <?php echo esc_attr($level_info['class']); ?>">
                        <div class="sponsor-level-header">
                            <span class="level-icon"><?php echo $level_info['icon']; ?></span>
                            <h2 class="sponsor-level-title"><?php echo esc_html($level_info['name']); ?></h2>
                        </div>
                        
                        <div class="sponsor-grid">
                            <?php while ($sponsors->have_posts()) : $sponsors->the_post();
                                $company_url = get_post_meta(get_the_ID(), '_sponsor_url', true);
                                $industry = get_post_meta(get_the_ID(), '_sponsor_industry', true);
                                $sponsor_since = get_post_meta(get_the_ID(), '_sponsor_since', true);
                                ?>
                                <article class="sponsor-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="sponsor-logo">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('medium'); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="sponsor-content">
                                        <h3 class="sponsor-name">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        
                                        <?php if ($industry) : ?>
                                            <div class="sponsor-industry">
                                                <i class="fas fa-building"></i>
                                                <span><?php echo esc_html($industry); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($sponsor_since) : ?>
                                            <div class="sponsor-since">
                                                <i class="fas fa-calendar-check"></i>
                                                <span>協賛開始: <?php echo date('Y年n月', strtotime($sponsor_since)); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="sponsor-excerpt">
                                            <?php echo wp_trim_words(get_the_excerpt(), 50, '...'); ?>
                                        </div>
                                        
                                        <div class="sponsor-actions">
                                            <a href="<?php the_permalink(); ?>" class="btn-detail">
                                                詳細を見る
                                            </a>
                                            <?php if ($company_url) : ?>
                                                <a href="<?php echo esc_url($company_url); ?>" target="_blank" rel="noopener" class="btn-website">
                                                    <i class="fas fa-external-link-alt"></i>
                                                    ウェブサイト
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php endif;
                wp_reset_postdata();
            endforeach; ?>
            
            <?php if (!have_posts()) : ?>
                <div class="no-sponsors">
                    <p>現在、協賛企業の情報はありません。</p>
                    <p>協賛にご興味のある企業様は、お問い合わせください。</p>
                    <a href="<?php echo home_url('/contact/'); ?>" class="btn btn-primary">お問い合わせ</a>
                </div>
            <?php endif; ?>
            
        </div>
    </section>
    
    <!-- 協賛募集セクション -->
    <section class="sponsor-cta-section section">
        <div class="container">
            <div class="sponsor-cta-box">
                <h2 class="cta-title">協賛企業募集中</h2>
                <p class="cta-text">
                    ありがとう倶楽部では、私たちの活動に賛同いただける企業様を募集しています。<br>
                    感謝の心を大切にする活動を、一緒に広げていきませんか？
                </p>
                <div class="cta-buttons">
                    <a href="<?php echo home_url('/contact/'); ?>?type=sponsor" class="btn btn-primary">
                        協賛について問い合わせる
                    </a>
                </div>
            </div>
        </div>
    </section>
    
</main>

<?php get_footer(); ?>