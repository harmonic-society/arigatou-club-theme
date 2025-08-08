<?php
/**
 * 404エラーページテンプレート
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <div class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title">404</h1>
                <p class="error-message">お探しのページが見つかりません</p>
            </header>
            
            <div class="page-content">
                <p>申し訳ございません。お探しのページは存在しないか、移動した可能性があります。</p>
                
                <div class="error-actions">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">ホームへ戻る</a>
                </div>
                
                <div class="search-section">
                    <h2>検索してみる</h2>
                    <?php get_search_form(); ?>
                </div>
                
                <div class="helpful-links">
                    <h2>お役立ちリンク</h2>
                    <ul>
                        <li><a href="<?php echo get_post_type_archive_link('event'); ?>">イベント一覧</a></li>
                        <li><a href="<?php echo get_post_type_archive_link('story'); ?>">ストーリー</a></li>
                        <li><a href="<?php echo get_post_type_archive_link('goods'); ?>">グッズ</a></li>
                        <li><a href="<?php echo home_url('/about/'); ?>">私たちについて</a></li>
                        <li><a href="<?php echo home_url('/contact/'); ?>">お問い合わせ</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.error-404 {
    text-align: center;
    padding: 80px 0;
}

.error-404 .page-title {
    font-size: 8rem;
    color: #667eea;
    margin-bottom: 20px;
    font-weight: bold;
}

.error-message {
    font-size: 1.5rem;
    color: #666;
    margin-bottom: 40px;
}

.error-actions {
    margin: 40px 0;
}

.search-section {
    max-width: 500px;
    margin: 60px auto;
}

.helpful-links {
    margin-top: 60px;
}

.helpful-links ul {
    list-style: none;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.helpful-links a {
    color: #667eea;
    text-decoration: none;
    padding: 10px 20px;
    border: 2px solid #667eea;
    border-radius: 25px;
    transition: all 0.3s;
}

.helpful-links a:hover {
    background: #667eea;
    color: #fff;
}
</style>

<?php get_footer(); ?>