<?php
require __DIR__ . '/config.php';

$orderNumber = $_GET['order'] ?? '';
$order = $orderNumber ? load_order($orderNumber) : null;

if ($order) {
    $order['status'] = 'cancelled_before_payment';
    save_order($order);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pagamento cancelado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white min-h-screen flex items-center justify-center p-6">
    <div class="max-w-xl w-full bg-white/5 border border-white/10 rounded-3xl p-8 text-center">
        <h1 class="text-3xl font-black uppercase mb-4">Pagamento cancelado</h1>
        <p class="text-white/70 mb-6">Você pode voltar para a loja e tentar novamente.</p>
        <a href="index.html" class="inline-block bg-blue-600 hover:bg-blue-500 rounded-2xl px-6 py-3 font-black uppercase">Voltar</a>
    </div>
</body>
</html>
