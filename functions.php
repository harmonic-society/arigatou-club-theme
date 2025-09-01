<?php
/**
 * ありがとう倶楽部 テーマ関数
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * テーマのセットアップ
 */
function arigatou_club_setup() {
    // タイトルタグのサポート
    add_theme_support('title-tag');
    
    // アイキャッチ画像のサポート
    add_theme_support('post-thumbnails');
    
    // カスタムロゴのサポート
    add_theme_support('custom-logo', array(
        'height' => 100,
        'width' => 300,
        'flex-height' => true,
        'flex-width' => true,
    ));
    
    // HTML5サポート
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    
    // カスタムメニューの登録
    register_nav_menus(array(
        'primary' => 'メインメニュー',
        'footer' => 'フッターメニュー',
    ));
    
    // エディタースタイルのサポート
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');
}
add_action('after_setup_theme', 'arigatou_club_setup');

/**
 * スタイルシートとスクリプトの読み込み
 */
function arigatou_club_scripts() {
    // スタイルシート
    wp_enqueue_style('arigatou-club-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Google Fonts - 手書き風フォント
    wp_enqueue_style('google-fonts-handwriting', 'https://fonts.googleapis.com/css2?family=Klee+One:wght@400;600&family=Zen+Maru+Gothic:wght@400;500;700&family=Kosugi+Maru&display=swap', array(), null);
    
    // メインCSS
    wp_enqueue_style('arigatou-club-main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0');
    
    // ページ用CSS
    wp_enqueue_style('arigatou-club-pages', get_template_directory_uri() . '/assets/css/pages.css', array(), '1.0.0');
    
    // About ページ用CSS
    if (is_page_template('page-about.php')) {
        wp_enqueue_style('arigatou-club-about', get_template_directory_uri() . '/assets/css/page-about.css', array(), '1.0.0');
    }
    
    // ナビゲーション用CSS
    wp_enqueue_style('arigatou-club-navigation', get_template_directory_uri() . '/assets/css/navigation.css', array(), '1.0.0');
    
    // ブログ用CSS
    if (is_home() || is_single() || is_archive() || is_search()) {
        wp_enqueue_style('arigatou-club-blog', get_template_directory_uri() . '/assets/css/blog.css', array(), '1.0.0');
    }
    
    // 協賛企業用CSS
    if (is_post_type_archive('sponsor') || is_singular('sponsor')) {
        wp_enqueue_style('arigatou-club-sponsors', get_template_directory_uri() . '/assets/css/sponsors.css', array(), '1.0.0');
    }
    
    // フッター用CSS
    wp_enqueue_style('arigatou-club-footer', get_template_directory_uri() . '/assets/css/footer.css', array(), '1.0.0');
    
    // Contact Form 7用CSS（Contact Form 7が有効な場合）
    if (class_exists('WPCF7') && (is_page_template('page-contact-cf7.php') || is_page('contact'))) {
        wp_enqueue_style('arigatou-club-cf7', get_template_directory_uri() . '/assets/css/contact-form-7.css', array(), '1.0.0');
    }
    
    // Contact ページ用CSS
    if (is_page_template('page-contact.php') || is_page_template('page-contact-cf7.php') || is_page('contact')) {
        wp_enqueue_style('arigatou-club-contact', get_template_directory_uri() . '/assets/css/contact-page.css', array(), '1.0.0');
    }
    
    // Thanks ページ用CSS
    if (is_page_template('page-thanks.php') || is_page('thanks')) {
        wp_enqueue_style('arigatou-club-thanks', get_template_directory_uri() . '/assets/css/thanks-page.css', array(), '1.0.0');
    }
    
    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
    
    // Swiper CSS (フロントページのみ)
    if (is_front_page()) {
        wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0');
    }
    
    // JavaScript
    wp_enqueue_script('arigatou-club-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
    
    // Swiper JS (フロントページのみ)
    if (is_front_page()) {
        wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);
        wp_enqueue_script('arigatou-club-slider', get_template_directory_uri() . '/assets/js/slider.js', array('swiper'), '1.0.0', true);
    }
    
    // AJAXのURL設定
    wp_localize_script('arigatou-club-main', 'arigatou_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('arigatou_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'arigatou_club_scripts');

/**
 * ウィジェットエリアの登録
 */
function arigatou_club_widgets_init() {
    register_sidebar(array(
        'name' => 'サイドバー',
        'id' => 'sidebar-1',
        'description' => 'サイドバーウィジェットエリア',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    
    register_sidebar(array(
        'name' => 'フッターウィジェット1',
        'id' => 'footer-1',
        'description' => 'フッターウィジェットエリア1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => 'フッターウィジェット2',
        'id' => 'footer-2',
        'description' => 'フッターウィジェットエリア2',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => 'フッターウィジェット3',
        'id' => 'footer-3',
        'description' => 'フッターウィジェットエリア3',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'arigatou_club_widgets_init');

/**
 * カスタム投稿タイプの登録
 */
function arigatou_club_custom_post_types() {
    // ヒーロースライダー投稿タイプ
    register_post_type('hero_slider', array(
        'labels' => array(
            'name' => 'ヒーロースライダー',
            'singular_name' => 'スライダー画像',
            'add_new' => '新規追加',
            'add_new_item' => '新しいスライダー画像を追加',
            'edit_item' => 'スライダー画像を編集',
            'new_item' => '新しいスライダー画像',
            'view_item' => 'スライダー画像を表示',
            'search_items' => 'スライダー画像を検索',
            'not_found' => 'スライダー画像が見つかりません',
            'not_found_in_trash' => 'ゴミ箱にスライダー画像はありません',
            'all_items' => 'すべてのスライダー画像',
            'menu_name' => 'ヒーロースライダー',
        ),
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-images-alt2',
        'supports' => array('title', 'thumbnail', 'custom-fields'),
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'show_in_rest' => true,
    ));
    
    // イベント投稿タイプ
    register_post_type('event', array(
        'labels' => array(
            'name' => 'イベント',
            'singular_name' => 'イベント',
            'add_new' => '新規追加',
            'add_new_item' => '新しいイベントを追加',
            'edit_item' => 'イベントを編集',
            'new_item' => '新しいイベント',
            'view_item' => 'イベントを表示',
            'search_items' => 'イベントを検索',
            'not_found' => 'イベントが見つかりません',
            'not_found_in_trash' => 'ゴミ箱にイベントはありません',
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'rewrite' => array('slug' => 'events'),
    ));
    
    // 協賛企業投稿タイプ
    register_post_type('sponsor', array(
        'labels' => array(
            'name' => '協賛企業',
            'singular_name' => '協賛企業',
            'add_new' => '新規追加',
            'add_new_item' => '新しい協賛企業を追加',
            'edit_item' => '協賛企業を編集',
            'new_item' => '新しい協賛企業',
            'view_item' => '協賛企業を表示',
            'search_items' => '協賛企業を検索',
            'not_found' => '協賛企業が見つかりません',
            'not_found_in_trash' => 'ゴミ箱に協賛企業はありません',
            'all_items' => 'すべての協賛企業',
            'menu_name' => '協賛企業',
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-building',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'rewrite' => array('slug' => 'sponsors'),
        'show_in_rest' => true,
    ));
    
}
add_action('init', 'arigatou_club_custom_post_types');

/**
 * カスタムタクソノミーの登録
 */
function arigatou_club_custom_taxonomies() {
    // イベントカテゴリー
    register_taxonomy('event_category', 'event', array(
        'labels' => array(
            'name' => 'イベントカテゴリー',
            'singular_name' => 'イベントカテゴリー',
            'search_items' => 'カテゴリーを検索',
            'all_items' => 'すべてのカテゴリー',
            'edit_item' => 'カテゴリーを編集',
            'update_item' => 'カテゴリーを更新',
            'add_new_item' => '新しいカテゴリーを追加',
            'new_item_name' => '新しいカテゴリー名',
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'rewrite' => array('slug' => 'event-category'),
    ));
    
    // 協賛企業カテゴリー
    register_taxonomy('sponsor_category', 'sponsor', array(
        'labels' => array(
            'name' => '協賛カテゴリー',
            'singular_name' => '協賛カテゴリー',
            'search_items' => 'カテゴリーを検索',
            'all_items' => 'すべてのカテゴリー',
            'edit_item' => 'カテゴリーを編集',
            'update_item' => 'カテゴリーを更新',
            'add_new_item' => '新しいカテゴリーを追加',
            'new_item_name' => '新しいカテゴリー名',
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'rewrite' => array('slug' => 'sponsor-category'),
        'show_in_rest' => true,
    ));
    
}
add_action('init', 'arigatou_club_custom_taxonomies');

/**
 * カスタムフィールドの追加（ACF不使用版）
 */
function arigatou_club_add_meta_boxes() {
    // ヒーロースライダー用メタボックス
    add_meta_box(
        'slider_details',
        'スライダー詳細設定',
        'arigatou_club_slider_meta_box',
        'hero_slider',
        'normal',
        'high'
    );
    
    // イベント用メタボックス
    add_meta_box(
        'event_details',
        'イベント詳細',
        'arigatou_club_event_meta_box',
        'event',
        'normal',
        'high'
    );
    
    // 協賛企業用メタボックス
    add_meta_box(
        'sponsor_details',
        '企業詳細',
        'arigatou_club_sponsor_meta_box',
        'sponsor',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'arigatou_club_add_meta_boxes');

/**
 * ヒーロースライダーメタボックスの表示
 */
function arigatou_club_slider_meta_box($post) {
    wp_nonce_field('arigatou_club_save_slider_meta', 'arigatou_club_slider_nonce');
    
    $slide_title = get_post_meta($post->ID, '_slide_title', true);
    $slide_subtitle = get_post_meta($post->ID, '_slide_subtitle', true);
    $button_text = get_post_meta($post->ID, '_slide_button_text', true);
    $button_url = get_post_meta($post->ID, '_slide_button_url', true);
    $slide_order = get_post_meta($post->ID, '_slide_order', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="slide_title">スライドタイトル</label></th>
            <td><input type="text" id="slide_title" name="slide_title" value="<?php echo esc_attr($slide_title); ?>" class="regular-text" placeholder="ありがとう倶楽部" /></td>
        </tr>
        <tr>
            <th><label for="slide_subtitle">スライドサブタイトル</label></th>
            <td><textarea id="slide_subtitle" name="slide_subtitle" class="large-text" rows="2" placeholder="感謝の心で繋がる、温かい和の世界へ"><?php echo esc_textarea($slide_subtitle); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="slide_button_text">ボタンテキスト</label></th>
            <td><input type="text" id="slide_button_text" name="slide_button_text" value="<?php echo esc_attr($button_text); ?>" class="regular-text" placeholder="詳しく見る" /></td>
        </tr>
        <tr>
            <th><label for="slide_button_url">ボタンリンク先URL</label></th>
            <td><input type="url" id="slide_button_url" name="slide_button_url" value="<?php echo esc_attr($button_url); ?>" class="regular-text" placeholder="https://" /></td>
        </tr>
        <tr>
            <th><label for="slide_order">表示順序</label></th>
            <td><input type="number" id="slide_order" name="slide_order" value="<?php echo esc_attr($slide_order ? $slide_order : '0'); ?>" class="small-text" min="0" /> <span class="description">小さい数字から順に表示されます</span></td>
        </tr>
    </table>
    <p class="description">※ アイキャッチ画像を設定してください。推奨サイズ: 1920×800px</p>
    <?php
}

/**
 * イベントメタボックスの表示
 */
function arigatou_club_event_meta_box($post) {
    wp_nonce_field('arigatou_club_save_event_meta', 'arigatou_club_event_nonce');
    
    $event_date = get_post_meta($post->ID, '_event_date', true);
    $event_time = get_post_meta($post->ID, '_event_time', true);
    $event_location = get_post_meta($post->ID, '_event_location', true);
    $event_fee = get_post_meta($post->ID, '_event_fee', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="event_date">開催日</label></th>
            <td><input type="date" id="event_date" name="event_date" value="<?php echo esc_attr($event_date); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_time">開催時間</label></th>
            <td><input type="text" id="event_time" name="event_time" value="<?php echo esc_attr($event_time); ?>" class="regular-text" placeholder="例: 10:00-12:00" /></td>
        </tr>
        <tr>
            <th><label for="event_location">開催場所</label></th>
            <td><input type="text" id="event_location" name="event_location" value="<?php echo esc_attr($event_location); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_fee">参加費</label></th>
            <td><input type="text" id="event_fee" name="event_fee" value="<?php echo esc_attr($event_fee); ?>" class="regular-text" placeholder="例: 無料、1,000円" /></td>
        </tr>
    </table>
    <?php
}

/**
 * 協賛企業メタボックスの表示
 */
function arigatou_club_sponsor_meta_box($post) {
    wp_nonce_field('arigatou_club_save_sponsor_meta', 'arigatou_club_sponsor_nonce');
    
    $company_url = get_post_meta($post->ID, '_sponsor_url', true);
    $company_industry = get_post_meta($post->ID, '_sponsor_industry', true);
    $sponsor_level = get_post_meta($post->ID, '_sponsor_level', true);
    $sponsor_since = get_post_meta($post->ID, '_sponsor_since', true);
    $contact_person = get_post_meta($post->ID, '_sponsor_contact_person', true);
    $contact_email = get_post_meta($post->ID, '_sponsor_contact_email', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="sponsor_url">企業ウェブサイト</label></th>
            <td><input type="url" id="sponsor_url" name="sponsor_url" value="<?php echo esc_attr($company_url); ?>" class="regular-text" placeholder="https://example.com" /></td>
        </tr>
        <tr>
            <th><label for="sponsor_industry">業種</label></th>
            <td><input type="text" id="sponsor_industry" name="sponsor_industry" value="<?php echo esc_attr($company_industry); ?>" class="regular-text" placeholder="例: IT、製造業、サービス業" /></td>
        </tr>
        <tr>
            <th><label for="sponsor_level">協賛レベル</label></th>
            <td>
                <select id="sponsor_level" name="sponsor_level">
                    <option value="">選択してください</option>
                    <option value="platinum" <?php selected($sponsor_level, 'platinum'); ?>>プラチナスポンサー</option>
                    <option value="gold" <?php selected($sponsor_level, 'gold'); ?>>ゴールドスポンサー</option>
                    <option value="silver" <?php selected($sponsor_level, 'silver'); ?>>シルバースポンサー</option>
                    <option value="bronze" <?php selected($sponsor_level, 'bronze'); ?>>ブロンズスポンサー</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="sponsor_since">協賛開始年月</label></th>
            <td><input type="month" id="sponsor_since" name="sponsor_since" value="<?php echo esc_attr($sponsor_since); ?>" /></td>
        </tr>
        <tr>
            <th><label for="sponsor_contact_person">担当者名</label></th>
            <td><input type="text" id="sponsor_contact_person" name="sponsor_contact_person" value="<?php echo esc_attr($contact_person); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="sponsor_contact_email">担当者メール</label></th>
            <td><input type="email" id="sponsor_contact_email" name="sponsor_contact_email" value="<?php echo esc_attr($contact_email); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

/**
 * カスタムフィールドの保存
 */
function arigatou_club_save_post_meta($post_id) {
    // 自動保存の場合は何もしない
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // スライダーメタの保存
    if (isset($_POST['arigatou_club_slider_nonce']) && wp_verify_nonce($_POST['arigatou_club_slider_nonce'], 'arigatou_club_save_slider_meta')) {
        if (isset($_POST['slide_title'])) {
            update_post_meta($post_id, '_slide_title', sanitize_text_field($_POST['slide_title']));
        }
        if (isset($_POST['slide_subtitle'])) {
            update_post_meta($post_id, '_slide_subtitle', sanitize_textarea_field($_POST['slide_subtitle']));
        }
        if (isset($_POST['slide_button_text'])) {
            update_post_meta($post_id, '_slide_button_text', sanitize_text_field($_POST['slide_button_text']));
        }
        if (isset($_POST['slide_button_url'])) {
            update_post_meta($post_id, '_slide_button_url', esc_url_raw($_POST['slide_button_url']));
        }
        if (isset($_POST['slide_order'])) {
            update_post_meta($post_id, '_slide_order', intval($_POST['slide_order']));
        }
    }
    
    // イベントメタの保存
    if (isset($_POST['arigatou_club_event_nonce']) && wp_verify_nonce($_POST['arigatou_club_event_nonce'], 'arigatou_club_save_event_meta')) {
        if (isset($_POST['event_date'])) {
            update_post_meta($post_id, '_event_date', sanitize_text_field($_POST['event_date']));
        }
        if (isset($_POST['event_time'])) {
            update_post_meta($post_id, '_event_time', sanitize_text_field($_POST['event_time']));
        }
        if (isset($_POST['event_location'])) {
            update_post_meta($post_id, '_event_location', sanitize_text_field($_POST['event_location']));
        }
        if (isset($_POST['event_fee'])) {
            update_post_meta($post_id, '_event_fee', sanitize_text_field($_POST['event_fee']));
        }
    }
    
    // 協賛企業メタの保存
    if (isset($_POST['arigatou_club_sponsor_nonce']) && wp_verify_nonce($_POST['arigatou_club_sponsor_nonce'], 'arigatou_club_save_sponsor_meta')) {
        if (isset($_POST['sponsor_url'])) {
            update_post_meta($post_id, '_sponsor_url', esc_url_raw($_POST['sponsor_url']));
        }
        if (isset($_POST['sponsor_industry'])) {
            update_post_meta($post_id, '_sponsor_industry', sanitize_text_field($_POST['sponsor_industry']));
        }
        if (isset($_POST['sponsor_level'])) {
            update_post_meta($post_id, '_sponsor_level', sanitize_text_field($_POST['sponsor_level']));
        }
        if (isset($_POST['sponsor_since'])) {
            update_post_meta($post_id, '_sponsor_since', sanitize_text_field($_POST['sponsor_since']));
        }
        if (isset($_POST['sponsor_contact_person'])) {
            update_post_meta($post_id, '_sponsor_contact_person', sanitize_text_field($_POST['sponsor_contact_person']));
        }
        if (isset($_POST['sponsor_contact_email'])) {
            update_post_meta($post_id, '_sponsor_contact_email', sanitize_email($_POST['sponsor_contact_email']));
        }
    }
}
add_action('save_post', 'arigatou_club_save_post_meta');

/**
 * カスタマイザーの設定
 */
function arigatou_club_customize_register($wp_customize) {
    // ホームページ設定セクション
    $wp_customize->add_section('arigatou_club_homepage', array(
        'title' => 'ホームページ設定',
        'priority' => 30,
    ));
    
    // ヒーローテキスト
    $wp_customize->add_setting('hero_title', array(
        'default' => 'ありがとうで世界を満たそう',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('hero_title', array(
        'label' => 'ヒーロータイトル',
        'section' => 'arigatou_club_homepage',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('hero_subtitle', array(
        'default' => '感謝の心を広げるコミュニティ',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('hero_subtitle', array(
        'label' => 'ヒーローサブタイトル',
        'section' => 'arigatou_club_homepage',
        'type' => 'text',
    ));
    
    // 代表挨拶設定セクション
    $wp_customize->add_section('arigatou_club_representative', array(
        'title' => '代表挨拶設定',
        'priority' => 35,
    ));
    
    // 代表者名
    $wp_customize->add_setting('representative_name', array(
        'default' => '秋山',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('representative_name', array(
        'label' => '代表者名',
        'section' => 'arigatou_club_representative',
        'type' => 'text',
    ));
    
    // 代表者肩書き
    $wp_customize->add_setting('representative_title', array(
        'default' => 'ありがとう倶楽部 代表',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('representative_title', array(
        'label' => '代表者肩書き',
        'section' => 'arigatou_club_representative',
        'type' => 'text',
    ));
    
    // 代表者プロフィール写真
    $wp_customize->add_setting('representative_photo', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'representative_photo', array(
        'label' => '代表者プロフィール写真',
        'description' => '推奨サイズ: 600×800px（縦長）',
        'section' => 'arigatou_club_representative',
        'settings' => 'representative_photo',
    )));
    
    // お好み焼き社会セクション
    $wp_customize->add_section('arigatou_club_okonomiyaki', array(
        'title' => 'お好み焼き社会',
        'priority' => 35,
        'description' => 'お好み焼き社会セクションの設定',
    ));
    
    // お好み焼き社会イメージ画像
    $wp_customize->add_setting('okonomiyaki_society_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'okonomiyaki_society_image', array(
        'label' => 'お好み焼き社会イメージ画像',
        'description' => '推奨サイズ: 400×400px（正方形）。未設定の場合は絵文字が表示されます。',
        'section' => 'arigatou_club_okonomiyaki',
        'settings' => 'okonomiyaki_society_image',
    )));
}
add_action('customize_register', 'arigatou_club_customize_register');

/**
 * 抜粋の長さを変更
 */
function arigatou_club_excerpt_length($length) {
    return 100;
}
add_filter('excerpt_length', 'arigatou_club_excerpt_length');

/**
 * 抜粋の末尾を変更
 */
function arigatou_club_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'arigatou_club_excerpt_more');

/**
 * お問い合わせフォーム処理
 */
function arigatou_club_handle_contact_form() {
    // nonceチェック
    if (!isset($_POST['arigatou_contact_nonce']) || !wp_verify_nonce($_POST['arigatou_contact_nonce'], 'arigatou_contact_form')) {
        wp_redirect(home_url('/contact/?status=error'));
        exit;
    }
    
    // データの取得とサニタイズ
    $contact_type = sanitize_text_field($_POST['contact_type']);
    $name = sanitize_text_field($_POST['contact_name']);
    $email = sanitize_email($_POST['contact_email']);
    $phone = sanitize_text_field($_POST['contact_phone']);
    $subject = sanitize_text_field($_POST['contact_subject']);
    $message = sanitize_textarea_field($_POST['contact_message']);
    
    // メール送信
    $to = get_option('admin_email');
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $email
    );
    
    $email_subject = '[ありがとう倶楽部] ' . $contact_type . '：' . $subject;
    
    $email_body = "
    <html>
    <body>
        <h2>お問い合わせを受け付けました</h2>
        <table style='border-collapse: collapse; width: 100%;'>
            <tr>
                <th style='border: 1px solid #ddd; padding: 10px; text-align: left; background: #f5f5f5;'>お問い合わせ種別</th>
                <td style='border: 1px solid #ddd; padding: 10px;'>{$contact_type}</td>
            </tr>
            <tr>
                <th style='border: 1px solid #ddd; padding: 10px; text-align: left; background: #f5f5f5;'>お名前</th>
                <td style='border: 1px solid #ddd; padding: 10px;'>{$name}</td>
            </tr>
            <tr>
                <th style='border: 1px solid #ddd; padding: 10px; text-align: left; background: #f5f5f5;'>メールアドレス</th>
                <td style='border: 1px solid #ddd; padding: 10px;'>{$email}</td>
            </tr>
            <tr>
                <th style='border: 1px solid #ddd; padding: 10px; text-align: left; background: #f5f5f5;'>電話番号</th>
                <td style='border: 1px solid #ddd; padding: 10px;'>{$phone}</td>
            </tr>
            <tr>
                <th style='border: 1px solid #ddd; padding: 10px; text-align: left; background: #f5f5f5;'>件名</th>
                <td style='border: 1px solid #ddd; padding: 10px;'>{$subject}</td>
            </tr>
            <tr>
                <th style='border: 1px solid #ddd; padding: 10px; text-align: left; background: #f5f5f5;'>お問い合わせ内容</th>
                <td style='border: 1px solid #ddd; padding: 10px;'>" . nl2br($message) . "</td>
            </tr>
        </table>
    </body>
    </html>
    ";
    
    $sent = wp_mail($to, $email_subject, $email_body, $headers);
    
    // 自動返信メール
    if ($sent) {
        $reply_subject = '[ありがとう倶楽部] お問い合わせありがとうございます';
        $reply_body = "
        <html>
        <body>
            <p>{$name} 様</p>
            <p>この度は、ありがとう倶楽部へお問い合わせいただき、誠にありがとうございます。</p>
            <p>以下の内容でお問い合わせを承りました。</p>
            <hr>
            <p><strong>お問い合わせ種別：</strong>{$contact_type}</p>
            <p><strong>件名：</strong>{$subject}</p>
            <p><strong>お問い合わせ内容：</strong><br>" . nl2br($message) . "</p>
            <hr>
            <p>内容を確認の上、担当者よりご連絡させていただきます。</p>
            <p>今しばらくお待ちください。</p>
            <br>
            <p>ありがとう倶楽部</p>
        </body>
        </html>
        ";
        
        wp_mail($email, $reply_subject, $reply_body, array('Content-Type: text/html; charset=UTF-8'));
    }
    
    // リダイレクト
    if ($sent) {
        wp_redirect(home_url('/contact/?status=success'));
    } else {
        wp_redirect(home_url('/contact/?status=error'));
    }
    exit;
}
add_action('admin_post_arigatou_contact_form', 'arigatou_club_handle_contact_form');
add_action('admin_post_nopriv_arigatou_contact_form', 'arigatou_club_handle_contact_form');

/**
 * Contact Form 7 送信後のサンクスページへのリダイレクト
 */
add_action('wp_footer', 'arigatou_cf7_redirect_script');
function arigatou_cf7_redirect_script() {
    // Contact Form 7を使用しているページでのみ実行
    if (!is_page('contact') && !is_page_template('page-contact-cf7.php')) {
        return;
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Contact Form 7の送信完了イベントをリスン
        document.addEventListener('wpcf7mailsent', function(event) {
            // サンクスページへリダイレクト
            window.location.href = '<?php echo home_url('/thanks/'); ?>';
        }, false);
    });
    </script>
    <?php
}