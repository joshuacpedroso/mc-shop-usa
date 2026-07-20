<?php
require __DIR__ . '/config.php';

$orderNumber = $_GET['order'] ?? '';
$order = load_order($orderNumber);

if (!$order) {
    http_response_code(404);
    exit('Pedido não encontrado.');
}

$customer = $order['customer'] ?? [];
$items = $order['items'] ?? [];

function type_label_print(string $cat): string
{
    $cat = strtolower(trim($cat));
    if ($cat === 'shirt') return 'Camisa';
    if ($cat === 'hat' || $cat === 'cap') return 'Boné';
    return $cat !== '' ? ucfirst($cat) : 'Produto';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Ordem <?= e($order['order_number']) ?></title>
    <style>
        @page { size: A4; margin: 14mm; }
        body { font-family: Arial, sans-serif; color: #111; }
        h1 { margin: 0 0 8px; font-size: 26px; }
        .top { display: flex; justify-content: space-between; border-bottom: 2px solid #111; padding-bottom: 12px; margin-bottom: 20px; }
        .box { border: 1px solid #ccc; padding: 12px; margin-bottom: 18px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #ddd; padding: 9px; text-align: left; font-size: 14px; vertical-align: top; }
        th { background: #f2f2f2; }
        img { width: 55px; height: 55px; object-fit: cover; border-radius: 8px; }
        .total { text-align: right; font-size: 20px; font-weight: bold; margin-top: 20px; }
        .btn { margin-bottom: 20px; }
        @media print { .btn { display: none; } }
    </style>
</head>
<body>
    <button class="btn" onclick="window.print()">Imprimir</button>

    <div class="top">
        <div>
            <h1>MC SHOP USA</h1>
            <strong>Ordem de Serviço / Pedido</strong>
        </div>
        <div>
            <strong>Pedido:</strong> <?= e($order['order_number']) ?><br>
            <strong>Data:</strong> <?= e(date('d/m/Y H:i', strtotime($order['created_at'] ?? 'now'))) ?><br>
            <strong>Status:</strong> <?= e($order['status'] ?? '') ?>
        </div>
    </div>

    <div class="box">
        <strong>Cliente</strong><br>
        <?= e($customer['name'] ?? '') ?><br>
        <?= e($customer['email'] ?? '') ?><br>
        <?= e($customer['phone'] ?? '') ?><br>
        <?= e($customer['address'] ?? '') ?>, <?= e($customer['city'] ?? '') ?> <?= e($customer['state'] ?? '') ?> <?= e($customer['zip'] ?? '') ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Imagem</th>
                <th>Produto</th>
                <th>Tipo</th>
                <th>País</th>
                <th>Tamanho</th>
                <th>Qtd</th>
                <th>Preço</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <?php if (!empty($item['image'])): ?>
                            <img src="<?= e($item['image']) ?>" alt="">
                        <?php endif; ?>
                    </td>
                    <td><?= e($item['name'] ?? '') ?></td>
                    <td><?= e(type_label_print((string)($item['cat'] ?? ''))) ?></td>
                    <td><?= e($item['country'] ?? '') ?></td>
                    <td><?= e($item['size'] ?? 'Único') ?></td>
                    <td><?= (int)($item['qty'] ?? 0) ?></td>
                    <td>$<?= number_format((float)($item['price'] ?? 0), 2) ?></td>
                    <td>$<?= number_format((float)($item['subtotal'] ?? 0), 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total">Total: $<?= number_format((float)($order['total'] ?? 0), 2) ?></div>

    <script>
        window.addEventListener('load', () => setTimeout(() => window.print(), 300));
    </script>
</body>
</html>
