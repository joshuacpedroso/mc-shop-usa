<?php
session_start();
require __DIR__ . '/config.php';

if (empty($_SESSION['admin_logged'])) {
    header('Location: painel.php');
    exit;
}

$productsFile = __DIR__ . '/products.json';

if (!file_exists($productsFile)) {
    die('Arquivo products.json não encontrado.');
}

$products = json_decode(file_get_contents($productsFile), true);

if (!is_array($products)) {
    die('Erro ao ler products.json.');
}

function is_hat_report(array $product): bool
{
    $cat = strtolower($product['cat'] ?? '');
    $name = strtolower($product['name'] ?? '');

    return $cat === 'hat'
        || $cat === 'cap'
        || str_contains($name, 'boné')
        || str_contains($name, 'bone')
        || str_contains($name, 'cap');
}

function total_stock_report(array $product): int
{
    if (is_hat_report($product)) {
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

$availableProducts = array_filter($products, function ($product) {
    return !empty($product['available']);
});
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Estoque</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111;
            background: #fff;
            padding: 30px;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .date {
            text-align: center;
            margin-bottom: 30px;
            color: #555;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background: #111;
            color: #fff;
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }

        td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
        }

        tr:nth-child(even) {
            background: #f5f5f5;
        }

        .print-btn {
            background: #111;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .summary {
            margin-top: 20px;
            font-weight: bold;
        }

        @media print {
            .print-btn {
                display: none;
            }

            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <button class="print-btn" onclick="window.print()">
        Imprimir / Salvar em PDF
    </button>

    <h1>Relatório de Estoque</h1>

    <div class="date">
        Gerado em <?= date('d/m/Y H:i') ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID do Produto</th>
                <th>Nome do Produto</th>
                <th>Estoque Detalhado</th>
                <th>Total</th>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach ($availableProducts as $product): ?>
                <?php
                    $stock = total_stock_report($product);
        
                    $details = '';
        
                    if (is_hat_report($product)) {
                        $details = 'Quantidade: ' . intval($product['stock'] ?? 0);
                    } elseif (isset($product['sizes']) && is_array($product['sizes'])) {
        
                        $parts = [];
        
                        foreach ($product['sizes'] as $size => $qty) {
                            $parts[] = $size . ': ' . intval($qty);
                        }
        
                        $details = implode(' | ', $parts);
        
                    } else {
                        $details = 'Sem variações';
                    }
                ?>
        
                <tr>
                    <td><?= e($product['id'] ?? '') ?></td>
        
                    <td>
                        <?= e($product['name'] ?? 'Produto') ?>
                    </td>
        
                    <td>
                        <?= e($details) ?>
                    </td>
        
                    <td>
                        <?= $stock ?>
                    </td>
                </tr>
        
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="summary">
        Produtos disponíveis: <?= count($availableProducts) ?> |
        Total em estoque: <?= array_sum(array_map('total_stock_report', $availableProducts)) ?>
    </div>

</body>
</html>