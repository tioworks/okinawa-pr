# 沖縄の広報相談室 LP

沖縄県内の中小企業・小規模事業者向け「広報・PR支援サービス」の1ページLPです。

## ファイル構成

- `index.html` — LP本体
- `contact.php` — 問い合わせフォーム送信処理
- `config.php` — 受信メールアドレス等の設定
- `robots.txt` — 検索エンジン向け設定
- `sitemap.xml` — サイトマップ
- `assets/okinawa-pr-logo.png` — 透過ロゴ
- `assets/okinawa-pr-logo.webp` — LP表示用ロゴ
- `assets/profile.webp` — 提供いただいたプロフィール写真をWebP化したもの
- `assets/favicon.png` — favicon

## 公開前に必ず変更する箇所

### 1. `config.php`

以下を実際の値に変更してください。

```php
'to_email' => 'YOUR-EMAIL@example.com',
'from_email' => 'no-reply@YOUR-DOMAIN.example',
```

`from_email` は、できればレンタルサーバー上の自社ドメインメールにしてください。

### 2. `index.html`

以下の文字列を実際の情報に変更してください。

- `YOUR-DOMAIN.example` → 公開するドメイン
- `YOUR NAME` → 運営者名

canonical、OGP、構造化データ内のURLも同じドメインへ変更します。

### 3. `robots.txt` / `sitemap.xml`

`YOUR-DOMAIN.example` を実際のドメインへ変更します。

## レンタルサーバーへのアップロード

PHPが使えるレンタルサーバーで、ファイルをそのまま公開ディレクトリへアップロードしてください。

例：

```text
public_html/
├─ index.html
├─ contact.php
├─ config.php
├─ robots.txt
├─ sitemap.xml
└─ assets/
   ├─ favicon.png
   ├─ okinawa-pr-logo.png
   ├─ okinawa-pr-logo.webp
   └─ profile.webp
```

## フォームについて

`contact.php` は PHP の `mb_send_mail()` を利用しています。

サーバーによっては、PHPのメール送信機能が制限されている場合があります。その場合は、レンタルサーバー会社が案内しているSMTP送信方式に切り替えてください。

## セキュリティ

最低限の対策として、

- POSTのみ受付
- メールアドレス形式チェック
- メールヘッダーインジェクション対策
- Honeypotによる簡易スパム対策
- 同一セッションからの連続送信抑制
- `noindex` の送信完了画面

を実装しています。

本番運用では、必要に応じてreCAPTCHA / Cloudflare Turnstile / SMTP送信等を追加してください。

## デザイン

ロゴの明るい沖縄カラーに合わせ、

- ターコイズ
- コーラル
- イエロー
- オフホワイト

を基調にしています。

沖縄モチーフは、ミンサー柄と花ブロックをCSSパターンとして控えめに配置し、観光サイトになりすぎないようにしています。

## SEO / GEO / LLMO

以下を実装済みです。

- semantic HTML
- H1/H2/H3構造
- title / description
- canonical
- OGP
- robots.txt
- sitemap.xml
- WebSite / Organization / Person / Service / FAQPage のJSON-LD
- サービス定義文
- 対象顧客・対応地域の明記
- FAQのHTML本文
- 重要情報を画像だけに依存しない構造

実際の氏名・法人情報・URLなどに合わせて構造化データを必ず更新してください。
