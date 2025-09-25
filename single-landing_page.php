<?php
/**
 * ランディングページテンプレート
 */

// ランディングページ設定を取得
$hide_header = get_post_meta(get_the_ID(), '_landing_page_hide_header', true);
$hide_footer = get_post_meta(get_the_ID(), '_landing_page_hide_footer', true);
$custom_css = get_post_meta(get_the_ID(), '_landing_page_custom_css', true);
$layout_type = get_post_meta(get_the_ID(), '_landing_page_layout_type', true) ?: 'default';

// ヒーローセクション設定を取得
$hero_title = get_post_meta(get_the_ID(), '_landing_page_hero_title', true);
$hero_subtitle = get_post_meta(get_the_ID(), '_landing_page_hero_subtitle', true);
$hero_button_text = get_post_meta(get_the_ID(), '_landing_page_hero_button_text', true);
$hero_button_url = get_post_meta(get_the_ID(), '_landing_page_hero_button_url', true);

// ヘッダーの表示
if ($hide_header !== '1') {
    get_header();
}
?>

<?php if ($hide_header === '1') : ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    <?php if (!empty($custom_css)) : ?>
    <style type="text/css">
        <?php echo wp_strip_all_tags($custom_css); ?>
    </style>
    <?php endif; ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php else : ?>
    <?php if (!empty($custom_css)) : ?>
    <style type="text/css">
        <?php echo wp_strip_all_tags($custom_css); ?>
    </style>
    <?php endif; ?>
<?php endif; ?>

<main id="landing-page-main" class="landing-page-content">
    <?php while (have_posts()) : the_post(); ?>

        <?php if ($layout_type === 'hero' && ($hero_title || has_post_thumbnail())) : ?>
        <!-- ヒーローセクション -->
        <section class="landing-hero-section">
            <?php if (has_post_thumbnail()) : ?>
                <div class="landing-hero-background" style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>');">
                    <div class="landing-hero-overlay"></div>
                </div>
            <?php else : ?>
                <div class="landing-hero-background landing-hero-default">
                    <div class="landing-hero-overlay"></div>
                </div>
            <?php endif; ?>

            <div class="landing-hero-content">
                <div class="container">
                    <?php if ($hero_title) : ?>
                        <h1 class="landing-hero-title"><?php echo esc_html($hero_title); ?></h1>
                    <?php else : ?>
                        <h1 class="landing-hero-title"><?php the_title(); ?></h1>
                    <?php endif; ?>

                    <?php if ($hero_subtitle) : ?>
                        <p class="landing-hero-subtitle"><?php echo nl2br(esc_html($hero_subtitle)); ?></p>
                    <?php endif; ?>

                    <?php if ($hero_button_text && $hero_button_url) : ?>
                        <div class="landing-hero-buttons">
                            <a href="<?php echo esc_url($hero_button_url); ?>" class="btn-landing-hero"><?php echo esc_html($hero_button_text); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- メインコンテンツ -->
        <div class="landing-main-content <?php echo 'layout-' . esc_attr($layout_type); ?>">
            <?php if ($layout_type === 'sidebar') : ?>
                <div class="container">
                    <div class="landing-content-wrapper">
                        <article class="landing-article">
                            <?php if ($layout_type !== 'hero') : ?>
                                <h1 class="landing-title"><?php the_title(); ?></h1>
                            <?php endif; ?>
                            <div class="landing-content">
                                <?php the_content(); ?>
                            </div>
                        </article>
                        <aside class="landing-sidebar">
                            <?php if (is_active_sidebar('landing-page-sidebar')) : ?>
                                <?php dynamic_sidebar('landing-page-sidebar'); ?>
                            <?php else : ?>
                                <!-- デフォルトのサイドバー内容 -->
                                <div class="widget">
                                    <h3 class="widget-title">お問い合わせ</h3>
                                    <p>ご質問やお問い合わせはこちらから</p>
                                    <a href="<?php echo home_url('/contact/'); ?>" class="btn btn-primary">お問い合わせフォーム</a>
                                </div>
                            <?php endif; ?>
                        </aside>
                    </div>
                </div>
            <?php elseif ($layout_type === 'fullwidth') : ?>
                <article class="landing-article fullwidth">
                    <?php if ($layout_type !== 'hero') : ?>
                        <div class="container">
                            <h1 class="landing-title"><?php the_title(); ?></h1>
                        </div>
                    <?php endif; ?>
                    <div class="landing-content">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php else : // default layout ?>
                <div class="container">
                    <article class="landing-article">
                        <?php if ($layout_type !== 'hero') : ?>
                            <h1 class="landing-title"><?php the_title(); ?></h1>
                        <?php endif; ?>
                        <div class="landing-content">
                            <?php the_content(); ?>
                        </div>
                    </article>
                </div>
            <?php endif; ?>
        </div>

    <?php endwhile; ?>
</main>

<?php if ($hide_footer !== '1') : ?>
    <?php get_footer(); ?>
<?php else : ?>
    <?php wp_footer(); ?>
    </body>
    </html>
<?php endif; ?>