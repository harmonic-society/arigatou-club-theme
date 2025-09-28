<?php
/**
 * 追加のSEO強化機能
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * WebP画像のサポートを追加
 */
function arigatou_club_webp_support($mime_types) {
    $mime_types['webp'] = 'image/webp';
    return $mime_types;
}
add_filter('upload_mimes', 'arigatou_club_webp_support');

/**
 * 画像の最適化設定
 */
function arigatou_club_optimize_images() {
    // 大きな画像サイズの制限
    add_filter('big_image_size_threshold', function() {
        return 2560; // 最大幅2560px
    });

    // JPEG品質の設定
    add_filter('jpeg_quality', function() {
        return 85; // 85%の品質
    });
}
add_action('init', 'arigatou_club_optimize_images');

/**
 * Critical CSSのインライン化
 * 注: ヒーローセクションのスタイルは含めない（既存のスタイルと競合するため）
 */
function arigatou_club_inline_critical_css() {
    // Critical CSSを一時的に無効化（ヒーローセクションの表示問題を修正）
    // 必要に応じて、より精密なCritical CSSを後で追加
    return;
}
// add_action('wp_head', 'arigatou_club_inline_critical_css', 1);

/**
 * リソースヒントの追加
 */
function arigatou_club_resource_hints($hints, $relation_type) {
    if ('dns-prefetch' === $relation_type) {
        $hints[] = '//fonts.googleapis.com';
        $hints[] = '//fonts.gstatic.com';
        $hints[] = '//www.google-analytics.com';
        $hints[] = '//www.googletagmanager.com';
    } elseif ('preconnect' === $relation_type) {
        $hints[] = array(
            'href' => 'https://fonts.googleapis.com',
            'crossorigin' => 'anonymous',
        );
        $hints[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        );
    }
    return $hints;
}
add_filter('wp_resource_hints', 'arigatou_club_resource_hints', 10, 2);

/**
 * スクリプトの非同期・遅延読み込み
 */
function arigatou_club_async_defer_scripts($tag, $handle) {
    // 非同期読み込みするスクリプト
    $async_scripts = array('google-analytics', 'facebook-sdk');

    // 遅延読み込みするスクリプト（存在するハンドルのみ）
    $defer_scripts = array();

    if (in_array($handle, $async_scripts)) {
        return str_replace(' src', ' async src', $tag);
    }

    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }

    return $tag;
}
add_filter('script_loader_tag', 'arigatou_club_async_defer_scripts', 10, 2);

/**
 * ローカルビジネス構造化データの強化
 */
function arigatou_club_local_business_schema() {
    if (is_front_page()) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => 'ありがとう倶楽部',
            'image' => get_site_icon_url(512),
            'description' => '千葉県千葉市を中心に「ありがとう」の心を広げる活動を行っている地域団体',
            '@id' => home_url('/#organization'),
            'url' => home_url('/'),
            'telephone' => '+81-XXX-XXX-XXXX',
            'address' => array(
                '@type' => 'PostalAddress',
                'addressLocality' => '千葉市',
                'addressRegion' => '千葉県',
                'postalCode' => 'XXX-XXXX',
                'addressCountry' => 'JP'
            ),
            'geo' => array(
                '@type' => 'GeoCoordinates',
                'latitude' => 35.6074,
                'longitude' => 140.1065
            ),
            'openingHoursSpecification' => array(
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'),
                'opens' => '09:00',
                'closes' => '18:00'
            ),
            'priceRange' => '無料〜',
            'servesCuisine' => '地域交流・社会貢献活動'
        );

        echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }
}
add_action('wp_head', 'arigatou_club_local_business_schema');

/**
 * FAQ構造化データの追加（よくある質問ページ用）
 */
function arigatou_club_faq_schema() {
    if (is_page('faq') || is_page('よくある質問')) {
        $faqs = array(
            array(
                'question' => 'ありがとう倶楽部への入会方法は？',
                'answer' => 'お問い合わせフォームまたはイベント会場で直接お申し込みいただけます。'
            ),
            array(
                'question' => '会費はかかりますか？',
                'answer' => '基本的に無料です。イベントによっては参加費が必要な場合があります。'
            ),
            array(
                'question' => 'どのような活動をしていますか？',
                'answer' => '地域交流イベント、ボランティア活動、講演会、ワークショップなどを開催しています。'
            )
        );

        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array()
        );

        foreach ($faqs as $faq) {
            $schema['mainEntity'][] = array(
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text' => $faq['answer']
                )
            );
        }

        echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }
}
add_action('wp_head', 'arigatou_club_faq_schema');

/**
 * AMP対応の準備
 */
function arigatou_club_amp_support() {
    // AMPページ用のリンクタグを追加
    if (is_single()) {
        $amp_url = add_query_arg('amp', '1', get_permalink());
        echo '<link rel="amphtml" href="' . esc_url($amp_url) . '">' . "\n";
    }
}
add_action('wp_head', 'arigatou_club_amp_support');

/**
 * 多言語対応のメタタグ
 */
function arigatou_club_hreflang_tags() {
    // 日本語版のみの場合
    echo '<link rel="alternate" hreflang="ja" href="' . esc_url(get_permalink()) . '">' . "\n";
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url(get_permalink()) . '">' . "\n";
}
add_action('wp_head', 'arigatou_club_hreflang_tags');

/**
 * セキュリティヘッダーの追加
 */
function arigatou_club_security_headers() {
    if (!is_admin()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: geolocation=(self), microphone=(), camera=()');
    }
}
add_action('send_headers', 'arigatou_club_security_headers');

/**
 * コンテンツの自動抜粋最適化
 */
function arigatou_club_optimize_excerpt($excerpt) {
    // メタディスクリプション用に最適化
    $excerpt = strip_tags($excerpt);
    $excerpt = str_replace(array("\r", "\n"), ' ', $excerpt);
    $excerpt = mb_substr($excerpt, 0, 155) . '...';
    return $excerpt;
}
add_filter('get_the_excerpt', 'arigatou_club_optimize_excerpt');

/**
 * メタディスクリプションとメタキーワードの追加
 */
function arigatou_club_meta_tags() {
    // デフォルトのメタディスクリプション
    $default_description = 'ありがとう倶楽部は千葉県千葉市を中心に「ありがとう」の心を広げる活動を行う地域団体です。地域交流イベント、ボランティア活動、講演会、ワークショップなどを通じて、感謝の気持ちを大切にする社会づくりに貢献しています。';

    // デフォルトのメタキーワード
    $default_keywords = 'ありがとう倶楽部,千葉県,千葉市,地域交流,ボランティア,感謝,ありがとう,社会貢献,イベント,講演会,ワークショップ,地域活動,コミュニティ';

    // ページタイプごとのメタ情報
    $description = '';
    $keywords = $default_keywords;

    if (is_front_page()) {
        $description = 'ありがとう倶楽部は「ありがとう」の心を広げる千葉県千葉市の地域団体。地域交流イベント、ボランティア活動、講演会を通じて感謝の気持ちを大切にする社会づくりを推進。入会無料、どなたでも参加できます。';
        $keywords = 'ありがとう倶楽部,千葉県,千葉市,地域交流,ボランティア活動,感謝の心,社会貢献活動,無料イベント,地域コミュニティ,講演会,ワークショップ';

    } elseif (is_page('about')) {
        $description = 'ありがとう倶楽部について。私たちは「ありがとう」の心を大切に、千葉県千葉市を中心に地域交流とボランティア活動を展開。設立理念、活動内容、メンバー紹介、参加方法をご案内します。';
        $keywords .= ',倶楽部について,設立理念,活動内容,メンバー紹介,参加方法,ミッション,ビジョン';

    } elseif (is_page('contact')) {
        $description = 'ありがとう倶楽部へのお問い合わせ・入会申込みページ。イベント参加、ボランティア活動、講演依頼、協賛のご相談など、お気軽にご連絡ください。千葉県千葉市を中心に活動中。';
        $keywords .= ',お問い合わせ,入会申込み,連絡先,講演依頼,協賛募集';

    } elseif (is_post_type_archive('event')) {
        $description = 'ありがとう倶楽部のイベント情報。千葉県内で開催する地域交流イベント、ボランティア活動、講演会、ワークショップの最新情報をお届け。参加無料のイベント多数開催中。';
        $keywords .= ',イベント情報,イベントスケジュール,参加無料,地域イベント,交流会';

    } elseif (is_singular('event')) {
        $post = get_post();
        $excerpt = wp_trim_words(strip_tags($post->post_content), 30);
        $description = $post->post_title . ' - ありがとう倶楽部主催。' . $excerpt;
        $keywords .= ',' . $post->post_title . ',イベント詳細';

    } elseif (is_post_type_archive('sponsor')) {
        $description = 'ありがとう倶楽部の協賛企業・スポンサー一覧。私たちの「ありがとう」の心を広げる活動を支援してくださる千葉県内外の企業様をご紹介。協賛のご相談も受付中。';
        $keywords .= ',協賛企業,スポンサー,支援企業,パートナー企業,協賛募集';

    } elseif (is_singular('sponsor')) {
        $post = get_post();
        $description = $post->post_title . ' - ありがとう倶楽部協賛企業。' . wp_trim_words(strip_tags($post->post_content), 25);
        $keywords .= ',' . $post->post_title . ',協賛企業';

    } elseif (is_home() || is_archive()) {
        $description = 'ありがとう倶楽部ブログ。活動報告、イベントレポート、感謝のエピソード、地域交流の様子など、日々の活動を発信。千葉県の地域活性化と「ありがとう」の心を広げる取り組みをご紹介。';
        $keywords .= ',ブログ,活動報告,イベントレポート,活動記録,コラム';

    } elseif (is_single()) {
        $post = get_post();
        $excerpt = get_the_excerpt();
        if (empty($excerpt)) {
            $excerpt = wp_trim_words(strip_tags($post->post_content), 30);
        }
        $description = mb_substr($excerpt, 0, 155);

        // カテゴリーとタグからキーワードを生成
        $categories = get_the_category();
        $tags = get_the_tags();
        if ($categories) {
            foreach ($categories as $category) {
                $keywords .= ',' . $category->name;
            }
        }
        if ($tags) {
            foreach ($tags as $tag) {
                $keywords .= ',' . $tag->name;
            }
        }

    } elseif (is_404()) {
        $description = 'ページが見つかりません - ありがとう倶楽部。お探しのページは移動または削除された可能性があります。トップページから目的のページをお探しください。';
        $keywords .= ',404エラー,ページが見つかりません';

    } else {
        // その他のページはデフォルトを使用
        $description = $default_description;
    }

    // メタディスクリプションが空の場合はデフォルトを使用
    if (empty($description)) {
        $description = $default_description;
    }

    // メタタグを出力
    echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";

    // OGPメタタグ
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta property="og:site_name" content="ありがとう倶楽部">' . "\n";
    echo '<meta property="og:locale" content="ja_JP">' . "\n";

    // Twitterカード
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
}
add_action('wp_head', 'arigatou_club_meta_tags', 5);

/**
 * 検索エンジン向けのサイト情報
 */
function arigatou_club_site_verification() {
    // Google Search Console
    echo '<meta name="google-site-verification" content="YOUR_GOOGLE_VERIFICATION_CODE">' . "\n";

    // Bing Webmaster Tools
    echo '<meta name="msvalidate.01" content="YOUR_BING_VERIFICATION_CODE">' . "\n";

    // Pinterest
    echo '<meta name="p:domain_verify" content="YOUR_PINTEREST_VERIFICATION_CODE">' . "\n";
}
add_action('wp_head', 'arigatou_club_site_verification', 1);