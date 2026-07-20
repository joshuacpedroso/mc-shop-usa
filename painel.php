<?php
session_start();
require __DIR__ . '/config.php';

$error = '';

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: painel.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';

    if ($user === ADMIN_USER && $pass === ADMIN_PASS) {
        $_SESSION['admin_logged'] = true;
        header('Location: painel.php');
        exit;
    }

    $error = 'Login inválido.';
}

if (empty($_SESSION['admin_logged'])):
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel - MC SHOP USA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white min-h-screen flex items-center justify-center p-6">
    <form method="POST" class="w-full max-w-sm bg-white/5 border border-white/10 rounded-3xl p-8">
        <h1 class="text-3xl font-black uppercase mb-6 text-center">Painel</h1>

        <?php if ($error): ?>
            <div class="bg-red-500/20 border border-red-500/30 rounded-xl p-3 mb-4"><?= e($error) ?></div>
        <?php endif; ?>

        <label class="block text-sm mb-2 text-white/70">Usuário</label>
        <input name="user" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 mb-4">

        <label class="block text-sm mb-2 text-white/70">Senha</label>
        <input name="pass" type="password" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 mb-6">

        <button class="w-full bg-blue-600 hover:bg-blue-500 rounded-2xl py-3 font-black uppercase">Entrar</button>
    </form>
</body>
</html>
<?php
exit;
endif;

$orders = load_orders();
$dateFrom = $_GET['from'] ?? '';
$dateTo = $_GET['to'] ?? '';

$filtered = array_filter($orders, function ($order) use ($dateFrom, $dateTo) {
    $status = strtolower((string)($order['status'] ?? ''));
    $paymentStatus = strtolower((string)($order['payment_status'] ?? ''));

    if ($status !== 'paid' && $paymentStatus !== 'paid') {
        return false;
    }

    $created = substr($order['created_at'] ?? '', 0, 10);

    if ($dateFrom && $created < $dateFrom) return false;
    if ($dateTo && $created > $dateTo) return false;

    return true;
});

function resumo_itens(array $order): string
{
    $parts = [];

    foreach (($order['items'] ?? []) as $item) {
        $qty = (int)($item['qty'] ?? 0);
        $name = $item['name'] ?? 'Produto';
        $size = $item['size'] ?? '';
        $cat = $item['cat'] ?? ($item['category'] ?? '');

        if ($cat === 'shirt') $cat = 'Camisa';
        if ($cat === 'hat' || $cat === 'cap') $cat = 'Boné';

        $txt = $qty . 'x ' . $name;
        if ($cat !== '') $txt .= ' (' . $cat . ')';
        if ($size !== '') $txt .= ' Tam: ' . $size;

        $parts[] = $txt;
    }

    return implode(' | ', $parts);
}

function build_query(array $extra = []): string
{
    return http_build_query(array_merge($_GET, $extra));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel de Pedidos - MC SHOP USA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white min-h-screen p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-wrap justify-between gap-4 items-center mb-8">
            <div>
                <h1 class="text-3xl font-black uppercase">Pedidos Pagos</h1>
                <p class="text-white/50 text-sm mt-1">Pendentes, cancelados e erros ficam ocultos.</p>
            </div>

            <a href="?logout=1" class="text-red-400 border-b border-red-400">Sair</a>
            <a href="estoque.php" class="bg-green-600 hover:bg-green-500 rounded-xl px-4 py-2 font-bold">Ver Estoque</a>
            <a href="etiqueta.php" class="bg-blue-600 hover:bg-blue-500 rounded-xl px-4 py-2 font-bold">Emitir Etiqueta</a>
        </div>

        <form method="GET" class="bg-white/5 border border-white/10 rounded-3xl p-5 mb-6 flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm text-white/60 mb-1">De</label>
                <input type="date" name="from" value="<?= e($dateFrom) ?>" class="bg-black border border-white/10 rounded-xl px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-white/60 mb-1">Até</label>
                <input type="date" name="to" value="<?= e($dateTo) ?>" class="bg-black border border-white/10 rounded-xl px-3 py-2">
            </div>

            <button class="bg-blue-600 rounded-xl px-5 py-2 font-bold">Filtrar</button>
            <a href="painel.php" class="bg-white/10 rounded-xl px-5 py-2 font-bold">Limpar</a>
        </form>

        <div class="overflow-x-auto bg-white/5 border border-white/10 rounded-3xl">
            <table class="w-full text-left">
                <thead class="bg-white/10">
                    <tr>
                        <th class="p-4">Data</th>
                        <th class="p-4">Pedido</th>
                        <th class="p-4">Cliente</th>
                        <th class="p-4">Resumo do pedido</th>
                        <th class="p-4">Total</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$filtered): ?>
                        <tr><td colspan="7" class="p-6 text-center text-white/60">Nenhum pedido pago encontrado.</td></tr>
                    <?php endif; ?>

                    <?php foreach ($filtered as $order): ?>
                        <tr class="border-t border-white/10 align-top">
                            <td class="p-4"><?= e(date('d/m/Y H:i', strtotime($order['created_at'] ?? 'now'))) ?></td>
                            <td class="p-4 font-bold"><?= e($order['order_number']) ?></td>
                            <td class="p-4">
                                <?= e($order['customer']['name'] ?? '') ?><br>
                                <span class="text-white/50 text-sm"><?= e($order['customer']['email'] ?? '') ?></span>
                            </td>
                            <td class="p-4 max-w-md text-sm text-white/80"><?= e(resumo_itens($order)) ?></td>
                            <td class="p-4">$<?= number_format((float)($order['total'] ?? 0), 2) ?></td>
                            <td class="p-4 text-green-400 font-bold"><?= e($order['status'] ?? '') ?></td>
                            <td class="p-4 whitespace-nowrap">
                                <a class="inline-block bg-purple-600 hover:bg-purple-500 text-white rounded-xl px-3 py-2 font-bold mb-2" href="order-details.php?order=<?= urlencode($order['order_number']) ?>">Ver detalhes</a>
                                <br>
                                <a class="text-blue-400 font-bold mr-4" target="_blank" href="print-order.php?order=<?= urlencode($order['order_number']) ?>">Imprimir A4</a>
                                <a class="text-green-400 font-bold mr-4" target="_blank" href="print-label.php?order=<?= urlencode($order['order_number']) ?>">Etiqueta</a>
                                <a class="text-yellow-400 font-bold" href="export-pirateship.php?order=<?= urlencode($order['order_number']) ?>">Pirate</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-white/40 text-sm">
            Total de pedidos pagos listados: <?= count($filtered) ?>
        </div>
    </div>
</body>
</html>
