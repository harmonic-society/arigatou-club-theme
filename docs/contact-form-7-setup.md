# Contact Form 7 設定手順書

## 1. プラグインのインストール

1. WordPress管理画面にログイン
2. **プラグイン** → **新規追加** をクリック
3. 検索ボックスに「Contact Form 7」と入力
4. **Contact Form 7**（作者: Takayuki Miyoshi）を見つけて **今すぐインストール** をクリック
5. インストール完了後、**有効化** をクリック

## 2. 日本語化（必要に応じて）

Contact Form 7は自動的に日本語で表示されますが、もし英語表示の場合：
1. **設定** → **一般** で「サイトの言語」を「日本語」に設定
2. Contact Form 7が自動的に日本語化されます

## 3. お問い合わせフォームの作成

### 3.1 新規フォームの作成

1. 管理画面メニューの **お問い合わせ** → **新規追加** をクリック
2. フォーム名を「ありがとう倶楽部 お問い合わせフォーム」に設定

### 3.2 フォームテンプレートの設定

以下のコードを「フォーム」タブに貼り付けてください：

```html
<div class="cf7-arigatou-form">
  <div class="form-group">
    <label for="contact-type">お問い合わせ種別 <span class="required">*</span></label>
    [select* contact-type id:contact-type class:form-control "選択してください" "会員登録について" "イベント参加について" "協賛について" "その他"]
  </div>

  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="your-name">お名前 <span class="required">*</span></label>
      [text* your-name id:your-name class:form-control placeholder "山田 太郎"]
    </div>

    <div class="form-group col-md-6">
      <label for="your-kana">フリガナ <span class="required">*</span></label>
      [text* your-kana id:your-kana class:form-control placeholder "ヤマダ タロウ"]
    </div>
  </div>

  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="your-email">メールアドレス <span class="required">*</span></label>
      [email* your-email id:your-email class:form-control placeholder "example@email.com"]
    </div>

    <div class="form-group col-md-6">
      <label for="your-phone">電話番号</label>
      [tel your-phone id:your-phone class:form-control placeholder "090-1234-5678"]
    </div>
  </div>

  <div class="form-group">
    <label for="your-subject">件名 <span class="required">*</span></label>
    [text* your-subject id:your-subject class:form-control placeholder "お問い合わせの件名を入力してください"]
  </div>

  <div class="form-group">
    <label for="your-message">お問い合わせ内容 <span class="required">*</span></label>
    [textarea* your-message id:your-message class:form-control rows:8 placeholder "お問い合わせ内容を詳しくご記入ください"]
  </div>

  <div class="form-group">
    <div class="privacy-agreement">
      [checkbox* agreement class:form-check-input "個人情報の取り扱いについて同意します"]
    </div>
    <div class="privacy-notice">
      <p>※ お預かりした個人情報は、お問い合わせへの回答およびありがとう倶楽部の活動に関するご案内にのみ使用いたします。</p>
    </div>
  </div>

  <div class="form-submit">
    [submit class:btn-submit "送信する"]
  </div>
</div>
```

### 3.3 メール設定

「メール」タブで以下を設定：

#### 送信先
```
[_site_admin_email]
```

#### 送信元
```
ありがとう倶楽部 <wordpress@yourdomain.com>
```
※ yourdomain.com は実際のドメインに置き換えてください

#### 題名
```
【お問い合わせ】[contact-type] - [your-subject]
```

#### 追加ヘッダー
```
Reply-To: [your-name] <[your-email]>
```

#### メッセージ本文
```
ありがとう倶楽部ウェブサイトからお問い合わせがありました。

━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ お問い合わせ詳細
━━━━━━━━━━━━━━━━━━━━━━━━━━━

【お問い合わせ種別】
[contact-type]

【お名前】
[your-name]（[your-kana]）

【メールアドレス】
[your-email]

【電話番号】
[your-phone]

【件名】
[your-subject]

【お問い合わせ内容】
[your-message]

━━━━━━━━━━━━━━━━━━━━━━━━━━━
送信日時: [_date] [_time]
送信元IP: [_remote_ip]
━━━━━━━━━━━━━━━━━━━━━━━━━━━

このメールは [_site_title] ([_site_url]) のお問い合わせフォームから送信されました。
```

### 3.4 自動返信メールの設定

「メール(2)」にチェックを入れて自動返信を有効化：

#### 送信先
```
[your-email]
```

#### 送信元
```
ありがとう倶楽部 <noreply@yourdomain.com>
```

#### 題名
```
【ありがとう倶楽部】お問い合わせありがとうございます
```

#### メッセージ本文
```
[your-name] 様

この度は、ありがとう倶楽部へお問い合わせいただき、
誠にありがとうございます。

以下の内容でお問い合わせを受け付けました。

━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ お問い合わせ内容
━━━━━━━━━━━━━━━━━━━━━━━━━━━

【お問い合わせ種別】
[contact-type]

【件名】
[your-subject]

【お問い合わせ内容】
[your-message]

━━━━━━━━━━━━━━━━━━━━━━━━━━━

内容を確認の上、担当者よりご連絡させていただきます。
今しばらくお待ちください。

なお、お問い合わせの内容により、お返事にお時間を
いただく場合がございます。あらかじめご了承ください。

────────────────────────────
ありがとう倶楽部
[_site_url]
────────────────────────────

※ このメールは自動送信されています。
※ このメールアドレスは送信専用です。
```

### 3.5 メッセージ設定

「メッセージ」タブで以下のメッセージをカスタマイズ：

```
送信完了: お問い合わせありがとうございます。内容を確認の上、ご連絡させていただきます。
送信失敗: 送信に失敗しました。お手数ですが、しばらくしてから再度お試しください。
入力エラー: 入力内容に誤りがあります。赤く表示された項目をご確認ください。
必須項目エラー: 必須項目が入力されていません。
メールアドレス形式エラー: 正しいメールアドレスを入力してください。
電話番号形式エラー: 正しい電話番号を入力してください。
同意必須: 個人情報の取り扱いへの同意が必要です。
```

## 4. フォームの設置

### 4.1 ショートコードの取得

1. フォーム作成後、フォーム一覧画面に表示されるショートコードをコピー
   例: `[contact-form-7 id="123" title="ありがとう倶楽部 お問い合わせフォーム"]`

### 4.2 お問い合わせページへの設置

1. **固定ページ** → **お問い合わせ** を編集
2. エディタ内の既存のフォームコードを削除
3. コピーしたショートコードを貼り付け
4. 更新をクリック

## 5. スパム対策（推奨）

### 5.1 reCAPTCHA v3の設定

1. [Google reCAPTCHA](https://www.google.com/recaptcha/admin) にアクセス
2. サイトを登録（reCAPTCHA v3を選択）
3. サイトキーとシークレットキーを取得
4. WordPress管理画面の **お問い合わせ** → **インテグレーション**
5. reCAPTCHAの設定にキーを入力して保存

### 5.2 Akismetの設定（オプション）

1. Akismetプラグインをインストール・有効化
2. APIキーを取得・設定
3. Contact Form 7が自動的にAkismetと連携

## 6. テスト送信

1. 実際のお問い合わせページにアクセス
2. すべての項目を入力してテスト送信
3. 管理者メールと自動返信メールが届くことを確認

## 7. トラブルシューティング

### メールが届かない場合

1. **WP Mail SMTP** プラグインのインストールを検討
2. サーバーのメール送信設定を確認
3. 迷惑メールフォルダを確認

### フォームが表示されない場合

1. ショートコードが正しく貼り付けられているか確認
2. プラグインが有効になっているか確認
3. JavaScriptエラーがないか確認（ブラウザの開発者ツール）

## 8. メンテナンス

- 定期的にContact Form 7を最新版にアップデート
- 送信されたメールの内容を確認（必要に応じてFlamingoプラグインでログ保存）
- スパムメールが多い場合は追加の対策を検討

## サポート

設定に関してご不明な点がございましたら、以下をご参照ください：
- [Contact Form 7 公式ドキュメント](https://contactform7.com/ja/)
- [WordPress.org サポートフォーラム](https://wordpress.org/support/plugin/contact-form-7/)