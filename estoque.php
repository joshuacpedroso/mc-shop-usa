<?php
session_start();
require __DIR__ . '/config.php';

if (empty($_SESSION['admin_logged'])) {
    header('Location: painel.php');
    exit;
}

$productsFile = __DIR__ . '/products.json';

if (!file_exists($productsFile)) {
    die('Arquivo products.json não encontrado na mesma pasta do painel.');
}

$products = json_decode(file_get_contents($productsFile), true);

if (!is_array($products)) {
    die('Erro ao ler products.json. Verifique se o JSON está válido.');
}

$saved = false;
$error = '';

function is_hat(array $product): bool
{
    $cat = strtolower($product['cat'] ?? '');
    $name = strtolower($product['name'] ?? '');

    return $cat === 'hat'
        || $cat === 'cap'
        || str_contains($name, 'boné')
        || str_contains($name, 'bone')
        || str_contains($name, 'cap');
}

function total_stock(array $product): int
{
    if (is_hat($product)) {
        return max(0, intval($product['stock'] ?? 0));
    }

    if (!isset($product['sizes']) || !is_array($product['sizes'])) {
        return !empty($product['available']) ? 1 : 0;
    }

    $total = 0;

    foreach ($product['sizes'] as $qty) {
        $total += (int)$qty;
    }

    return $total;
}

function product_type(array $product): string
{
    $cat = strtolower($product['cat'] ?? '');

    if ($cat === 'shirt') return 'Camisa';
    if ($cat === 'hat' || $cat === 'cap') return 'Boné';

    return $cat ?: 'Produto';
}

function normalize_price($value): float
{
    $value = trim((string)$value);
    $value = str_replace(',', '.', $value);
    return max(0, floatval($value));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        foreach ($_POST['products'] ?? [] as $id => $data) {
            foreach ($products as &$product) {
                if ((string)($product['id'] ?? '') === (string)$id) {
                    $product['available'] = (($data['available'] ?? '0') === '1');

                    $product['price'] = normalize_price(
                        $data['price'] ?? ($product['price'] ?? 0)
                    );

                    if (is_hat($product)) {
                        $product['stock'] = max(0, intval($data['stock'] ?? ($product['stock'] ?? 0)));
                    } elseif (isset($product['sizes']) && isset($data['sizes'])) {
                        foreach ($product['sizes'] as $size => $oldQty) {
                            $newQty = $data['sizes'][$size] ?? $oldQty;
                            $product['sizes'][$size] = max(0, intval($newQty));
                        }
                    }

                    break;
                }
            }
            unset($product);
        }

        $json = json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if ($json === false) {
            throw new Exception('Erro ao gerar JSON.');
        }

        $backupFile = __DIR__ . '/products-backup-' . date('Ymd-His') . '.json';
        copy($productsFile, $backupFile);

        $result = file_put_contents($productsFile, $json, LOCK_EX);

        if ($result === false) {
            throw new Exception('Não foi possível salvar o products.json. Verifique permissão de escrita.');
        }

        $saved = true;
        $products = json_decode(file_get_contents($productsFile), true);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$search = strtolower(trim($_GET['q'] ?? ''));

$filteredProducts = array_filter($products, function ($product) use ($search) {
    if ($search === '') return true;

    $name = strtolower($product['name'] ?? '');
    $country = strtolower($product['country'] ?? '');
    $cat = strtolower($product['cat'] ?? '');

    return str_contains($name, $search)
        || str_contains($country, $search)
        || str_contains($cat, $search);
});
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Estoque - MC SHOP USA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white min-h-screen p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-wrap justify-between gap-4 items-center mb-8">
            <div>
                <h1 class="text-3xl font-black uppercase">Estoque</h1>
                <p class="text-white/50 text-sm mt-1">
                    Atualize quantidades, disponibilidade e preço dos produtos.
                </p>
            </div>

            <div class="flex gap-3 items-center">
                <a href="painel.php" class="bg-white/10 hover:bg-white/20 rounded-xl px-4 py-2 font-bold">
                    Voltar aos pedidos
                </a>
                <a href="relatorio-estoque.php" target="_blank" class="bg-green-600 hover:bg-green-500 rounded-xl px-4 py-2 font-bold">
                    Exportar relatório
                </a>
                <a href="painel.php?logout=1" class="text-red-400 border-b border-red-400">
                    Sair
                </a>
            </div>
        </div>

        <?php if ($saved): ?>
            <div class="bg-green-500/20 border border-green-500/30 rounded-2xl p-4 mb-5 text-green-200 font-bold">
                Alterações salvas com sucesso.
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="bg-red-500/20 border border-red-500/30 rounded-2xl p-4 mb-5 text-red-200 font-bold">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="GET" class="bg-white/5 border border-white/10 rounded-3xl p-5 mb-6 flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[240px]">
                <label class="block text-sm text-white/60 mb-1">Buscar produto/time</label>
                <input
                    type="text"
                    name="q"
                    value="<?= e($_GET['q'] ?? '') ?>"
                    placeholder="Ex: Brazil, Argentina, Cap, Boné..."
                    class="w-full bg-black border border-white/10 rounded-xl px-3 py-2"
                >
            </div>

            <button class="bg-blue-600 hover:bg-blue-500 rounded-xl px-5 py-2 font-bold">
                Buscar
            </button>

            <a href="estoque.php" class="bg-white/10 hover:bg-white/20 rounded-xl px-5 py-2 font-bold">
                Limpar
            </a>
        </form>

        <form method="POST">
            <div class="overflow-x-auto bg-white/5 border border-white/10 rounded-3xl">
                <table class="w-full text-left">
                    <thead class="bg-white/10">
                        <tr>
                            <th class="p-4">Produto</th>
                            <th class="p-4">Tipo</th>
                            <th class="p-4">Total</th>
                            <th class="p-4">Disponível</th>
                            <th class="p-4">Preço</th>
                            <th class="p-4">Estoque</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!$filteredProducts): ?>
                            <tr>
                                <td colspan="6" class="p-6 text-center text-white/60">
                                    Nenhum produto encontrado.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($filteredProducts as $product): ?>
                            <?php
                                $id = $product['id'];
                                $total = total_stock($product);
                                $available = !empty($product['available']);
                                $price = number_format((float)($product['price'] ?? 0), 2, '.', '');
                            ?>

                            <tr class="border-t border-white/10 align-top">
                                <td class="p-4 min-w-[240px]">
                                    <div class="flex gap-3 items-center">
                                        <?php if (!empty($product['image'])): ?>
                                            <img
                                                src="<?= e($product['image']) ?>"
                                                class="w-14 h-14 rounded-xl object-cover bg-white/10"
                                                alt=""
                                            >
                                        <?php endif; ?>

                                        <div>
                                            <div class="font-black"><?= e($product['name'] ?? 'Produto') ?></div>
                                            <div class="text-white/50 text-sm"><?= e($product['country'] ?? '') ?></div>
                                            <div class="text-white/30 text-xs">ID: <?= e($id) ?></div>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-4">
                                    <?= e(product_type($product)) ?>
                                </td>

                                <td class="p-4">
                                    <span class="<?= $total <= 0 ? 'text-red-400' : 'text-green-400' ?> font-black">
                                        <?= $total ?>
                                    </span>
                                </td>

                                <td class="p-4">
                                    <select
                                        name="products[<?= e($id) ?>][available]"
                                        class="bg-black border border-white/10 rounded-xl px-3 py-2"
                                    >
                                        <option value="1" <?= $available ? 'selected' : '' ?>>Sim</option>
                                        <option value="0" <?= !$available ? 'selected' : '' ?>>Não</option>
                                    </select>
                                </td>

                                <td class="p-4">
                                    <label class="block">
                                        <span class="block text-xs text-white/50 mb-1">
                                            US$
                                        </span>
                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            name="products[<?= e($id) ?>][price]"
                                            value="<?= e($price) ?>"
                                            class="w-28 bg-black border border-white/10 rounded-xl px-3 py-2"
                                        >
                                    </label>
                                </td>

                                <td class="p-4">
                                    <?php if (is_hat($product)): ?>
                                        <label class="block">
                                            <span class="block text-xs text-white/50 mb-1">
                                                Quantidade
                                            </span>
                                            <input
                                                type="number"
                                                min="0"
                                                name="products[<?= e($id) ?>][stock]"
                                                value="<?= e($product['stock'] ?? 0) ?>"
                                                class="w-24 bg-black border border-white/10 rounded-xl px-3 py-2"
                                            >
                                        </label>

                                    <?php elseif (isset($product['sizes']) && is_array($product['sizes'])): ?>
                                        <div class="flex flex-wrap gap-3">
                                            <?php foreach ($product['sizes'] as $size => $qty): ?>
                                                <label class="block">
                                                    <span class="block text-xs text-white/50 mb-1">
                                                        <?= e($size) ?>
                                                    </span>
                                                    <input
                                                        type="number"
                                                        min="0"
                                                        name="products[<?= e($id) ?>][sizes][<?= e($size) ?>]"
                                                        value="<?= e($qty) ?>"
                                                        class="w-20 bg-black border border-white/10 rounded-xl px-3 py-2"
                                                    >
                                                </label>
                                            <?php endforeach; ?>
                                        </div>

                                    <?php else: ?>
                                        <span class="text-white/40 text-sm">
                                            Sem controle de quantidade.
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="sticky bottom-4 mt-6 bg-black/90 border border-white/10 rounded-3xl p-4 flex flex-wrap justify-between gap-4 items-center">
                <div class="text-white/50 text-sm">
                    Produtos listados: <?= count($filteredProducts) ?> |
                    Total geral no estoque: <?= array_sum(array_map('total_stock', $products)) ?>
                </div>

                <button class="bg-green-600 hover:bg-green-500 rounded-2xl px-8 py-3 font-black uppercase">
                    Salvar alterações
                </button>
            </div>
        </form>
    </div>
</body>
</html>