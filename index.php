<?php
/**
 * メインテンプレートファイル
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <div class="content-area">
            <?php if (have_posts()) : ?>
                
                <?php if (is_home() && !is_front_page()) : ?>
                    <header class="page-header">
                        <h1 class="page-title">ブログ</h1>
                    </header>
                <?php endif; ?>
                
                <div class="posts-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-content">
                                <header class="entry-header">
                                    <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>
                                    
                                    <div class="entry-meta">
                                        <span class="posted-date">
                                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                <?php echo get_the_date(); ?>
                                            </time>
                                        </span>
                                        <span class="posted-author">
                                            <?php the_author(); ?>
                                        </span>
                                    </div>
                                </header>
                                
                                <div class="entry-summary">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <footer class="entry-footer">
                                    <a href="<?php the_permalink(); ?>" class="read-more">続きを読む →</a>
                                </footer>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <div class="pagination">
                    <?php
                    the_posts_pagination(array(
                        'mid_size' => 2,
                        'prev_text' => '← 前へ',
                        'next_text' => '次へ →',
                    ));
                    ?>
                </div>
                
            <?php else : ?>
                
                <div class="no-results">
                    <h2>コンテンツが見つかりません</h2>
                    <p>お探しのコンテンツは見つかりませんでした。</p>
                    <?php get_search_form(); ?>
                </div>
                
            <?php endif; ?>
        </div>
        
        <?php if (is_active_sidebar('sidebar-1')) : ?>
            <aside class="widget-area">
                <?php dynamic_sidebar('sidebar-1'); ?>
            </aside>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>