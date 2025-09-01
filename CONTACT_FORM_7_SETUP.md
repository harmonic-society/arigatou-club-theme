# Contact Form 7 サンクスページ設定ガイド

## 概要
お問い合わせフォーム送信後にサンクスページ（/thanks/）へリダイレクトする設定方法です。

## 事前準備

### 1. サンクスページの作成
WordPressの管理画面から新規固定ページを作成します：

1. **管理画面** → **固定ページ** → **新規追加**
2. タイトル: 「お問い合わせ完了」または「Thanks」
3. スラッグ: `thanks`
4. ページテンプレート: 「お問い合わせ完了」を選択
5. 公開

## Contact Form 7の設定方法

### 方法1: functions.phpに追加（推奨）

`functions.php`の最後に以下のコードを追加してください：

```php
/**
 * Contact Form 7 送信後のサンクスページへのリダイレクト
 */
add_action('wp_footer', 'arigatou_cf7_redirect_script');
function arigatou_cf7_redirect_script() {
    // Contact Form 7を使用しているページでのみ実行
    if (!is_page('contact') && !is_page_template('page-contact-cf7.php')) {
        return;
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Contact Form 7の送信完了イベントをリスン
        document.addEventListener('wpcf7mailsent', function(event) {
            // サンクスページへリダイレクト
            window.location.href = '<?php echo home_url('/thanks/'); ?>';
        }, false);
    });
    </script>
    <?php
}
```

### 方法2: 特定のフォームのみリダイレクト

複数のContact Form 7フォームがあり、特定のフォームのみリダイレクトしたい場合：

```php
add_action('wp_footer', 'arigatou_cf7_specific_redirect');
function arigatou_cf7_specific_redirect() {
    if (!is_page('contact')) return;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('wpcf7mailsent', function(event) {
            // フォームIDで判定（Contact Form 7の編集画面でIDを確認）
            if (event.detail.contactFormId === '123') { // ← ここにフォームIDを入力
                window.location.href = '<?php echo home_url('/thanks/'); ?>';
            }
        }, false);
    });
    </script>
    <?php
}
```

### 方法3: Contact Form 7の追加設定（非推奨）

Contact Form 7のフォーム編集画面で設定する方法：

1. **Contact Form 7** → **コンタクトフォーム** からフォームを編集
2. **その他の設定**タブを開く
3. 以下のコードを追加：

```
on_sent_ok: "location = '/thanks/';"
```

**注意**: `on_sent_ok`は将来的に廃止予定のため、方法1または2を推奨します。

## サンクスページのカスタマイズ

### ページ内容の編集

`page-thanks.php`ファイルを編集することで、サンクスページの内容をカスタマイズできます：

- 感謝メッセージの変更
- 関連リンクの追加・削除
- ソーシャルメディアリンクの更新

### スタイルの調整

`assets/css/thanks-page.css`ファイルを編集することで、デザインをカスタマイズできます：

- カラーの変更
- アニメーションの調整
- レイアウトの変更

## トラブルシューティング

### リダイレクトが動作しない場合

1. **JavaScriptエラーの確認**
   - ブラウザの開発者ツール（F12）でコンソールエラーを確認

2. **プラグインの競合**
   - 他のプラグインとの競合がないか確認
   - 一時的に他のプラグインを無効化してテスト

3. **キャッシュのクリア**
   - ブラウザキャッシュをクリア
   - WordPressのキャッシュプラグインがある場合はキャッシュをクリア

### ページが見つからない場合

1. **パーマリンクの更新**
   - 管理画面 → 設定 → パーマリンク → 「変更を保存」をクリック

2. **ページの公開状態を確認**
   - サンクスページが「公開」になっているか確認

## Contact Form 7のその他の設定

### 送信完了メッセージのカスタマイズ

リダイレクトを使用しない場合の送信完了メッセージ：

1. Contact Form 7の編集画面
2. **メッセージ**タブ
3. 「メッセージが正常に送信された」の内容を編集

### 自動返信メールの設定

1. **メール**タブ
2. **メール (2)**にチェック
3. 自動返信の内容を設定

## サポート

問題が解決しない場合は、以下をご確認ください：

- WordPress バージョン: 5.0以上
- Contact Form 7 バージョン: 5.0以上
- PHPバージョン: 7.4以上

---

最終更新日: 2025年9月