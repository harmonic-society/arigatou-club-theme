<?php
/**
 * ブログ記事詳細ページテンプレート
 */

get_header(); ?>

<main id="main" class="site-main single-blog">
    
    <?php while (have_posts()) : the_post(); ?>
    
    <!-- ブログヘッダー -->
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="blog-single-header">
            <?php
            $categories = get_the_category();
            if (!empty($categories)) : ?>
                <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="blog-single-category">
                    <?php echo esc_html($categories[0]->name); ?>
                </a>
            <?php endif; ?>
            
            <h1 class="blog-single-title"><?php the_title(); ?></h1>
            
            <div class="blog-single-meta">
                <div class="blog-meta-item">
                    <i class="far fa-calendar"></i>
                    <time datetime="<?php echo get_the_date('Y-m-d'); ?>">
                        <?php echo get_the_date('Y年n月j日'); ?>
                    </time>
                </div>
                <div class="blog-meta-item">
                    <i class="far fa-user"></i>
                    <span><?php the_author(); ?></span>
                </div>
                <div class="blog-meta-item">
                    <i class="far fa-clock"></i>
                    <span><?php echo arigatou_club_get_reading_time(); ?> 分で読めます</span>
                </div>
                <?php if (get_comments_number() > 0) : ?>
                <div class="blog-meta-item">
                    <i class="far fa-comments"></i>
                    <span><?php comments_number('0 コメント', '1 コメント', '% コメント'); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- ブログ本文 -->
        <div class="blog-content-wrapper">
            <?php if (has_post_thumbnail()) : ?>
                <div class="blog-featured-image">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>
            
            <div class="blog-content">
                <?php the_content(); ?>
            </div>
            
            <?php
            // タグの表示
            $tags = get_the_tags();
            if ($tags) : ?>
                <div class="blog-tags">
                    <i class="fas fa-tags"></i>
                    <?php foreach ($tags as $tag) : ?>
                        <a href="<?php echo get_tag_link($tag->term_id); ?>" class="blog-tag">
                            #<?php echo esc_html($tag->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- 記事下部のアクション -->
        <div class="blog-actions">
            <!-- シェアボタン -->
            <div class="blog-share">
                <h3 class="share-title">この記事をシェア</h3>
                <div class="share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" 
                       target="_blank" 
                       class="share-btn facebook"
                       rel="noopener">
                        <i class="fab fa-facebook-f"></i>
                        Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" 
                       target="_blank" 
                       class="share-btn twitter"
                       rel="noopener">
                        <i class="fab fa-twitter"></i>
                        Twitter
                    </a>
                    <a href="https://line.me/R/msg/text/?<?php the_title(); ?>%0D%0A<?php the_permalink(); ?>" 
                       target="_blank" 
                       class="share-btn line"
                       rel="noopener">
                        <i class="fab fa-line"></i>
                        LINE
                    </a>
                </div>
            </div>
            
            <!-- 著者情報 -->
            <div class="author-box">
                <div class="author-avatar-large">
                    <?php 
                    $author_name = get_the_author();
                    echo mb_substr($author_name, 0, 1);
                    ?>
                </div>
                <div class="author-info">
                    <h4><?php the_author(); ?></h4>
                    <div class="author-bio">
                        <?php echo get_the_author_meta('description'); ?>
                        <?php if (!get_the_author_meta('description')) : ?>
                            ありがとう倶楽部のメンバーとして、感謝の心を大切にしながら記事を書いています。
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 記事ナビゲーション -->
        <nav class="post-navigation">
            <div class="nav-links">
                <div class="nav-previous">
                    <?php previous_post_link('%link', '<span class="nav-subtitle">← 前の記事</span><span class="nav-title">%title</span>'); ?>
                </div>
                <div class="nav-next">
                    <?php next_post_link('%link', '<span class="nav-subtitle">次の記事 →</span><span class="nav-title">%title</span>'); ?>
                </div>
            </div>
        </nav>
        
        <!-- 関連記事 -->
        <?php
        $categories = wp_get_post_categories($post->ID);
        if ($categories) {
            $related_args = array(
                'category__in' => $categories,
                'post__not_in' => array($post->ID),
                'posts_per_page' => 3,
                'ignore_sticky_posts' => 1
            );
            
            $related_posts = new WP_Query($related_args);
            
            if ($related_posts->have_posts()) : ?>
                <section class="related-posts">
                    <h2 class="related-posts-title">関連記事</h2>
                    <div class="related-posts-grid">
                        <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                            <article class="blog-card-new">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="blog-card-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium'); ?>
                                        </a>
                                        <?php
                                        $categories = get_the_category();
                                        if (!empty($categories)) : ?>
                                            <span class="blog-category-badge">
                                                <?php echo esc_html($categories[0]->name); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="blog-card-body">
                                    <div class="blog-date">
                                        <?php echo get_the_date('Y.m.d'); ?>
                                    </div>
                                    
                                    <h3 class="blog-card-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    
                                    <div class="blog-card-excerpt">
                                        <?php echo wp_trim_words(get_the_excerpt(), 50, '...'); ?>
                                    </div>
                                    
                                    <div class="blog-card-footer">
                                        <a href="<?php the_permalink(); ?>" class="read-more-btn">続きを読む</a>
                                        <div class="blog-author">
                                            <div class="author-avatar">
                                                <?php echo mb_substr(get_the_author(), 0, 1); ?>
                                            </div>
                                            <span><?php the_author(); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </section>
            <?php endif;
            wp_reset_postdata();
        }
        ?>
        
        <!-- コメント -->
        <?php
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
        ?>
        
    </article>
    
    <?php endwhile; ?>
    
</main>

<?php get_footer(); ?>