<?php
session_start();
require __DIR__ . '/config.php';

if (empty($_SESSION['admin_logged'])) {
    header('Location: painel.php');
    exit;
}

$orderNumber = $_GET['order'] ?? '';
$order = load_order($orderNumber);

if (!$order) {
    http_response_code(404);
    exit('Pedido não encontrado.');
}

$customer = $order['customer'] ?? [];
$items = $order['items'] ?? [];

function product_type_label(string $cat): string
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
    <title>Detalhes do Pedido <?= e($order['order_number']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white min-h-screen p-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-wrap justify-between gap-4 items-center mb-8">
            <div>
                <a href="painel.php" class="text-blue-400 border-b border-blue-400 text-sm">← Voltar ao painel</a>
                <h1 class="text-3xl font-black uppercase mt-3">Detalhes do Pedido</h1>
                <p class="text-white/60"><?= e($order['order_number']) ?></p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a target="_blank" href="print-order.php?order=<?= urlencode($order['order_number']) ?>" class="bg-blue-600 hover:bg-blue-500 rounded-xl px-4 py-3 font-bold">Imprimir A4</a>
                <a target="_blank" href="print-label.php?order=<?= urlencode($order['order_number']) ?>" class="bg-green-600 hover:bg-green-500 rounded-xl px-4 py-3 font-bold">Imprimir etiqueta</a>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white/5 border border-white/10 rounded-3xl p-6">
                <h2 class="text-xl font-black uppercase mb-4">Cliente</h2>
                <p><strong>Nome:</strong> <?= e($customer['name'] ?? '') ?></p>
                <p><strong>Email:</strong> <?= e($customer['email'] ?? '') ?></p>
                <p><strong>Telefone:</strong> <?= e($customer['phone'] ?? '') ?></p>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-3xl p-6">
                <h2 class="text-xl font-black uppercase mb-4">Entrega</h2>
                <p><?= e($customer['address'] ?? '') ?></p>
                <p><?= e($customer['city'] ?? '') ?> <?= e($customer['state'] ?? '') ?></p>
                <p><strong>ZIP:</strong> <?= e($customer['zip'] ?? '') ?></p>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-3xl p-6">
                <h2 class="text-xl font-black uppercase mb-4">Pagamento</h2>
                <p><strong>Status:</strong> <?= e($order['status'] ?? '') ?></p>
                <p><strong>Stripe:</strong> <?= e($order['payment_status'] ?? '') ?></p>
                <p><strong>Criado:</strong> <?= e(date('d/m/Y H:i', strtotime($order['created_at'] ?? 'now'))) ?></p>
                <?php if (!empty($order['paid_at'])): ?>
                    <p><strong>Pago:</strong> <?= e(date('d/m/Y H:i', strtotime($order['paid_at']))) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-3xl p-6">
            <h2 class="text-2xl font-black uppercase mb-6">Itens do Pedido</h2>

            <?php if (!$items): ?>
                <div class="text-white/60">Nenhum item salvo nesse pedido.</div>
            <?php endif; ?>

            <div class="space-y-4">
                <?php foreach ($items as $item): ?>
                    <?php
                        $cat = (string)($item['cat'] ?? ($item['category'] ?? ''));
                        $type = product_type_label($cat);
                        $image = trim((string)($item['image'] ?? ''));
                    ?>
                    <div class="bg-black/40 border border-white/10 rounded-3xl p-4 flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-32 h-32 bg-white/5 rounded-2xl overflow-hidden flex items-center justify-center">
                            <?php if ($image !== ''): ?>
                                <img src="<?= e($image) ?>" alt="<?= e($item['name'] ?? '') ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-white/40 text-sm">Sem imagem</span>
                            <?php endif; ?>
                        </div>

                        <div class="flex-1">
                            <div class="flex flex-wrap justify-between gap-3">
                                <div>
                                    <h3 class="text-xl font-black"><?= e($item['name'] ?? 'Produto') ?></h3>
                                    <p class="text-white/60"><?= e($item['country'] ?? '') ?></p>
                                </div>

                                <div class="text-right">
                                    <p class="text-blue-400 font-black text-xl">$<?= number_format((float)($item['subtotal'] ?? 0), 2) ?></p>
                                    <p class="text-white/50 text-sm">$<?= number_format((float)($item['price'] ?? 0), 2) ?> cada</p>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-4 gap-3 mt-4">
                                <div class="bg-white/5 rounded-2xl p-3">
                                    <div class="text-white/50 text-xs uppercase">Tipo</div>
                                    <div class="font-bold"><?= e($type) ?></div>
                                </div>

                                <div class="bg-white/5 rounded-2xl p-3">
                                    <div class="text-white/50 text-xs uppercase">Tamanho</div>
                                    <div class="font-bold"><?= e($item['size'] ?? 'Único') ?></div>
                                </div>

                                <div class="bg-white/5 rounded-2xl p-3">
                                    <div class="text-white/50 text-xs uppercase">Quantidade</div>
                                    <div class="font-bold"><?= (int)($item['qty'] ?? 0) ?></div>
                                </div>

                                <div class="bg-white/5 rounded-2xl p-3">
                                    <div class="text-white/50 text-xs uppercase">Produto ID</div>
                                    <div class="font-bold"><?= e($item['id'] ?? '-') ?></div>
                                </div>
                            </div>

                            <?php if (!empty($item['tags']) && is_array($item['tags'])): ?>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <?php foreach ($item['tags'] as $tag): ?>
                                        <span class="bg-white/10 rounded-full px-3 py-1 text-xs text-white/70"><?= e($tag) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-6 pt-6 border-t border-white/10 flex justify-end">
                <div class="text-right">
                    <div class="text-white/50 uppercase text-sm">Total do pedido</div>
                    <div class="text-3xl font-black text-blue-400">$<?= number_format((float)($order['total'] ?? 0), 2) ?></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
