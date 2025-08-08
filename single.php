<?php
/**
 * 個別投稿ページテンプレート
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <div class="content-area">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
                    <header class="entry-header">
                        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                        
                        <div class="entry-meta">
                            <span class="posted-date">
                                <i class="far fa-calendar"></i>
                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo get_the_date(); ?>
                                </time>
                            </span>
                            <span class="posted-author">
                                <i class="far fa-user"></i>
                                <?php the_author(); ?>
                            </span>
                            <?php if (has_category()) : ?>
                                <span class="posted-category">
                                    <i class="far fa-folder"></i>
                                    <?php the_category(', '); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </header>
                    
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="entry-content">
                        <?php the_content(); ?>
                        <?php
                        wp_link_pages(array(
                            'before' => '<div class="page-links">ページ: ',
                            'after' => '</div>',
                        ));
                        ?>
                    </div>
                    
                    <footer class="entry-footer">
                        <?php if (has_tag()) : ?>
                            <div class="post-tags">
                                <i class="fas fa-tags"></i>
                                <?php the_tags('', ', ', ''); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-navigation">
                            <?php
                            the_post_navigation(array(
                                'prev_text' => '<span class="nav-subtitle">前の記事</span> <span class="nav-title">%title</span>',
                                'next_text' => '<span class="nav-subtitle">次の記事</span> <span class="nav-title">%title</span>',
                            ));
                            ?>
                        </div>
                    </footer>
                </article>
                
                <?php
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
                
            <?php endwhile; ?>
        </div>
        
        <?php if (is_active_sidebar('sidebar-1')) : ?>
            <aside class="widget-area">
                <?php dynamic_sidebar('sidebar-1'); ?>
            </aside>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>