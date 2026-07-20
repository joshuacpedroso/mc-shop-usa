<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

$sessionId = $_GET['session_id'] ?? '';
$order = null;
$message = 'Estamos confirmando seu pagamento.';

if ($sessionId !== '') {
    try {
        $session = \Stripe\Checkout\Session::retrieve($sessionId);
        $orderNumber = $session->client_reference_id ?: ($session->metadata->order_number ?? '');

        if ($orderNumber) {
            $order = load_order($orderNumber);

            if ($order) {
                $order['stripe_session_id'] = $session->id;
                $order['payment_status'] = $session->payment_status;

                if ($session->payment_status === 'paid') {
                    $order['status'] = 'paid';
                    $order['paid_at'] = $order['paid_at'] ?: date('c');
                    $message = 'Pagamento aprovado! Seu pedido foi criado.';
                } else {
                    $order['status'] = 'awaiting_payment_confirmation';
                    $message = 'Pedido recebido. Pagamento ainda não confirmado.';
                }

                save_order($order);
            }
        }
    } catch (\Throwable $e) {
        $message = 'Não foi possível consultar a Stripe agora. Se o pagamento foi aprovado, a ordem será atualizada depois.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obrigado - MC SHOP USA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white min-h-screen flex items-center justify-center p-6">
    <div class="max-w-xl w-full bg-white/5 border border-white/10 rounded-3xl p-8 text-center">
        <h1 class="text-4xl font-black uppercase mb-4">Obrigado!</h1>
        <p class="text-white/70 mb-6"><?= e($message) ?></p>

        <?php if ($order): ?>
            <div class="text-left bg-black/40 rounded-2xl p-5 mb-6">
                <p><strong>Pedido:</strong> <?= e($order['order_number']) ?></p>
                <p><strong>Cliente:</strong> <?= e($order['customer']['name'] ?? '') ?></p>
                <p><strong>Total:</strong> $<?= number_format((float)$order['total'], 2) ?></p>
                <p><strong>Status:</strong> <?= e($order['status']) ?></p>
            </div>
        <?php endif; ?>

        <a href="index.html" class="inline-block bg-blue-600 hover:bg-blue-500 rounded-2xl px-6 py-3 font-black uppercase">
            Voltar para loja
        </a>
    </div>
</body>
</html>
