<?php
/**
 * CONFIGURAÇÕES DO MC SHOP USA
 *
 * IMPORTANTE:
 * 1) NÃO deixe sua sk_live escrita direto no código público.
 * 2) Como sua chave foi colada na conversa/código, ROTACIONE ela na Stripe.
 * 3) Coloque a nova chave em variável de ambiente STRIPE_SECRET_KEY no servidor.
 */

define('STRIPE_SECRET_KEY', getenv('STRIPE_SECRET_KEY') ?: 'STRIPE_SECRET_KEY');

define('ADMIN_USER', 'caetano');
define('ADMIN_PASS', 'caetano@MCSHOP');

define('ORDERS_DIR', __DIR__ . '/orders');

if (!is_dir(ORDERS_DIR)) {
    mkdir(ORDERS_DIR, 0755, true);
}

function base_url(): string
{
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? null) == 443);
    $protocol = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    return $protocol . '://' . $host . ($dir === '' ? '' : $dir);
}

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function order_file_path(string $orderNumber): string
{
    $safe = preg_replace('/[^A-Z0-9\-_]/i', '', $orderNumber);
    return ORDERS_DIR . '/' . $safe . '.json';
}

function save_order(array $order): void
{
    file_put_contents(
        order_file_path($order['order_number']),
        json_encode($order, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        LOCK_EX
    );
}

function load_order(string $orderNumber): ?array
{
    $path = order_file_path($orderNumber);
    if (!is_file($path)) {
        return null;
    }

    $data = json_decode(file_get_contents($path), true);
    return is_array($data) ? $data : null;
}

function load_orders(): array
{
    $orders = [];

    foreach (glob(ORDERS_DIR . '/*.json') as $file) {
        $data = json_decode(file_get_contents($file), true);
        if (is_array($data)) {
            $orders[] = $data;
        }
    }

    usort($orders, function ($a, $b) {
        return strcmp($b['created_at'] ?? '', $a['created_at'] ?? '');
    });

    return $orders;
}
