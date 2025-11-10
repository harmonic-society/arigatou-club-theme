<?php
/**
 * ブログ一覧ページテンプレート
 */

get_header(); ?>

<main id="main" class="site-main wa-style">
    
    <!-- ページヘッダー -->
    <section class="blog-page-header">
        <div class="wa-pattern-overlay"></div>
        <div class="container">
            <h1 class="blog-page-title">ありがとうブログ</h1>
            <p class="blog-page-subtitle">感謝のエピソードや日々の気づきをお届けします</p>
        </div>
    </section>
    
    <!-- ブログ一覧セクション -->
    <section class="blog-archive section">
        <div class="container">
            <div class="blog-layout">
                
                <!-- メインコンテンツ -->
                <div class="blog-main">
                    
                    <?php if (have_posts()) : ?>
                        
                        <?php
                        // フィーチャード記事（最初の記事）
                        if (is_home() && !is_paged()) :
                            the_post(); ?>
                            <article class="featured-post">
                                <div class="featured-content">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="featured-image">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('large'); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <div class="featured-text">
                                        <span class="featured-label">注目記事</span>
                                        <h2>
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        <div class="excerpt">
                                            <?php echo wp_trim_words(get_the_excerpt(), 80, '...'); ?>
                                        </div>
                                        <a href="<?php the_permalink(); ?>" class="read-more-btn">続きを読む</a>
                                    </div>
                                </div>
                            </article>
                        <?php endif; ?>
                        
                        <div class="blog-grid">
                            <?php while (have_posts()) : the_post(); ?>
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
                                        
                                        <h2 class="blog-card-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        
                                        <div class="blog-card-excerpt">
                                            <?php echo wp_trim_words(get_the_excerpt(), 60, '...'); ?>
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
                        
                        <!-- ページネーション -->
                        <div class="blog-pagination">
                            <?php
                            $pagination_links = paginate_links(array(
                                'prev_text' => '←',
                                'next_text' => '→',
                                'type' => 'array'
                            ));
                            if ($pagination_links) {
                                foreach ($pagination_links as $link) {
                                    echo $link;
                                }
                            }
                            ?>
                        </div>
                        
                    <?php else : ?>
                        
                        <div class="no-posts">
                            <p>まだブログ記事がありません。</p>
                            <p>新しい記事が投稿されましたら、こちらに表示されます。</p>
                        </div>
                        
                    <?php endif; ?>
                    
                </div>
                
                <!-- サイドバー -->
                <aside class="blog-sidebar">
                    
                    <!-- 検索ウィジェット -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">検索</h3>
                        <?php get_search_form(); ?>
                    </div>
                    
                    <!-- カテゴリーウィジェット -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">カテゴリー</h3>
                        <ul class="category-list">
                            <?php
                            wp_list_categories(array(
                                'title_li' => '',
                                'orderby' => 'name',
                                'show_count' => true,
                            ));
                            ?>
                        </ul>
                    </div>
                    
                    <!-- 最新記事ウィジェット -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">最新記事</h3>
                        <ul class="recent-posts">
                            <?php
                            $recent_posts = wp_get_recent_posts(array(
                                'numberposts' => 5,
                                'post_status' => 'publish'
                            ));
                            foreach ($recent_posts as $post) : ?>
                                <li>
                                    <a href="<?php echo get_permalink($post['ID']); ?>">
                                        <span class="post-date"><?php echo date('n/j', strtotime($post['post_date'])); ?></span>
                                        <span class="post-title"><?php echo esc_html($post['post_title']); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <!-- アーカイブウィジェット -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">アーカイブ</h3>
                        <ul class="archive-list">
                            <?php wp_get_archives(array(
                                'type' => 'monthly',
                                'limit' => 12,
                                'show_post_count' => true,
                            )); ?>
                        </ul>
                    </div>
                    
                    <!-- タグクラウドウィジェット -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">タグ</h3>
                        <div class="tag-cloud">
                            <?php wp_tag_cloud(array(
                                'smallest' => 12,
                                'largest' => 18,
                                'unit' => 'px',
                            )); ?>
                        </div>
                    </div>
                    
                </aside>
                
            </div>
        </div>
    </section>
    
</main>

<?php get_footer(); ?>