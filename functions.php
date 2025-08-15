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
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap', array(), null);
    
    // メインCSS
    wp_enqueue_style('arigatou-club-main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0');
    
    // ページ用CSS
    wp_enqueue_style('arigatou-club-pages', get_template_directory_uri() . '/assets/css/pages.css', array(), '1.0.0');
    
    // JavaScript
    wp_enqueue_script('arigatou-club-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
    
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
    
}
add_action('init', 'arigatou_club_custom_taxonomies');

/**
 * カスタムフィールドの追加（ACF不使用版）
 */
function arigatou_club_add_meta_boxes() {
    // イベント用メタボックス
    add_meta_box(
        'event_details',
        'イベント詳細',
        'arigatou_club_event_meta_box',
        'event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'arigatou_club_add_meta_boxes');

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
 * カスタムフィールドの保存
 */
function arigatou_club_save_post_meta($post_id) {
    // 自動保存の場合は何もしない
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
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