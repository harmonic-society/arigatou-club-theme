<?php
/**
 * Template Name: ありがとう倶楽部について
 * 
 * ありがとう倶楽部についてのページテンプレート
 */

get_header(); ?>

<main id="main" class="site-main wa-style">
    
    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="wa-pattern-overlay"></div>
        <div class="container">
            <h1 class="page-title">ありがとう倶楽部について</h1>
            <p class="page-subtitle">感謝の心で繋がる、温かい和の世界</p>
        </div>
    </section>
    
    <!-- 目的セクション -->
    <section class="purpose-section section">
        <div class="container">
            <div class="section-header">
                <span class="section-marker">◯</span>
                <h2 class="section-title">ありがとう倶楽部の目的</h2>
            </div>
            
            <div class="purpose-cards">
                <div class="purpose-card main-purpose">
                    <div class="card-inner">
                        <h3>ありがとうを大事にする人たちの集まる場作り</h3>
                    </div>
                </div>
                
                <div class="purpose-grid">
                    <div class="purpose-card">
                        <div class="card-inner">
                            <p>身の回りのありがとうに氣づいて、きちんと感謝を言葉にする</p>
                        </div>
                    </div>
                    
                    <div class="purpose-card">
                        <div class="card-inner">
                            <p>自分の天才を発見 発達 発揮すること</p>
                        </div>
                    </div>
                    
                    <div class="purpose-card">
                        <div class="card-inner">
                            <p>仲間の天才を発見 発達 発揮させること</p>
                        </div>
                    </div>
                    
                    <div class="purpose-card">
                        <div class="card-inner">
                            <p>お互いの天才を生かし合って、大いなる調和=大和（だいわ）の世界（お好み焼き社会）を作ること</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="purpose-notes">
                <div class="note-item">
                    <span class="note-marker">※</span>
                    <p>人は皆違うものだという認識のもと、自分の天才（天賦の才）があることを前提として、天才を発見し 発達させ発揮し、周りの人たちを喜ばせたり 助けたりして、周りの人も自分も幸せを感じる人となる。</p>
                </div>
                
                <div class="note-item">
                    <span class="note-marker">※</span>
                    <div>
                        <p><strong>お好み焼き社会</strong>　お好み焼きというのはどんな素材を入れても成立する、まさに大和の世界、大いなる調和はハーモニーの料理ではないでしょうか。</p>
                        <p class="emphasis">ありがとう倶楽部は、平たく まあるく 温かい お好み焼き のような大和の世界を、目指していきます。</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- 代表挨拶セクション -->
    <section class="greeting-section section">
        <div class="container">
            <div class="section-header">
                <span class="section-marker">◯</span>
                <h2 class="section-title">代表ご挨拶</h2>
            </div>
            
            <div class="greeting-content">
                <?php 
                $representative_photo = get_theme_mod('representative_photo');
                $representative_name = get_theme_mod('representative_name', '秋山');
                $representative_title = get_theme_mod('representative_title', 'ありがとう倶楽部 代表');
                
                if ($representative_photo) : ?>
                    <div class="representative-profile">
                        <div class="profile-photo">
                            <img src="<?php echo esc_url($representative_photo); ?>" alt="<?php echo esc_attr($representative_name); ?>">
                        </div>
                        <div class="profile-info">
                            <h3 class="representative-name"><?php echo esc_html($representative_name); ?></h3>
                            <p class="representative-title"><?php echo esc_html($representative_title); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="greeting-message <?php echo $representative_photo ? 'with-photo' : ''; ?>">
                    <p class="greeting-intro">ありがとう倶楽部の<?php echo esc_html($representative_name); ?>です。ホームページへようこそお越しくださいました。</p>
                    
                    <p>ありがとう倶楽部は、ありがとうを大切にする人たちの集まりです。お互いの天才を生かし合って、喜ばせあって助け合って、暮らしやすい大和の社会を作っていきましょう。</p>
                    
                    <p>人は皆違うし、それぞれ 天才があると感じています。違うのは天才を活かしている人とうまく活かせていない人がいるだけで、誰しもが天才を持っていることには変わりがないと思います。</p>
                    
                    <p>多くの方は自分には天才などないと思っていらっしゃるかもしれませんが、私はそれが壮大な誤解なのではないかと感じています。</p>
                    
                    <p>その自分には天才などないという前提を、全ての人が天才を持っているのだという前提に書き換えたいのです。書き換えた上で、ではどうすれば天才を発見 発達 発揮させることができるのか、周りの人を喜ばせたり 助けたりするのができるのかということについて、この ありがとう倶楽部で仲間と一緒に探求、実践していけたら、こんなに嬉しいことはありません。</p>
                    
                    <p>また昨今 日本のみならず世界においても、自分さえよければいいという考え方が蔓延することによって、サービスを受けるお客様中心でなく自分たち中心の考え方、下手をすると騙される方が悪いのだと言わんばかりの商売のあり方が見られるようになりました。とても残念なことだと思います。</p>
                    
                    <p>それならば、ありがとう倶楽部という本当にありがとう を実践しようとする人たちの集団の中で、騙し騙される関係ではなく、助け 喜び合う関係を結ぶことで、安心して売買ができるのではないかと感じています。</p>
                    
                    <p>働くとは、 端を楽にするという考え方があります。私はこの考え方が大好きです。天才をもって 周りの人を楽しませる 助ける、こういう人間関係をありがとう倶楽部を通じて広げていくことができたら、どれだけ楽しい社会になることだろうと今からワクワクしています。</p>
                    
                    <p class="greeting-closing">皆様にご参加いただいて、活発 自己表現および活動を一緒にしていただけることを楽しみにお待ちしております。</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- 活動内容セクション -->
    <section class="activities-section section">
        <div class="container">
            <div class="section-header">
                <span class="section-marker">◯</span>
                <h2 class="section-title">活動内容</h2>
            </div>
            
            <div class="activities-grid">
                <div class="activity-item">
                    <div class="activity-icon">☕</div>
                    <h3>ありがとうカフェ</h3>
                    <p class="activity-description">定期的に開催される交流の場。感謝の気持ちを共有し、新しい仲間と出会える場所です。</p>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">📚</div>
                    <h3>ありがとうセミナー</h3>
                    <p class="activity-description">感謝の心を深め、自己成長につながる学びの機会を提供します。</p>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">🎨</div>
                    <h3>ありがとう体験ワークショップ</h3>
                    <p class="activity-description">実践的なワークを通じて、感謝の心を体感し、日常に活かす方法を学びます。</p>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">🎁</div>
                    <h3>ありがとうグッズの販売</h3>
                    <p class="activity-description">ありがとうカード 2025、野口さんのカレンダー、野口さんの絵本、ありがとう T シャツ、ありがとうシールなど</p>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">👥</div>
                    <h3>Facebook グループ</h3>
                    <p class="activity-description">オンラインで繋がり、日々の感謝や気づきを共有できるコミュニティです。</p>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">✍️</div>
                    <h3>ありがとう ブログ</h3>
                    <p class="activity-description">メンバーの体験談や感謝のエピソードを発信し、感動を分かち合います。</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- 参加方法セクション -->
    <section class="membership-section section">
        <div class="container">
            <div class="section-header">
                <span class="section-marker">◯</span>
                <h2 class="section-title">参加方法</h2>
            </div>
            
            <div class="membership-options">
                <div class="membership-card free">
                    <div class="membership-header">
                        <h3>無料会員</h3>
                    </div>
                    <div class="membership-body">
                        <p>Facebook グループに登録してありがとう倶楽部の活動を知ることができます。またスポットで料金を払うことでありがとうカフェや セミナーに参加することもできます。</p>
                        <ul class="membership-benefits">
                            <li>Facebookグループへの参加</li>
                            <li>活動情報の受け取り</li>
                            <li>イベントへのスポット参加（有料）</li>
                        </ul>
                    </div>
                </div>
                
                <div class="membership-card premium">
                    <div class="membership-header">
                        <h3>有料会員</h3>
                        <p class="membership-price">月額1,000円　年額10,000円</p>
                        <p class="membership-note">（年金生活者、学生は無料）</p>
                    </div>
                    <div class="membership-body">
                        <p>各地で開催される ありがとうカフェに、どこでも、何度でも参加することが可能です。またセミナーやグッズは 会員価格で参加したり購入したりすることが可能です。</p>
                        <ul class="membership-benefits">
                            <li>ありがとうカフェ参加し放題</li>
                            <li>セミナー会員価格</li>
                            <li>グッズ会員価格</li>
                            <li>特別イベントへの優先案内</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="membership-notice">
                <span class="note-marker">※</span>
                <p>会員の皆さんに対して、ご自身のご商売やご活動を積極的にご案内いただきます。しかしながら、会員の皆様に対して不誠実な対応や、嘘偽りの内容があってトラブルに発展したような場合、情報を共有させていただき、場合によってはご退会いただくこともございます。お互い様の精神で、氣持ちの良い関係性を保てる倶楽部にしてまいりましょう。</p>
            </div>
            
            <div class="membership-cta">
                <h3>会員登録をご希望の方へ</h3>
                <p>下記のお問い合わせフォームより、会員登録希望の旨をお知らせください。</p>
                <a href="<?php echo home_url('/contact/'); ?>" class="btn btn-primary">お問い合わせはこちら</a>
            </div>
        </div>
    </section>
    
    <!-- 企業の皆様へセクション -->
    <section class="corporate-section section">
        <div class="container">
            <div class="section-header">
                <span class="section-marker">◯</span>
                <h2 class="section-title">企業の皆様へ</h2>
            </div>
            
            <div class="corporate-content">
                <p class="corporate-intro">ありがとう を企業理念として大切にされている企業の皆様にお願いがあります。</p>
                
                <p class="corporate-appeal">是非ありがとう倶楽部と連携していただきたく存じます。</p>
                
                <p>ありがとう倶楽部に協賛いただくことで広告として、当ホームページをご活用していただけないでしょうか。</p>
                
                <p>このホームページに皆様の考え方あり方やり方を動画に撮って掲載することで、ありがとうを大事に活動をされている会員の皆様へ 訴求することができます。</p>
                
                <h3>協賛のメリット</h3>
                <ul class="corporate-benefits">
                    <li>感謝の心を大切にする会員へのダイレクトなアプローチ</li>
                    <li>企業イメージの向上</li>
                    <li>CSR活動としての実績</li>
                    <li>イベントでの企業PR機会</li>
                </ul>
                
                <p class="corporate-closing">是非ご一緒に、ありがとうを大切にする 住みよい大和（だいわ）の社会を作っていきましょう。</p>
                
                <div class="corporate-cta">
                    <a href="<?php echo home_url('/contact/'); ?>" class="btn btn-primary">協賛のお問い合わせ</a>
                </div>
            </div>
        </div>
    </section>
    
</main>

<?php get_footer(); ?>