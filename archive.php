<?php
/**
 * アーカイブページテンプレート
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <header class="archive-header">
            <?php
            the_archive_title('<h1 class="archive-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            ?>
        </header>
        
        <?php if (have_posts()) : ?>
            <div class="archive-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('archive-item'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="archive-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="archive-content">
                            <header class="entry-header">
                                <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>
                                
                                <div class="entry-meta">
                                    <span class="posted-date">
                                        <?php echo get_the_date(); ?>
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
</main>

<?php get_footer(); ?>