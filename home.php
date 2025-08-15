<?php
/**
 * ブログ一覧ページテンプレート
 */

get_header(); ?>

<main id="main" class="site-main wa-style">
    
    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="wa-pattern-overlay"></div>
        <div class="container">
            <h1 class="page-title">ブログ</h1>
            <p class="page-subtitle">感謝のエピソードや日々の気づきをお届けします</p>
        </div>
    </section>
    
    <!-- ブログ一覧セクション -->
    <section class="blog-archive section">
        <div class="container">
            <div class="blog-layout">
                
                <!-- メインコンテンツ -->
                <div class="blog-main">
                    
                    <?php if (have_posts()) : ?>
                        
                        <div class="blog-posts">
                            <?php while (have_posts()) : the_post(); ?>
                                <article class="blog-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="blog-thumbnail">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('medium'); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="blog-content">
                                        <div class="blog-meta">
                                            <span class="blog-date"><?php echo get_the_date('Y年n月j日'); ?></span>
                                            <?php
                                            $categories = get_the_category();
                                            if (!empty($categories)) : ?>
                                                <span class="blog-category">
                                                    <?php foreach ($categories as $category) : ?>
                                                        <a href="<?php echo get_category_link($category->term_id); ?>"><?php echo esc_html($category->name); ?></a>
                                                    <?php endforeach; ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <h2 class="blog-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        
                                        <div class="blog-excerpt">
                                            <?php the_excerpt(); ?>
                                        </div>
                                        
                                        <div class="blog-footer">
                                            <a href="<?php the_permalink(); ?>" class="read-more">続きを読む →</a>
                                            <span class="blog-author">by <?php the_author(); ?></span>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                        
                        <!-- ページネーション -->
                        <div class="pagination">
                            <?php
                            echo paginate_links(array(
                                'prev_text' => '← 前へ',
                                'next_text' => '次へ →',
                            ));
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