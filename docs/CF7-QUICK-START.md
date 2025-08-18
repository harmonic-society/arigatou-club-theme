# Contact Form 7 クイックスタートガイド

## 🚀 3分で設定完了！

### 1️⃣ プラグインインストール（30秒）
```
管理画面 → プラグイン → 新規追加 → 「Contact Form 7」検索 → インストール → 有効化
```

### 2️⃣ フォーム作成（1分）
```
お問い合わせ → 新規追加 → 以下のコードをコピペ
```

```html
<div class="cf7-arigatou-form">
  <div class="form-group">
    <label>お問い合わせ種別 *</label>
    [select* contact-type "選択してください" "会員登録について" "イベント参加について" "協賛について" "その他"]
  </div>

  <div class="form-row">
    <div class="form-group col-md-6">
      <label>お名前 *</label>
      [text* your-name]
    </div>
    <div class="form-group col-md-6">
      <label>メール *</label>
      [email* your-email]
    </div>
  </div>

  <div class="form-group">
    <label>件名 *</label>
    [text* your-subject]
  </div>

  <div class="form-group">
    <label>内容 *</label>
    [textarea* your-message rows:6]
  </div>

  <div class="form-group">
    [checkbox* agreement "個人情報の取り扱いに同意します"]
  </div>

  [submit "送信する"]
</div>
```

### 3️⃣ ページに設置（30秒）
```
固定ページ → お問い合わせ → テンプレート「お問い合わせ (CF7版)」選択 → ショートコード貼付け → 更新
```

## ✅ 完了！

---

## 📋 チェックリスト

- [ ] Contact Form 7 インストール済み
- [ ] フォーム作成済み
- [ ] メール設定完了
- [ ] ページにショートコード設置
- [ ] テスト送信成功

## 🎨 デザインについて

スタイルは自動適用されます！
- 和風の温かみのあるデザイン
- レスポンシブ対応
- アニメーション付き

## 📧 メール設定

### 管理者向けメール
- **送信先**: `[_site_admin_email]`
- **題名**: `【お問い合わせ】[contact-type] - [your-subject]`

### 自動返信メール
- メール(2)を有効化
- **送信先**: `[your-email]`
- **題名**: `【ありがとう倶楽部】お問い合わせありがとうございます`

## 🛡️ スパム対策（推奨）

### reCAPTCHA設定
1. [Google reCAPTCHA](https://www.google.com/recaptcha/admin)でキー取得
2. お問い合わせ → インテグレーション → キー入力

## 🆘 困ったときは

- スタイルが反映されない → キャッシュクリア
- メールが届かない → WP Mail SMTPプラグイン導入
- エラーが出る → JavaScriptコンソール確認

詳細は `/docs/contact-form-7-setup.md` を参照