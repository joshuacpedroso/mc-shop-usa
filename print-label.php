<?php
require __DIR__ . '/config.php';

$orderNumber = $_GET['order'] ?? '';
$order = load_order($orderNumber);

if (!$order) {
    http_response_code(404);
    exit('Pedido não encontrado.');
}

$c = $order['customer'] ?? [];

$qrUrl = base_url() . '/orderqr.php?order=' . urlencode($order['order_number']);
$qrImage = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($qrUrl);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Etiqueta <?= e($order['order_number']) ?></title>

    <style>
        @page { size: 100mm 150mm; margin: 3mm; }

        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            color: #111;
            margin: 0;
            padding: 0;
        }

        .btn {
            margin: 8px;
        }

        .label {
            width: 94mm;
            height: 144mm;
            border: 2px solid #111;
            padding: 5mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        .brand {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            border-bottom: 2px solid #111;
            padding-bottom: 6px;
        }

        .from {
            font-size: 11px;
            line-height: 1.3;
            border-bottom: 1px solid #111;
            padding-bottom: 8px;
            margin-top: 6px;
        }

        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 8mm 0;
        }

        .ship {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .address {
            font-size: 15px;
            line-height: 1.3;
        }

        .bottom {
            border-top: 1px solid #111;
            padding-top: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }

        .order {
            font-size: 11px;
            line-height: 1.3;
            flex: 1;
        }

        .qr {
            width: 32mm;
            height: 32mm;
            border: 1px solid #111;
            padding: 2mm;
        }

        .qr img {
            width: 100%;
            height: 100%;
            display: block;
        }

        .qr-text {
            font-size: 8px;
            text-align: center;
            margin-top: 2px;
        }

        @media print {
            .btn {
                display: none;
            }

            body {
                width: 100mm;
                height: 150mm;
            }
        }
    </style>
</head>

<body>
    <button class="btn" onclick="window.print()">Imprimir etiqueta</button>

    <div class="label">
        <div>
            <div class="brand">MC SHOP USA</div>

            <div class="from">
                <strong>FROM:</strong> MC SHOP USA<br>
                Phone: +1 (848) 383-8929<br>
                403 Jefferson ST, Eatontown NJ<br> 
                Zipcode: 07724
            </div>
        </div>

        <div class="main">
            <div class="ship">SHIP TO:</div>

            <div class="name"><?= e($c['name'] ?? '') ?></div>

            <div class="address">
                <?= e($c['address'] ?? '') ?><br>
                <?= e($c['city'] ?? '') ?> <?= e($c['state'] ?? '') ?><br>
                ZIP: <?= e($c['zip'] ?? '') ?><br>

                <?php if (!empty($c['phone'])): ?>
                    Phone: <?= e($c['phone']) ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="bottom">
            <div class="order">
                <strong>Order:</strong><br>
                <?= e($order['order_number']) ?><br>
                <strong>Date:</strong>
                <?= e(date('d/m/Y', strtotime($order['created_at'] ?? 'now'))) ?><br>
                <strong>Scan:</strong> details
            </div>

            <div>
                <div class="qr">
                    <img src="<?= e($qrImage) ?>" alt="QR Code do pedido">
                </div>
                <div class="qr-text">ORDER QR</div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            setTimeout(() => window.print(), 500);
        });
    </script>
</body>
</html>