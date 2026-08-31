<?php
/**
 * 沖縄の広報相談室 - メール設定
 * 
 * レンタルサーバーへアップする前に、ここだけ編集してください。
 */
return [
    // 問い合わせを受信するメールアドレス
    'to_email' => 'YOUR-EMAIL@example.com',

    // 送信元メールアドレス
    // レンタルサーバーの独自ドメインメールを推奨します。
    'from_email' => 'no-reply@YOUR-DOMAIN.example',

    // サイト名
    'site_name' => '沖縄の広報相談室',

    // 問い合わせ送信後に表示するサイト名
    'business_name' => 'TIO WORKS',

    // 簡易スパム対策：同一IPからの送信間隔（秒）
    'cooldown_seconds' => 30,
];
