<?php
/**
 * イベント詳細ページテンプレート
 */

get_header(); ?>

<main id="main" class="site-main wa-style">
    
    <?php while (have_posts()) : the_post(); 
        $event_date = get_post_meta(get_the_ID(), '_event_date', true);
        $event_time = get_post_meta(get_the_ID(), '_event_time', true);
        $event_location = get_post_meta(get_the_ID(), '_event_location', true);
        $event_fee = get_post_meta(get_the_ID(), '_event_fee', true);
        
        // 日付が過ぎているかチェック
        $is_past = false;
        if ($event_date && strtotime($event_date) < strtotime('today')) {
            $is_past = true;
        }
    ?>
    
    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="wa-pattern-overlay"></div>
        <div class="container">
            <h1 class="page-title"><?php the_title(); ?></h1>
            <?php if ($is_past) : ?>
                <p class="event-status-badge">このイベントは終了しました</p>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- イベント詳細セクション -->
    <section class="event-detail section">
        <div class="container">
            <div class="event-detail-content">
                
                <!-- メインコンテンツ -->
                <div class="event-main">
                    
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="event-featured-image">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- イベント情報 -->
                    <div class="event-info-box">
                        <h2 class="info-title">イベント情報</h2>
                        
                        <table class="event-info-table">
                            <?php if ($event_date) : ?>
                                <tr>
                                    <th>開催日</th>
                                    <td><?php echo date('Y年n月j日（', strtotime($event_date)); ?>
                                        <?php 
                                        $week = array('日', '月', '火', '水', '木', '金', '土');
                                        echo $week[date('w', strtotime($event_date))];
                                        ?>）
                                    </td>
                                </tr>
                            <?php endif; ?>
                            
                            <?php if ($event_time) : ?>
                                <tr>
                                    <th>開催時間</th>
                                    <td><?php echo esc_html($event_time); ?></td>
                                </tr>
                            <?php endif; ?>
                            
                            <?php if ($event_location) : ?>
                                <tr>
                                    <th>開催場所</th>
                                    <td><?php echo esc_html($event_location); ?></td>
                                </tr>
                            <?php endif; ?>
                            
                            <?php if ($event_fee) : ?>
                                <tr>
                                    <th>参加費</th>
                                    <td><?php echo esc_html($event_fee); ?></td>
                                </tr>
                            <?php endif; ?>
                            
                            <?php
                            $categories = get_the_terms(get_the_ID(), 'event_category');
                            if ($categories && !is_wp_error($categories)) : ?>
                                <tr>
                                    <th>カテゴリー</th>
                                    <td>
                                        <?php foreach ($categories as $category) : ?>
                                            <span class="event-category-badge"><?php echo esc_html($category->name); ?></span>
                                        <?php endforeach; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    
                    <!-- イベント詳細説明 -->
                    <div class="event-description">
                        <h2>イベント詳細</h2>
                        <div class="event-content-body">
                            <?php the_content(); ?>
                        </div>
                    </div>
                    
                    <!-- 申し込みボタン -->
                    <?php if (!$is_past) : ?>
                        <div class="event-cta">
                            <h3>このイベントに参加をご希望の方へ</h3>
                            <p>下記のお問い合わせフォームより、イベント参加希望の旨をお知らせください。</p>
                            <a href="<?php echo home_url('/contact/'); ?>?event=<?php echo urlencode(get_the_title()); ?>" class="btn btn-primary">参加申し込み</a>
                        </div>
                    <?php endif; ?>
                    
                    <!-- シェアボタン -->
                    <div class="event-share">
                        <h3>このイベントをシェア</h3>
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank" class="share-btn facebook">Facebook</a>
                            <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" target="_blank" class="share-btn twitter">Twitter</a>
                            <a href="https://line.me/R/msg/text/?<?php the_title(); ?>%0D%0A<?php the_permalink(); ?>" target="_blank" class="share-btn line">LINE</a>
                        </div>
                    </div>
                    
                </div>
                
                <!-- サイドバー -->
                <aside class="event-sidebar">
                    
                    <!-- 関連イベント -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">他のイベント</h3>
                        <?php
                        $related_events = new WP_Query(array(
                            'post_type' => 'event',
                            'posts_per_page' => 5,
                            'post__not_in' => array(get_the_ID()),
                            'meta_key' => '_event_date',
                            'orderby' => 'meta_value',
                            'order' => 'ASC',
                            'meta_query' => array(
                                array(
                                    'key' => '_event_date',
                                    'value' => date('Y-m-d'),
                                    'compare' => '>=',
                                    'type' => 'DATE'
                                )
                            )
                        ));
                        
                        if ($related_events->have_posts()) : ?>
                            <ul class="related-events-list">
                                <?php while ($related_events->have_posts()) : $related_events->the_post(); 
                                    $related_date = get_post_meta(get_the_ID(), '_event_date', true);
                                ?>
                                    <li>
                                        <a href="<?php the_permalink(); ?>">
                                            <span class="event-date-mini"><?php echo date('n/j', strtotime($related_date)); ?></span>
                                            <span class="event-title-mini"><?php the_title(); ?></span>
                                        </a>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        <?php else : ?>
                            <p>他のイベントはありません。</p>
                        <?php endif;
                        wp_reset_postdata();
                        ?>
                    </div>
                    
                    <!-- お問い合わせ -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">お問い合わせ</h3>
                        <p>イベントについてご不明な点がございましたら、お気軽にお問い合わせください。</p>
                        <a href="<?php echo home_url('/contact/'); ?>" class="btn btn-secondary btn-block">お問い合わせ</a>
                    </div>
                    
                </aside>
                
            </div>
        </div>
    </section>
    
    <?php endwhile; ?>
    
</main>

<?php get_footer(); ?>