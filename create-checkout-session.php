<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

$cartJson = $_POST['cart_json'] ?? '[]';
$items = json_decode($cartJson, true);

if (!is_array($items) || empty($items)) {
    http_response_code(400);
    exit('Carrinho vazio ou inválido.');
}

$firstName = trim($_POST['first_name'] ?? '');
$lastName  = trim($_POST['last_name'] ?? '');
$email     = trim($_POST['email'] ?? '');
$address   = trim($_POST['address'] ?? '');
$city      = trim($_POST['city'] ?? '');
$zip       = trim($_POST['zip'] ?? '');
$state     = trim($_POST['state'] ?? '');
$phone     = trim($_POST['phone'] ?? '');

if ($firstName === '' || $lastName === '' || $email === '' || $address === '' || $city === '' || $zip === '') {
    http_response_code(400);
    exit('Preencha todos os dados obrigatórios do cliente.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit('E-mail inválido.');
}

$lineItems = [];
$orderItems = [];
$total = 0;

foreach ($items as $item) {
    $id      = $item['id'] ?? null;
    $name    = trim((string)($item['name'] ?? 'Produto'));
    $cat     = trim((string)($item['cat'] ?? ($item['category'] ?? '')));
    $price   = isset($item['price']) ? (float)$item['price'] : 0;
    $qty     = isset($item['qty']) ? (int)$item['qty'] : 0;
    $image   = trim((string)($item['image'] ?? ''));
    $country = trim((string)($item['country'] ?? ''));
    $size    = trim((string)($item['size'] ?? ''));
    $tags    = isset($item['tags']) && is_array($item['tags']) ? $item['tags'] : [];

    if ($price <= 0 || $qty <= 0) {
        continue;
    }

    $productData = ['name' => $name];

    if ($image !== '') {
        $productData['images'] = [$image];
    }

    $descriptionParts = array_filter([
        $cat !== '' ? 'Type: ' . $cat : null,
        $country !== '' ? 'Country: ' . $country : null,
        $size !== '' ? 'Size: ' . $size : null,
    ]);

    if ($descriptionParts) {
        $productData['description'] = implode(' | ', $descriptionParts);
    }

    $lineItems[] = [
        'price_data' => [
            'currency' => 'usd',
            'product_data' => $productData,
            'unit_amount' => (int) round($price * 100),
        ],
        'quantity' => $qty,
    ];

    $subtotal = $price * $qty;
    $total += $subtotal;

    $orderItems[] = [
        'id' => $id,
        'name' => $name,
        'cat' => $cat,
        'price' => $price,
        'qty' => $qty,
        'country' => $country,
        'size' => $size,
        'image' => $image,
        'tags' => $tags,
        'subtotal' => $subtotal,
    ];
}

if (!$lineItems) {
    http_response_code(400);
    exit('Nenhum item válido para pagamento.');
}

$orderNumber = 'MC-' . date('YmdHis') . '-' . random_int(1000, 9999);

$order = [
    'order_number' => $orderNumber,
    'status' => 'pending_payment',
    'payment_status' => 'pending',
    'stripe_session_id' => null,
    'created_at' => date('c'),
    'paid_at' => null,
    'customer' => [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'name' => trim($firstName . ' ' . $lastName),
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'city' => $city,
        'state' => $state,
        'zip' => $zip,
    ],
    'items' => $orderItems,
    'currency' => 'usd',
    'total' => $total,
];

save_order($order);

try {
    $session = \Stripe\Checkout\Session::create([
        'mode' => 'payment',
        'line_items' => $lineItems,
        'success_url' => base_url() . '/success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => base_url() . '/cancel.php?order=' . urlencode($orderNumber),
        'customer_email' => $email,
        'client_reference_id' => $orderNumber,
        'metadata' => [
            'order_number' => $orderNumber,
        ],
    ]);

    $order['stripe_session_id'] = $session->id;
    save_order($order);

    header('Location: ' . $session->url);
    exit;
} catch (\Stripe\Exception\ApiErrorException $e) {
    $order['status'] = 'stripe_error';
    $order['error'] = $e->getMessage();
    save_order($order);

    http_response_code(500);
    echo 'Erro Stripe: ' . e($e->getMessage());
    exit;
} catch (\Throwable $e) {
    $order['status'] = 'internal_error';
    $order['error'] = $e->getMessage();
    save_order($order);

    http_response_code(500);
    echo 'Erro interno: ' . e($e->getMessage());
    exit;
}
