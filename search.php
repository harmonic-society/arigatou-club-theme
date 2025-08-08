<?php
/**
 * 検索結果ページテンプレート
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title">
                <?php
                printf('「%s」の検索結果', '<span>' . get_search_query() . '</span>');
                ?>
            </h1>
            <?php if (have_posts()) : ?>
                <p class="search-result-count">
                    <?php
                    global $wp_query;
                    printf('%d件の結果が見つかりました', $wp_query->found_posts);
                    ?>
                </p>
            <?php endif; ?>
        </header>
        
        <?php if (have_posts()) : ?>
            <div class="search-results">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('search-result-item'); ?>>
                        <header class="entry-header">
                            <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
                            
                            <div class="entry-meta">
                                <span class="post-type">
                                    <?php
                                    $post_type = get_post_type();
                                    $post_type_obj = get_post_type_object($post_type);
                                    echo esc_html($post_type_obj->labels->singular_name);
                                    ?>
                                </span>
                                <span class="posted-date">
                                    <?php echo get_the_date(); ?>
                                </span>
                            </div>
                        </header>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="entry-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('thumbnail'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="entry-summary">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <footer class="entry-footer">
                            <a href="<?php the_permalink(); ?>" class="read-more">詳細を見る →</a>
                        </footer>
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
                <h2>検索結果が見つかりません</h2>
                <p>「<?php echo get_search_query(); ?>」に一致する結果は見つかりませんでした。</p>
                
                <div class="search-suggestions">
                    <h3>検索のヒント</h3>
                    <ul>
                        <li>キーワードを変更してみてください</li>
                        <li>より一般的な言葉を使ってみてください</li>
                        <li>スペルを確認してください</li>
                    </ul>
                </div>
                
                <div class="new-search">
                    <h3>もう一度検索</h3>
                    <?php get_search_form(); ?>
                </div>
            </div>
            
        <?php endif; ?>
    </div>
</main>

<style>
.search-results {
    margin-top: 40px;
}

.search-result-item {
    display: flex;
    gap: 20px;
    padding: 30px;
    background: #fff;
    margin-bottom: 20px;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.search-result-item .entry-thumbnail {
    flex-shrink: 0;
}

.search-result-item .entry-thumbnail img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
}

.search-result-item .entry-header {
    margin-bottom: 15px;
}

.search-result-item .entry-title {
    font-size: 1.4rem;
    margin-bottom: 10px;
}

.search-result-item .entry-meta {
    display: flex;
    gap: 15px;
    font-size: 0.9rem;
    color: #999;
}

.search-result-item .post-type {
    background: #667eea;
    color: #fff;
    padding: 2px 10px;
    border-radius: 15px;
    font-size: 0.85rem;
}

.search-result-count {
    margin-top: 10px;
    color: #666;
}

.search-suggestions {
    margin: 40px 0;
}

.search-suggestions ul {
    list-style-position: inside;
    color: #666;
}

.new-search {
    margin-top: 40px;
}

@media (max-width: 768px) {
    .search-result-item {
        flex-direction: column;
    }
    
    .search-result-item .entry-thumbnail img {
        width: 100%;
        height: 200px;
    }
}
</style>

<?php get_footer(); ?>