<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#main">メインコンテンツへスキップ</a>
    
    <header id="masthead" class="site-header">
        <div class="container">
            <div class="site-branding">
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
                    <?php
                    $description = get_bloginfo('description', 'display');
                    if ($description || is_customize_preview()) : ?>
                        <p class="site-description"><?php echo $description; ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <nav id="site-navigation" class="main-navigation">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="menu-toggle-inner">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
                
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id' => 'primary-menu',
                    'container' => false,
                    'fallback_cb' => function() {
                        echo '<ul id="primary-menu" class="menu">';
                        echo '<li><a href="' . home_url('/') . '">TOP</a></li>';
                        echo '<li><a href="' . home_url('/about/') . '">ありがとう倶楽部について</a></li>';
                        echo '<li><a href="' . get_post_type_archive_link('event') . '">イベント</a></li>';
                        echo '<li><a href="' . home_url('/blog/') . '">ブログ</a></li>';
                        echo '<li><a href="' . home_url('/contact/') . '">お問い合わせ</a></li>';
                        echo '</ul>';
                    }
                ));
                ?>
            </nav>
        </div>
    </header>