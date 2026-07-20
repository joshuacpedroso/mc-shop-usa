<?php
session_start();
require 'vendor/autoload.php'; // Você precisa do SDK da Stripe via Composer

\Stripe\Stripe::setApiKey('sk_test_SUA_CHAVE_AQUI');

$products = json_decode(file_get_contents('products.json'), true);
$line_items = [];

// Mapeia os itens do carrinho para o formato da Stripe
foreach ($_SESSION['cart'] as $id) {
    foreach ($products as $p) {
        if ($p['id'] == $id) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => $p['name'][$_SESSION['lang']]],
                    'unit_amount' => $p['price'] * 100, // Stripe usa centavos
                ],
                'quantity' => 1,
            ];
        }
    }
}

$checkout_session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [$line_items],
    'mode' => 'payment',
    'success_url' => 'http://seusite.com/success.php',
    'cancel_url' => 'http://seusite.com/index.php',
]);

header("Location: " . $checkout_session->url);