<?php
/**
 * Template Name: お問い合わせ
 * 
 * お問い合わせページテンプレート
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
                    
                    <!-- お問い合わせフォーム -->
                    <div class="contact-form-wrapper">
                        <form class="contact-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                            <?php wp_nonce_field('arigatou_contact_form', 'arigatou_contact_nonce'); ?>
                            <input type="hidden" name="action" value="arigatou_contact_form">
                            
                            <div class="form-group">
                                <label for="contact-type">お問い合わせ種別 <span class="required">*</span></label>
                                <select id="contact-type" name="contact_type" required>
                                    <option value="">選択してください</option>
                                    <option value="会員登録">会員登録について</option>
                                    <option value="イベント参加">イベント参加について</option>
                                    <option value="協賛">協賛について</option>
                                    <option value="その他">その他</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact-name">お名前 <span class="required">*</span></label>
                                <input type="text" id="contact-name" name="contact_name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact-email">メールアドレス <span class="required">*</span></label>
                                <input type="email" id="contact-email" name="contact_email" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact-phone">電話番号</label>
                                <input type="tel" id="contact-phone" name="contact_phone">
                            </div>
                            
                            <div class="form-group">
                                <label for="contact-subject">件名 <span class="required">*</span></label>
                                <input type="text" id="contact-subject" name="contact_subject" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact-message">お問い合わせ内容 <span class="required">*</span></label>
                                <textarea id="contact-message" name="contact_message" rows="8" required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="contact_agreement" required>
                                    <span>個人情報の取り扱いについて同意します</span>
                                </label>
                            </div>
                            
                            <div class="form-submit">
                                <button type="submit" class="btn btn-primary">送信する</button>
                            </div>
                        </form>
                        
                        <?php
                        // メッセージ表示
                        if (isset($_GET['status'])) {
                            if ($_GET['status'] === 'success') {
                                echo '<div class="form-message success">お問い合わせありがとうございます。内容を確認の上、ご連絡させていただきます。</div>';
                            } elseif ($_GET['status'] === 'error') {
                                echo '<div class="form-message error">送信に失敗しました。お手数ですが、もう一度お試しください。</div>';
                            }
                        }
                        ?>
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
                        </div>
                    </div>
                    
                    <!-- プライバシーポリシー -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">個人情報の取り扱い</h3>
                        <p>お預かりした個人情報は、お問い合わせへの回答およびありがとう倶楽部の活動に関するご案内にのみ使用いたします。</p>
                        <p>第三者への提供は一切行いません。</p>
                    </div>
                    
                    <!-- SNS -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">公式SNS</h3>
                        <div class="social-links">
                            <a href="#" class="social-link facebook">Facebook</a>
                            <a href="#" class="social-link twitter">Twitter</a>
                            <a href="#" class="social-link instagram">Instagram</a>
                        </div>
                    </div>
                    
                </aside>
                
            </div>
        </div>
    </section>
    
</main>

<?php get_footer(); ?>