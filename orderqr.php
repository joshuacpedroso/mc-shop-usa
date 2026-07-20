<?php
require __DIR__ . '/config.php';

$orderNumber = $_GET['order'] ?? '';
$order = load_order($orderNumber);

if (!$order) {
    http_response_code(404);
    exit('Pedido não encontrado.');
}

$c = $order['customer'] ?? [];
$items = $order['items'] ?? [];

function qr_type_label(string $cat): string
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido <?= e($order['order_number']) ?> - MC SHOP USA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white min-h-screen p-4">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white/5 border border-white/10 rounded-3xl p-5 mb-5">
            <div class="flex flex-wrap justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black uppercase">MC SHOP USA</h1>
                    <p class="text-white/60">Detalhes do pedido</p>
                </div>

                <div class="text-left md:text-right">
                    <p class="font-black text-blue-400"><?= e($order['order_number']) ?></p>
                    <p class="text-white/60 text-sm"><?= e(date('d/m/Y H:i', strtotime($order['created_at'] ?? 'now'))) ?></p>
                    <p class="text-white/60 text-sm">Status: <?= e($order['status'] ?? '') ?></p>
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-5 mb-5">
            <div class="bg-white/5 border border-white/10 rounded-3xl p-5">
                <h2 class="text-lg font-black uppercase mb-3">Cliente</h2>
                <p><strong>Nome:</strong> <?= e($c['name'] ?? '') ?></p>
                <p><strong>Email:</strong> <?= e($c['email'] ?? '') ?></p>
                <?php if (!empty($c['phone'])): ?>
                    <p><strong>Telefone:</strong> <?= e($c['phone']) ?></p>
                <?php endif; ?>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-3xl p-5">
                <h2 class="text-lg font-black uppercase mb-3">Entrega</h2>
                <p><?= e($c['address'] ?? '') ?></p>
                <p><?= e($c['city'] ?? '') ?> <?= e($c['state'] ?? '') ?></p>
                <p><strong>ZIP:</strong> <?= e($c['zip'] ?? '') ?></p>
            </div>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-3xl p-5">
            <h2 class="text-xl font-black uppercase mb-4">Itens</h2>

            <?php if (!$items): ?>
                <p class="text-white/60">Nenhum item encontrado.</p>
            <?php endif; ?>

            <div class="space-y-4">
                <?php foreach ($items as $item): ?>
                    <?php
                    $cat = (string)($item['cat'] ?? ($item['category'] ?? ''));
                    $image = trim((string)($item['image'] ?? ''));
                    ?>
                    <div class="bg-black/40 border border-white/10 rounded-2xl p-4 flex gap-4">
                        <div class="w-24 h-24 bg-white/5 rounded-xl overflow-hidden flex items-center justify-center shrink-0">
                            <?php if ($image !== ''): ?>
                                <img src="<?= e($image) ?>" alt="<?= e($item['name'] ?? '') ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-white/40 text-xs">Sem imagem</span>
                            <?php endif; ?>
                        </div>

                        <div class="flex-1">
                            <h3 class="font-black text-lg"><?= e($item['name'] ?? 'Produto') ?></h3>
                            <p class="text-white/60"><?= e($item['country'] ?? '') ?></p>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-3 text-sm">
                                <div class="bg-white/5 rounded-xl p-2">
                                    <span class="text-white/40 block text-xs">Tipo</span>
                                    <?= e(qr_type_label($cat)) ?>
                                </div>

                                <div class="bg-white/5 rounded-xl p-2">
                                    <span class="text-white/40 block text-xs">Tamanho</span>
                                    <?= e($item['size'] ?? 'Único') ?>
                                </div>

                                <div class="bg-white/5 rounded-xl p-2">
                                    <span class="text-white/40 block text-xs">Qtd</span>
                                    <?= (int)($item['qty'] ?? 0) ?>
                                </div>

                                <div class="bg-white/5 rounded-xl p-2">
                                    <span class="text-white/40 block text-xs">Subtotal</span>
                                    $<?= number_format((float)($item['subtotal'] ?? 0), 2) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="border-t border-white/10 mt-5 pt-5 text-right">
                <p class="text-white/50 uppercase text-sm">Total</p>
                <p class="text-3xl font-black text-blue-400">$<?= number_format((float)($order['total'] ?? 0), 2) ?></p>
            </div>
        </div>
    </div>
</body>
</html>
