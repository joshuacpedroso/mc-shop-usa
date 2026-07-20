<?php
require __DIR__ . '/config.php';

$name    = trim($_POST['name'] ?? '');
$address = trim($_POST['address'] ?? '');
$city    = trim($_POST['city'] ?? '');
$state   = trim($_POST['state'] ?? '');
$zip     = trim($_POST['zip'] ?? '');
$phone   = trim($_POST['phone'] ?? '');

$generated = $_SERVER['REQUEST_METHOD'] === 'POST';

$qrUrl = 'https://mcshopusa.com';
$qrImage = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($qrUrl);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerar Etiqueta - MC SHOP USA</title>

    <style>
        @page { size: 100mm 150mm; margin: 3mm; }

        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            color: #111;
            margin: 0;
            padding: 20px;
            background: #f3f3f3;
        }

        .form-box {
            max-width: 600px;
            margin: 0 auto 30px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 14px;
            padding: 20px;
        }

        .form-box h1 {
            margin-top: 0;
            font-size: 24px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-top: 12px;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 5px;
            font-size: 15px;
        }

        .btn {
            margin-top: 18px;
            background: #111;
            color: #fff;
            border: none;
            padding: 12px 18px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        .print-btn {
            margin-bottom: 10px;
            background: #111;
            color: #fff;
            border: none;
            padding: 12px 18px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
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
            background: #fff;
            margin: 0 auto;
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
            body {
                width: 100mm;
                height: 150mm;
                padding: 0;
                background: #fff;
            }

            .form-box,
            .print-btn {
                display: none;
            }

            .label {
                margin: 0;
            }
        }
    </style>
</head>

<body>

<?php if (!$generated): ?>

    <div class="form-box">
        <h1>Gerar Etiqueta</h1>

        <form method="POST">
            <label>Nome do cliente</label>
            <input type="text" name="name" required placeholder="Ex: Leonardo DeBorba">

            <label>Endereço</label>
            <input type="text" name="address" required placeholder="Ex: 1090 Broadway Suite 104">

            <label>Cidade</label>
            <input type="text" name="city" required placeholder="Ex: W Long Branch">

            <label>Estado</label>
            <input type="text" name="state" required placeholder="Ex: NJ">

            <label>ZIP Code</label>
            <input type="text" name="zip" required placeholder="Ex: 07764">

            <label>Telefone</label>
            <input type="text" name="phone" placeholder="Ex: +17328650904">

            <button class="btn" type="submit">
                Gerar Etiqueta
            </button>
        </form>
    </div>

<?php else: ?>

    <button class="print-btn" onclick="window.print()">Imprimir etiqueta</button>

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

            <div class="name"><?= e($name) ?></div>

            <div class="address">
                <?= e($address) ?><br>
                <?= e($city) ?> <?= e($state) ?><br>
                ZIP: <?= e($zip) ?><br>

                <?php if (!empty($phone)): ?>
                    Phone: <?= e($phone) ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="bottom">
            <div class="order">
                <strong>Website:</strong><br>
                mcshopusa.com<br>
                <strong>Date:</strong>
                <?= date('d/m/Y') ?><br>
                <strong>Scan:</strong> website
            </div>

            <div>
                <div class="qr">
                    <img src="<?= e($qrImage) ?>" alt="QR Code MC SHOP USA">
                </div>
                <div class="qr-text">MC SHOP USA</div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            setTimeout(() => window.print(), 500);
        });
    </script>

<?php endif; ?>

</body>
</html>