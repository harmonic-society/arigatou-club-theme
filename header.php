<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-R10TT3Q78S"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-R10TT3Q78S');
    </script>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#main">メインコンテンツへスキップ</a>
    
    <header id="masthead" class="site-header">
        <div class="container">
            <div class="header-inner">
                <div class="site-branding">
                    <?php 
                    $site_icon_url = get_site_icon_url(64);
                    if ($site_icon_url) : ?>
                        <div class="site-icon-wrapper">
                            <img src="<?php echo esc_url($site_icon_url); ?>" alt="<?php bloginfo('name'); ?> アイコン" class="header-site-icon">
                        </div>
                    <?php endif; ?>
                    
                    <?php if (has_custom_logo()) : ?>
                        <div class="site-logo">
                            <?php the_custom_logo(); ?>
                        </div>
                    <?php else : ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <?php bloginfo('name'); ?>
                            </a>
                        </h1>
                    <?php endif; ?>
                </div>
                
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="メニュー">
                    <span class="menu-toggle-inner">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
                
                <nav id="site-navigation" class="main-navigation" aria-hidden="true">
                
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id' => 'primary-menu',
                    'container' => false,
                    'fallback_cb' => function() {
                        echo '<ul id="primary-menu" class="menu">';
                        echo '<li><a href="' . home_url('/') . '">TOP</a></li>';
                        echo '<li><a href="' . home_url('/about/') . '">倶楽部について</a></li>';
                        echo '<li><a href="' . get_post_type_archive_link('event') . '">イベント</a></li>';
                        echo '<li><a href="' . home_url('/blog/') . '">ブログ</a></li>';
                        echo '<li><a href="' . get_post_type_archive_link('sponsor') . '">協賛企業</a></li>';
                        echo '<li><a href="https://arigatohclub.base.shop/" target="_blank" rel="noopener">グッズ販売<span class="external-link-icon">🛒</span></a></li>';
                        echo '<li><a href="' . home_url('/contact/') . '">お問い合わせ</a></li>';
                        // 会員メニュー（ログイン状態で切替）
                        if (is_user_logged_in()) {
                            echo '<li class="menu-item menu-item-member logged-in"><a href="' . home_url('/my-account/') . '">マイページ</a></li>';
                        } else {
                            echo '<li class="menu-item menu-item-member"><a href="' . home_url('/membership/') . '">お申し込み</a></li>';
                        }
                        echo '</ul>';
                    }
                ));
                ?>
                </nav>
            </div>
        </div>
    </header>