/**
 * Contact Form 7 - サンクスページへのリダイレクト設定
 * 
 * このコードをfunctions.phpまたは子テーマに追加してください
 */

// 方法1: JavaScriptによるリダイレクト（推奨）
document.addEventListener('DOMContentLoaded', function() {
    // Contact Form 7の送信完了イベントをリスン
    document.addEventListener('wpcf7mailsent', function(event) {
        // サンクスページへリダイレクト
        location = '/thanks/';
    }, false);
});

// 方法2: functions.phpに追加するPHPコード
/*
// Contact Form 7送信後のリダイレクト
add_action('wp_footer', 'cf7_redirect_to_thanks_page');
function cf7_redirect_to_thanks_page() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('wpcf7mailsent', function(event) {
            // フォームIDで特定のフォームのみリダイレクトする場合
            // if (event.detail.contactFormId === '123') { // フォームIDを指定
                window.location.href = '<?php echo home_url('/thanks/'); ?>';
            // }
        }, false);
    });
    </script>
    <?php
}
*/

// 方法3: Contact Form 7の追加設定に記述
/*
Contact Form 7のフォーム編集画面で「その他の設定」タブに以下を追加：

on_sent_ok: "location = '/thanks/';"

注意: on_sent_okは将来的に廃止予定のため、上記のJavaScript方法を推奨
*/