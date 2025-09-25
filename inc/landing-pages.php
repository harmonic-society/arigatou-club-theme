<?php
/**
 * ランディングページ機能
 *
 * カスタム投稿タイプとメタボックスを追加
 */

// ランディングページカスタム投稿タイプの登録
function arigatou_club_register_landing_page_post_type() {
    $labels = array(
        'name'                  => 'ランディングページ',
        'singular_name'         => 'ランディングページ',
        'menu_name'             => 'ランディングページ',
        'name_admin_bar'        => 'ランディングページ',
        'archives'              => 'ランディングページ一覧',
        'attributes'            => 'ランディングページ属性',
        'parent_item_colon'     => '親ランディングページ:',
        'all_items'             => 'すべてのランディングページ',
        'add_new_item'          => '新規ランディングページを追加',
        'add_new'               => '新規追加',
        'new_item'              => '新規ランディングページ',
        'edit_item'             => 'ランディングページを編集',
        'update_item'           => 'ランディングページを更新',
        'view_item'             => 'ランディングページを表示',
        'view_items'            => 'ランディングページを表示',
        'search_items'          => 'ランディングページを検索',
        'not_found'             => 'ランディングページが見つかりません',
        'not_found_in_trash'    => 'ゴミ箱にランディングページが見つかりません',
        'featured_image'        => 'アイキャッチ画像',
        'set_featured_image'    => 'アイキャッチ画像を設定',
        'remove_featured_image' => 'アイキャッチ画像を削除',
        'use_featured_image'    => 'アイキャッチ画像として使用',
        'insert_into_item'      => 'ランディングページに挿入',
        'uploaded_to_this_item' => 'このランディングページにアップロード',
        'items_list'            => 'ランディングページ一覧',
        'items_list_navigation' => 'ランディングページ一覧ナビゲーション',
        'filter_items_list'     => 'ランディングページ一覧を絞り込み',
    );

    $args = array(
        'label'                 => 'ランディングページ',
        'description'           => '独立したランディングページを作成',
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields'),
        'taxonomies'            => array(),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-welcome-widgets-menus',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true, // ブロックエディタを有効化
        'rewrite'               => array(
            'slug' => 'landing',
            'with_front' => false
        ),
    );

    register_post_type('landing_page', $args);
}
add_action('init', 'arigatou_club_register_landing_page_post_type');

// メタボックスの追加
function arigatou_club_add_landing_page_meta_boxes() {
    add_meta_box(
        'landing_page_settings',
        'ランディングページ設定',
        'arigatou_club_landing_page_settings_callback',
        'landing_page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'arigatou_club_add_landing_page_meta_boxes');

// メタボックスのコールバック関数
function arigatou_club_landing_page_settings_callback($post) {
    wp_nonce_field('arigatou_club_landing_page_settings', 'arigatou_club_landing_page_nonce');

    // 既存の値を取得
    $hide_header = get_post_meta($post->ID, '_landing_page_hide_header', true);
    $hide_footer = get_post_meta($post->ID, '_landing_page_hide_footer', true);
    $custom_css = get_post_meta($post->ID, '_landing_page_custom_css', true);
    $hero_title = get_post_meta($post->ID, '_landing_page_hero_title', true);
    $hero_subtitle = get_post_meta($post->ID, '_landing_page_hero_subtitle', true);
    $hero_button_text = get_post_meta($post->ID, '_landing_page_hero_button_text', true);
    $hero_button_url = get_post_meta($post->ID, '_landing_page_hero_button_url', true);
    $layout_type = get_post_meta($post->ID, '_landing_page_layout_type', true) ?: 'default';
    ?>

    <div style="margin-bottom: 20px;">
        <h3>レイアウト設定</h3>
        <table class="form-table">
            <tr>
                <th scope="row">レイアウトタイプ</th>
                <td>
                    <select name="landing_page_layout_type" id="landing_page_layout_type">
                        <option value="default" <?php selected($layout_type, 'default'); ?>>デフォルト</option>
                        <option value="hero" <?php selected($layout_type, 'hero'); ?>>ヒーローセクション付き</option>
                        <option value="fullwidth" <?php selected($layout_type, 'fullwidth'); ?>>フルワイド</option>
                        <option value="sidebar" <?php selected($layout_type, 'sidebar'); ?>>サイドバー付き</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">ヘッダーを非表示</th>
                <td>
                    <label>
                        <input type="checkbox" name="landing_page_hide_header" value="1" <?php checked($hide_header, '1'); ?>>
                        ヘッダーを非表示にする
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row">フッターを非表示</th>
                <td>
                    <label>
                        <input type="checkbox" name="landing_page_hide_footer" value="1" <?php checked($hide_footer, '1'); ?>>
                        フッターを非表示にする
                    </label>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-bottom: 20px;">
        <h3>ヒーローセクション設定（レイアウトタイプが「ヒーローセクション付き」の場合に表示）</h3>
        <table class="form-table">
            <tr>
                <th scope="row">ヒーロータイトル</th>
                <td>
                    <input type="text" name="landing_page_hero_title" value="<?php echo esc_attr($hero_title); ?>" class="large-text">
                </td>
            </tr>
            <tr>
                <th scope="row">ヒーローサブタイトル</th>
                <td>
                    <textarea name="landing_page_hero_subtitle" rows="2" class="large-text"><?php echo esc_textarea($hero_subtitle); ?></textarea>
                </td>
            </tr>
            <tr>
                <th scope="row">ボタンテキスト</th>
                <td>
                    <input type="text" name="landing_page_hero_button_text" value="<?php echo esc_attr($hero_button_text); ?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">ボタンURL</th>
                <td>
                    <input type="url" name="landing_page_hero_button_url" value="<?php echo esc_url($hero_button_url); ?>" class="large-text">
                </td>
            </tr>
        </table>
    </div>

    <div>
        <h3>カスタムCSS</h3>
        <p>このランディングページ専用のCSSを追加できます。</p>
        <textarea name="landing_page_custom_css" rows="10" style="width: 100%; font-family: monospace;"><?php echo esc_textarea($custom_css); ?></textarea>
    </div>

    <script>
    jQuery(document).ready(function($) {
        function toggleHeroSettings() {
            var layoutType = $('#landing_page_layout_type').val();
            if (layoutType === 'hero') {
                $('[name^="landing_page_hero_"]').closest('tr').show();
            } else {
                $('[name^="landing_page_hero_"]').closest('tr').hide();
            }
        }

        toggleHeroSettings();
        $('#landing_page_layout_type').on('change', toggleHeroSettings);
    });
    </script>
    <?php
}

// メタボックスの保存
function arigatou_club_save_landing_page_meta($post_id) {
    // nonce チェック
    if (!isset($_POST['arigatou_club_landing_page_nonce']) ||
        !wp_verify_nonce($_POST['arigatou_club_landing_page_nonce'], 'arigatou_club_landing_page_settings')) {
        return;
    }

    // 自動保存の場合は何もしない
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // 権限チェック
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // データを保存
    $fields = array(
        '_landing_page_hide_header' => isset($_POST['landing_page_hide_header']) ? '1' : '0',
        '_landing_page_hide_footer' => isset($_POST['landing_page_hide_footer']) ? '1' : '0',
        '_landing_page_custom_css' => sanitize_textarea_field($_POST['landing_page_custom_css'] ?? ''),
        '_landing_page_hero_title' => sanitize_text_field($_POST['landing_page_hero_title'] ?? ''),
        '_landing_page_hero_subtitle' => sanitize_textarea_field($_POST['landing_page_hero_subtitle'] ?? ''),
        '_landing_page_hero_button_text' => sanitize_text_field($_POST['landing_page_hero_button_text'] ?? ''),
        '_landing_page_hero_button_url' => esc_url_raw($_POST['landing_page_hero_button_url'] ?? ''),
        '_landing_page_layout_type' => sanitize_text_field($_POST['landing_page_layout_type'] ?? 'default'),
    );

    foreach ($fields as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }
}
add_action('save_post_landing_page', 'arigatou_club_save_landing_page_meta');

// ランディングページ用のボディクラスを追加
function arigatou_club_landing_page_body_class($classes) {
    if (is_singular('landing_page')) {
        $classes[] = 'landing-page';

        $layout_type = get_post_meta(get_the_ID(), '_landing_page_layout_type', true);
        if ($layout_type) {
            $classes[] = 'landing-layout-' . $layout_type;
        }
    }
    return $classes;
}
add_filter('body_class', 'arigatou_club_landing_page_body_class');

// ランディングページテンプレートの選択
function arigatou_club_landing_page_template($template) {
    if (is_singular('landing_page')) {
        $new_template = locate_template(array('single-landing_page.php'));
        if (!empty($new_template)) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'arigatou_club_landing_page_template');

// ランディングページ専用のサイドバーを登録
function arigatou_club_register_landing_sidebar() {
    register_sidebar(array(
        'name'          => 'ランディングページサイドバー',
        'id'            => 'landing-page-sidebar',
        'description'   => 'ランディングページのサイドバーエリア',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'arigatou_club_register_landing_sidebar');