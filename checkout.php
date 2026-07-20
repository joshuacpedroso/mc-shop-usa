<?php
$cartJson = $_POST['cart_json'] ?? '[]';
$items = json_decode($cartJson, true);

if (!is_array($items)) {
    $items = [];
}

$total = 0;
foreach ($items as $item) {
    $price = isset($item['price']) ? (float)$item['price'] : 0;
    $qty = isset($item['qty']) ? (int)$item['qty'] : 0;
    $total += $price * $qty;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - MC SHOP USA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #000; color: #fff; font-family: Arial, sans-serif; }
        .glass { background: rgba(255,255,255,0.03); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.08); }
    </style>
</head>
<body class="min-h-screen">
    <div class="max-w-6xl mx-auto p-6 md:p-8">
        <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
            <h1 class="text-2xl md:text-3xl font-black italic uppercase">
                MC SHOP <span class="text-blue-500 font-normal">USA</span>
            </h1>
            <a href="index.html" class="text-sm text-blue-400 border-b border-blue-400">Back to Store</a>
        </div>

        <div class="grid lg:grid-cols-[1.2fr_0.8fr] gap-8">
            <div class="glass rounded-3xl p-6">
                <h2 class="text-2xl font-black uppercase mb-6">Customer Details</h2>

                <form action="create-checkout-session.php" method="POST" class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-2 text-white/70">First Name</label>
                        <input name="first_name" type="text" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm mb-2 text-white/70">Last Name</label>
                        <input name="last_name" type="text" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 outline-none focus:border-blue-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm mb-2 text-white/70">Email</label>
                        <input name="email" type="email" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 outline-none focus:border-blue-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm mb-2 text-white/70">Phone</label>
                        <input name="phone" type="text" class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 outline-none focus:border-blue-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm mb-2 text-white/70">Address</label>
                        <input name="address" type="text" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm mb-2 text-white/70">City</label>
                        <input name="city" type="text" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm mb-2 text-white/70">State</label>
                        <input name="state" type="text" class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm mb-2 text-white/70">ZIP Code</label>
                        <input name="zip" type="text" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 outline-none focus:border-blue-500">
                    </div>

                    <input type="hidden" name="cart_json" value='<?= htmlspecialchars(json_encode($items), ENT_QUOTES, "UTF-8") ?>'>

                    <div class="md:col-span-2 pt-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 transition rounded-2xl py-4 font-black uppercase">
                            Pay
                        </button>
                    </div>
                </form>
            </div>

            <div class="glass rounded-3xl p-6">
                <h2 class="text-2xl font-black uppercase mb-6">Order Summary</h2>

                <?php if (empty($items)): ?>
                    <div class="text-white/60">Your cart is empty.</div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($items as $item): ?>
                            <div class="flex gap-4 items-center border-b border-white/10 pb-4">
                                <img src="<?= htmlspecialchars($item['image'] ?? '') ?>" alt="<?= htmlspecialchars($item['name'] ?? '') ?>" class="w-20 h-20 object-cover rounded-2xl">
                                <div class="flex-1">
                                    <h3 class="font-bold"><?= htmlspecialchars($item['name'] ?? '') ?></h3>
                                    <p class="text-white/60 text-sm"><?= htmlspecialchars($item['country'] ?? '') ?></p>
                                    <p class="text-white/60 text-sm">Size: <?= htmlspecialchars($item['size'] ?? '') ?></p>
                                    <p class="text-white/60 text-sm">Qty: <?= (int)($item['qty'] ?? 0) ?></p>
                                </div>
                                <div class="text-blue-400 font-black">
                                    $<?= number_format(((float)($item['price'] ?? 0)) * ((int)($item['qty'] ?? 0)), 2) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-6 pt-6 border-t border-white/10 flex justify-between text-lg">
                        <span class="font-bold">Total</span>
                        <span class="text-blue-400 font-black">$<?= number_format($total, 2) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
