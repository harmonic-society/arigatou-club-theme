<?php
/**
 * Template Name: お問い合わせ完了
 * 
 * お問い合わせ送信後のサンクスページテンプレート
 */

get_header(); ?>

<main id="main" class="site-main wa-style">
    
    <!-- ページヘッダー -->
    <section class="page-header thanks-header">
        <div class="wa-pattern-overlay"></div>
        <div class="container">
            <h1 class="page-title">お問い合わせありがとうございます</h1>
            <p class="page-subtitle">Thank you for your message</p>
        </div>
    </section>
    
    <!-- サンクスセクション -->
    <section class="thanks-section section">
        <div class="container">
            <div class="thanks-wrapper">
                
                <!-- 感謝のメッセージカード -->
                <div class="thanks-card">
                    <div class="thanks-icon-wrapper">
                        <div class="thanks-icon">
                            <span class="icon-circle"></span>
                            <span class="icon-check">✓</span>
                        </div>
                    </div>
                    
                    <div class="thanks-content">
                        <h2 class="thanks-title">送信が完了しました</h2>
                        
                        <div class="thanks-message">
                            <p class="thanks-lead">
                                この度は、ありがとう倶楽部へお問い合わせいただき、<br>
                                誠にありがとうございます。
                            </p>
                            
                            <div class="thanks-details">
                                <p>
                                    お送りいただいた内容を確認の上、<br class="sp-only">
                                    担当者より折り返しご連絡させていただきます。
                                </p>
                                <p>
                                    通常、2〜3営業日以内にご返信いたしますので、<br class="sp-only">
                                    今しばらくお待ちください。
                                </p>
                            </div>
                            
                            <div class="thanks-note">
                                <p class="note-title">📧 ご確認ください</p>
                                <ul>
                                    <li>自動返信メールをお送りしております</li>
                                    <li>メールが届かない場合は、迷惑メールフォルダをご確認ください</li>
                                    <li>お急ぎの場合は、お電話でもお問い合わせいただけます</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="thanks-actions">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                                <span class="btn-text">トップページへ戻る</span>
                                <span class="btn-arrow">→</span>
                            </a>
                            <a href="<?php echo esc_url(home_url('/about/')); ?>" class="btn btn-secondary">
                                倶楽部について詳しく見る
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- 関連リンク -->
                <div class="thanks-links">
                    <h3 class="links-title">こちらもご覧ください</h3>
                    <div class="links-grid">
                        <a href="<?php echo get_post_type_archive_link('event'); ?>" class="link-card">
                            <div class="link-icon">📅</div>
                            <div class="link-content">
                                <h4>イベント情報</h4>
                                <p>最新のイベント・活動予定をチェック</p>
                            </div>
                            <div class="link-arrow">→</div>
                        </a>
                        
                        <a href="<?php echo esc_url(home_url('/blog/')); ?>" class="link-card">
                            <div class="link-icon">📝</div>
                            <div class="link-content">
                                <h4>ブログ</h4>
                                <p>活動報告や日々の出来事を更新中</p>
                            </div>
                            <div class="link-arrow">→</div>
                        </a>
                        
                        <a href="https://arigatou-goods.stores.jp/" target="_blank" rel="noopener" class="link-card">
                            <div class="link-icon">🛍️</div>
                            <div class="link-content">
                                <h4>グッズ販売</h4>
                                <p>オリジナルグッズをオンラインで販売中</p>
                            </div>
                            <div class="link-arrow">↗</div>
                        </a>
                    </div>
                </div>
                
                <!-- ソーシャルメディア -->
                <div class="thanks-social">
                    <p class="social-title">最新情報はSNSでも発信中！</p>
                    <div class="social-links">
                        <a href="#" class="social-link facebook" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link twitter" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link instagram" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link line" aria-label="LINE">
                            <i class="fab fa-line"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
</main>

<?php get_footer(); ?>