<?php
/**
 * Template Name: お問い合わせ (CF7版)
 * 
 * Contact Form 7を使用するお問い合わせページテンプレート
 */

get_header(); ?>

<main id="main" class="site-main wa-style">
    
    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="wa-pattern-overlay"></div>
        <div class="container">
            <h1 class="page-title">お問い合わせ</h1>
            <p class="page-subtitle">ご質問・ご相談はお気軽にどうぞ</p>
        </div>
    </section>
    
    <!-- お問い合わせセクション -->
    <section class="contact-section section">
        <div class="container">
            <div class="contact-layout">
                
                <!-- メインコンテンツ -->
                <div class="contact-main">
                    
                    <div class="contact-intro">
                        <p>ありがとう倶楽部へのお問い合わせは、下記のフォームよりお願いいたします。</p>
                        <p>会員登録、イベント参加、協賛のご相談など、どのようなことでもお気軽にご連絡ください。</p>
                    </div>
                    
                    <!-- Contact Form 7 ショートコード挿入エリア -->
                    <div class="contact-form-wrapper">
                        <?php
                        // Contact Form 7のショートコードを出力
                        // 実際のIDは Contact Form 7 で作成後に置き換えてください
                        echo do_shortcode('[contact-form-7 id="319960c" title="ありがとう倶楽部-お問い合わせフォーム"]');
                        ?>
                        
                        <!-- 代替: ページコンテンツから自動的にショートコードを表示 -->
                        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                            <?php the_content(); ?>
                        <?php endwhile; endif; ?>
                    </div>
                    
                </div>
                
                <!-- サイドバー -->
                <aside class="contact-sidebar">
                    
                    <!-- よくあるご質問 -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">よくあるご質問</h3>
                        <div class="faq-list">
                            <div class="faq-item">
                                <h4>Q. 会員登録に費用はかかりますか？</h4>
                                <p>A. 無料会員と有料会員があります。有料会員は月額1,000円または年額10,000円です。年金生活者と学生は無料です。</p>
                            </div>
                            <div class="faq-item">
                                <h4>Q. イベントには誰でも参加できますか？</h4>
                                <p>A. はい、どなたでもご参加いただけます。無料会員の方はスポット料金でのご参加となります。</p>
                            </div>
                            <div class="faq-item">
                                <h4>Q. 企業として協賛したいのですが？</h4>
                                <p>A. ありがとうございます。詳細についてはお問い合わせフォームよりご連絡ください。</p>
                            </div>
                            <div class="faq-item">
                                <h4>Q. 返信はどのくらいで来ますか？</h4>
                                <p>A. 通常、2〜3営業日以内にご返信させていただきます。お急ぎの場合はその旨をお知らせください。</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 個人情報の取り扱い -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">個人情報の取り扱い</h3>
                        <div class="privacy-policy-summary">
                            <p>お預かりした個人情報は、お問い合わせへの回答およびありがとう倶楽部の活動に関するご案内にのみ使用いたします。</p>
                            <ul>
                                <li>第三者への提供は一切行いません</li>
                                <li>SSL暗号化通信により安全に送信されます</li>
                                <li>ご本人の同意なく個人情報を利用することはありません</li>
                            </ul>
                            <p>詳細は<a href="<?php echo home_url('/privacy-policy/'); ?>">プライバシーポリシー</a>をご確認ください。</p>
                        </div>
                    </div>

                </aside>
                
            </div>
        </div>
    </section>
    
</main>

<?php get_footer(); ?>