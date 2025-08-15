<?php
/**
 * 協賛企業詳細ページテンプレート
 */

get_header(); ?>

<main id="main" class="site-main single-sponsor">
    
    <?php while (have_posts()) : the_post();
        $company_url = get_post_meta(get_the_ID(), '_sponsor_url', true);
        $industry = get_post_meta(get_the_ID(), '_sponsor_industry', true);
        $sponsor_level = get_post_meta(get_the_ID(), '_sponsor_level', true);
        $sponsor_since = get_post_meta(get_the_ID(), '_sponsor_since', true);
        
        // スポンサーレベルの表示名
        $level_names = array(
            'platinum' => 'プラチナスポンサー',
            'gold' => 'ゴールドスポンサー',
            'silver' => 'シルバースポンサー',
            'bronze' => 'ブロンズスポンサー',
            '' => 'サポーター企業'
        );
        $level_display = isset($level_names[$sponsor_level]) ? $level_names[$sponsor_level] : 'サポーター企業';
        ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <!-- ヘッダーセクション -->
            <section class="sponsor-header">
                <div class="wa-pattern-overlay"></div>
                <div class="container">
                    <div class="sponsor-header-content">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="sponsor-logo-large">
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="sponsor-info">
                            <div class="sponsor-level-badge <?php echo esc_attr($sponsor_level); ?>">
                                <?php echo esc_html($level_display); ?>
                            </div>
                            
                            <h1 class="sponsor-title"><?php the_title(); ?></h1>
                            
                            <div class="sponsor-meta">
                                <?php if ($industry) : ?>
                                    <div class="meta-item">
                                        <i class="fas fa-building"></i>
                                        <span><?php echo esc_html($industry); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($sponsor_since) : ?>
                                    <div class="meta-item">
                                        <i class="fas fa-calendar-check"></i>
                                        <span>協賛開始: <?php echo date('Y年n月', strtotime($sponsor_since)); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($company_url) : ?>
                                    <div class="meta-item">
                                        <a href="<?php echo esc_url($company_url); ?>" target="_blank" rel="noopener" class="company-link">
                                            <i class="fas fa-globe"></i>
                                            <span>企業ウェブサイト</span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- コンテンツセクション -->
            <section class="sponsor-content-section section">
                <div class="container">
                    <div class="sponsor-content-wrapper">
                        
                        <!-- メインコンテンツ -->
                        <div class="sponsor-main-content">
                            <div class="content-box">
                                <h2 class="content-title">企業紹介</h2>
                                <div class="sponsor-description">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                            
                            <?php
                            // カテゴリーの取得
                            $categories = get_the_terms(get_the_ID(), 'sponsor_category');
                            if ($categories && !is_wp_error($categories)) : ?>
                                <div class="content-box">
                                    <h3 class="content-subtitle">協賛カテゴリー</h3>
                                    <div class="sponsor-categories">
                                        <?php foreach ($categories as $category) : ?>
                                            <a href="<?php echo get_term_link($category); ?>" class="category-tag">
                                                <?php echo esc_html($category->name); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- サイドバー -->
                        <aside class="sponsor-sidebar">
                            <!-- メッセージボックス -->
                            <div class="sidebar-box message-box">
                                <h3 class="box-title">感謝のメッセージ</h3>
                                <p class="message-text">
                                    <?php the_title(); ?>様には、ありがとう倶楽部の活動を温かくご支援いただいております。
                                    心より感謝申し上げます。
                                </p>
                                <div class="thank-you-icon">🙏</div>
                            </div>
                            
                            <?php if ($company_url) : ?>
                                <!-- ウェブサイトリンク -->
                                <div class="sidebar-box">
                                    <h3 class="box-title">企業情報</h3>
                                    <a href="<?php echo esc_url($company_url); ?>" target="_blank" rel="noopener" class="website-button">
                                        <i class="fas fa-external-link-alt"></i>
                                        公式ウェブサイトを見る
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <!-- シェアボタン -->
                            <div class="sidebar-box">
                                <h3 class="box-title">シェア</h3>
                                <div class="share-buttons">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" 
                                       target="_blank" 
                                       class="share-btn facebook"
                                       rel="noopener">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?> - ありがとう倶楽部協賛企業" 
                                       target="_blank" 
                                       class="share-btn twitter"
                                       rel="noopener">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="https://line.me/R/msg/text/?<?php the_title(); ?>%0D%0A<?php the_permalink(); ?>" 
                                       target="_blank" 
                                       class="share-btn line"
                                       rel="noopener">
                                        <i class="fab fa-line"></i>
                                    </a>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>
            
            <!-- 他の協賛企業 -->
            <section class="other-sponsors-section section">
                <div class="container">
                    <h2 class="section-title">その他の協賛企業</h2>
                    
                    <div class="other-sponsors-grid">
                        <?php
                        $other_sponsors = new WP_Query(array(
                            'post_type' => 'sponsor',
                            'posts_per_page' => 6,
                            'post__not_in' => array(get_the_ID()),
                            'orderby' => 'rand'
                        ));
                        
                        if ($other_sponsors->have_posts()) :
                            while ($other_sponsors->have_posts()) : $other_sponsors->the_post();
                                $other_level = get_post_meta(get_the_ID(), '_sponsor_level', true);
                                ?>
                                <div class="other-sponsor-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="other-sponsor-logo">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('thumbnail'); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <h4 class="other-sponsor-name">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h4>
                                    <?php if ($other_level && isset($level_names[$other_level])) : ?>
                                        <span class="other-sponsor-level <?php echo esc_attr($other_level); ?>">
                                            <?php echo esc_html($level_names[$other_level]); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endwhile;
                        endif;
                        wp_reset_postdata();
                        ?>
                    </div>
                    
                    <div class="section-footer">
                        <a href="<?php echo get_post_type_archive_link('sponsor'); ?>" class="btn-more">
                            すべての協賛企業を見る
                        </a>
                    </div>
                </div>
            </section>
            
        </article>
        
    <?php endwhile; ?>
    
</main>

<?php get_footer(); ?>