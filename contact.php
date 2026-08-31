<?php
declare(strict_types=1);

/**
 * 沖縄の広報相談室 - 問い合わせフォーム
 * PHP 7.4+ / PHP 8.x 推奨
 *
 * mail() が利用できる一般的なレンタルサーバーを想定。
 * 迷惑メール対策・到達率を重視する場合は、サーバー会社のSMTP設定に合わせて
 * PHPMailer等へ切り替えてください。
 */

$config = require __DIR__ . '/config.php';

function h(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function clean_header(string $value): string {
    return str_replace(["\r", "\n"], '', trim($value));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// Honeypot
if (!empty($_POST['website'] ?? '')) {
    http_response_code(400);
    exit('Bad Request');
}

// Basic rate limit per session
session_start();
$now = time();
$last = (int)($_SESSION['last_submit'] ?? 0);
if ($last > 0 && ($now - $last) < (int)$config['cooldown_seconds']) {
    show_result('少し時間をおいて、もう一度お試しください。', false);
    exit;
}

$name = trim((string)($_POST['name'] ?? ''));
$company = trim((string)($_POST['company'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$phone = trim((string)($_POST['phone'] ?? ''));
$support = trim((string)($_POST['support'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

$errors = [];

if ($name === '' || mb_strlen($name) > 100) $errors[] = 'お名前を入力してください。';
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 254) $errors[] = '正しいメールアドレスを入力してください。';
if ($message === '' || mb_strlen($message) > 5000) $errors[] = '相談内容を入力してください。';

if ($errors) {
    show_result(implode('<br>', array_map('h', $errors)), false);
    exit;
}

$name = clean_header($name);
$company = clean_header($company);
$email = clean_header($email);
$phone = clean_header($phone);
$support = clean_header($support);

$subject = '【沖縄の広報相談室】お問い合わせ';
$body = <<<MAIL
「沖縄の広報相談室」からお問い合わせがありました。

■ お名前
{$name}

■ 会社名・屋号
{$company}

■ メールアドレス
{$email}

■ 電話番号
{$phone}

■ 希望する支援内容
{$support}

■ 現在のお悩み・相談内容
{$message}

---
送信元：沖縄の広報相談室
MAIL;

$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'From: ' . clean_header($config['from_email']);
$headers[] = 'Reply-To: ' . $email;

$sent = mb_send_mail(
    $config['to_email'],
    $subject,
    $body,
    implode("\r\n", $headers)
);

if (!$sent) {
    show_result('送信に失敗しました。お手数ですが、時間をおいて再度お試しください。', false);
    exit;
}

$_SESSION['last_submit'] = $now;
show_result('お問い合わせを受け付けました。ありがとうございます。内容を確認のうえ、ご連絡します。', true);

function show_result(string $message, bool $success): void {
    $title = $success ? 'お問い合わせありがとうございます' : '送信できませんでした';
    $accent = $success ? '#0795a2' : '#d85b50';
    ?>
    <!doctype html>
    <html lang="ja">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?= h($title) ?>｜沖縄の広報相談室</title>
      <meta name="robots" content="noindex,nofollow">
      <style>
        body{margin:0;background:#fffdf8;color:#2f3d3f;font-family:system-ui,-apple-system,"Yu Gothic",Meiryo,sans-serif;line-height:1.8}
        .wrap{min-height:100vh;display:grid;place-items:center;padding:24px}
        .card{max-width:620px;width:100%;background:#fff;border:1px solid #dfe7e3;border-radius:24px;padding:42px;box-shadow:0 18px 50px rgba(39,66,68,.10);text-align:center}
        .mark{width:62px;height:62px;border-radius:50%;display:grid;place-items:center;margin:0 auto 18px;background:<?= h($accent) ?>;color:#fff;font-size:28px;font-weight:900}
        h1{font-size:28px;line-height:1.4}
        p{color:#687779}
        a{display:inline-flex;margin-top:18px;padding:12px 22px;border-radius:999px;background:#0795a2;color:#fff;text-decoration:none;font-weight:800}
      </style>
    </head>
    <body>
      <main class="wrap">
        <section class="card">
          <div class="mark"><?= $success ? '✓' : '!' ?></div>
          <h1><?= h($title) ?></h1>
          <p><?= $message ?></p>
          <a href="index.html">トップページへ戻る</a>
        </section>
      </main>
    </body>
    </html>
    <?php
}
