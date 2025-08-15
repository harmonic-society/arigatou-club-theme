<?php
/**
 * イベント一覧ページテンプレート
 */

get_header(); ?>

<main id="main" class="site-main wa-style">
    
    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="wa-pattern-overlay"></div>
        <div class="container">
            <h1 class="page-title">イベント一覧</h1>
            <p class="page-subtitle">ありがとうカフェ、セミナー、ワークショップなど</p>
        </div>
    </section>
    
    <!-- イベント一覧セクション -->
    <section class="events-archive section">
        <div class="container">
            
            <?php if (have_posts()) : ?>
                
                <!-- イベントカテゴリーフィルター -->
                <?php
                $terms = get_terms(array(
                    'taxonomy' => 'event_category',
                    'hide_empty' => true,
                ));
                
                if (!empty($terms) && !is_wp_error($terms)) : ?>
                    <div class="event-filters">
                        <a href="<?php echo get_post_type_archive_link('event'); ?>" class="filter-btn active">すべて</a>
                        <?php foreach ($terms as $term) : ?>
                            <a href="<?php echo get_term_link($term); ?>" class="filter-btn"><?php echo esc_html($term->name); ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <!-- イベントグリッド -->
                <div class="events-grid">
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
                        <article class="event-card <?php echo $is_past ? 'past-event' : ''; ?>">
                            <?php if ($is_past) : ?>
                                <div class="event-status">終了</div>
                            <?php endif; ?>
                            
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="event-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="event-content">
                                <h2 class="event-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                
                                <div class="event-meta">
                                    <?php if ($event_date) : ?>
                                        <div class="meta-item">
                                            <span class="meta-icon">📅</span>
                                            <span><?php echo date('Y年n月j日', strtotime($event_date)); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($event_time) : ?>
                                        <div class="meta-item">
                                            <span class="meta-icon">🕐</span>
                                            <span><?php echo esc_html($event_time); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($event_location) : ?>
                                        <div class="meta-item">
                                            <span class="meta-icon">📍</span>
                                            <span><?php echo esc_html($event_location); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($event_fee) : ?>
                                        <div class="meta-item">
                                            <span class="meta-icon">💰</span>
                                            <span><?php echo esc_html($event_fee); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="event-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="btn btn-small">詳細を見る</a>
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
                
                <div class="no-events">
                    <p>現在、イベントの情報はありません。</p>
                    <p>新しいイベントが登録されましたら、こちらに表示されます。</p>
                </div>
                
            <?php endif; ?>
            
        </div>
    </section>
    
</main>

<?php get_footer(); ?>