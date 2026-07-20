<?php
session_start();

/*
|--------------------------------------------------------------------------
| CONFIG INICIAL
|--------------------------------------------------------------------------
*/
$allProducts = json_decode(file_get_contents('products.json'), true) ?? [];
$search = trim($_GET['q'] ?? '');

// garante id em cada produto caso products.json não tenha
foreach ($allProducts as $i => &$prod) {
    if (!isset($prod['id'])) {
        $prod['id'] = $i + 1;
    }
}
unset($prod);

// inicia carrinho
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/*
|--------------------------------------------------------------------------
| FUNCOES
|--------------------------------------------------------------------------
*/
function getProductName($p) {
    if (isset($p['name']['pt']) && !empty($p['name']['pt'])) return $p['name']['pt'];
    if (isset($p['name']['en']) && !empty($p['name']['en'])) return $p['name']['en'];
    if (is_array($p['name'])) return reset($p['name']);
    return 'Produto';
}

function findProductById($products, $id) {
    foreach ($products as $p) {
        if ((string)$p['id'] === (string)$id) {
            return $p;
        }
    }
    return null;
}

function cartCount($cart) {
    return array_sum($cart);
}

function cartTotal($cart, $products) {
    $total = 0;
    foreach ($cart as $id => $qty) {
        $product = findProductById($products, $id);
        if ($product) {
            $total += ((float)$product['price']) * $qty;
        }
    }
    return $total;
}

/*
|--------------------------------------------------------------------------
| ACOES DO CARRINHO
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $productId = $_POST['product_id'] ?? null;

    if ($action === 'add' && $productId !== null) {
        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = 0;
        }
        $_SESSION['cart'][$productId]++;
    }

    if ($action === 'increase' && $productId !== null && isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]++;
    }

    if ($action === 'decrease' && $productId !== null && isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]--;
        if ($_SESSION['cart'][$productId] <= 0) {
            unset($_SESSION['cart'][$productId]);
        }
    }

    if ($action === 'remove' && $productId !== null) {
        unset($_SESSION['cart'][$productId]);
    }

    // evita reenvio de form
    $redirect = 'index.php';
    if ($search !== '') {
        $redirect .= '?q=' . urlencode($search);
    }
    header("Location: $redirect");
    exit;
}

/*
|--------------------------------------------------------------------------
| BUSCA
|--------------------------------------------------------------------------
*/
$searchResults = [];
if ($search !== '') {
    $searchResults = array_filter($allProducts, function($p) use ($search) {
        $names = '';
        if (isset($p['name']) && is_array($p['name'])) {
            $names = implode(' ', $p['name']);
        }

        $data = strtolower(
            $names . ' ' .
            ($p['tags'] ?? '') . ' ' .
            ($p['cat'] ?? '')
        );

        return strpos($data, strtolower($search)) !== false;
    });
}

/*
|--------------------------------------------------------------------------
| SECOES
|--------------------------------------------------------------------------
*/
usort($allProducts, fn($a, $b) => ($b['sales'] ?? 0) <=> ($a['sales'] ?? 0));
$bestSellers = array_slice($allProducts, 0, 5);

$shirts = array_filter($allProducts, fn($p) => ($p['cat'] ?? '') === 'shirt');
$shirtsSection = array_slice($shirts, 0, 5);

$hats = array_filter($allProducts, fn($p) => ($p['cat'] ?? '') === 'hat');
$hatsSection = array_slice($hats, 0, 5);

$cart = $_SESSION['cart'];
$cartItemsCount = cartCount($cart);
$cartItemsTotal = cartTotal($cart, $allProducts);
?>
<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MC SHOP USA</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syncopate:wght@700&family=Inter:wght@400;900&display=swap" rel="stylesheet">

    <style>
        body { background: #000; color: #fff; font-family: 'Inter', sans-serif; }
        .font-title { font-family: 'Syncopate', sans-serif; }
        .glass { background: rgba(255,255,255,0.03); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.08); }
        .neon-text { text-shadow: 0 0 10px rgba(59, 130, 246, 0.5); }

        /* Google Translate */
        #google_translate_element {
            min-width: 160px;
        }

        .goog-te-banner-frame.skiptranslate,
        body > .skiptranslate {
            display: none !important;
        }

        body {
            top: 0 !important;
        }

        .goog-te-gadget {
            color: #fff !important;
            font-size: 0 !important;
        }

        .goog-te-gadget .goog-te-combo {
            background: rgba(255,255,255,0.08);
            color: white;
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 999px;
            padding: 6px 10px;
            font-size: 12px !important;
            outline: none;
        }

        .goog-logo-link,
        .goog-te-gadget span {
            display: none !important;
        }
    </style>
</head>
<body class="pb-20">

    <!-- NAV -->
    <nav class="sticky top-0 z-[100] glass px-6 py-4">
        <div class="max-w-7xl mx-auto flex flex-wrap gap-4 justify-between items-center">
            <h1 class="text-xl font-black italic tracking-tighter uppercase notranslate">
                MC SHOP <span class="text-blue-500 font-normal">USA</span>
            </h1>

            <form action="index.php" method="GET" class="hidden md:flex relative w-72">
                <input
                    type="text"
                    name="q"
                    value="<?= htmlspecialchars($search) ?>"
                    placeholder="Buscar produtos..."
                    class="w-full bg-white/5 border border-white/10 rounded-full py-2 px-9 text-sm focus:border-blue-500 outline-none"
                >
                <span class="absolute left-3 top-2.5 text-gray-500 text-xs">🔍</span>
            </form>

            <div class="flex items-center gap-3">
                <div id="google_translate_element"></div>

                <button
                    onclick="toggleCart()"
                    class="relative bg-white/10 hover:bg-blue-600 transition px-4 py-2 rounded-full text-sm font-bold"
                >
                    🛒 Carrinho
                    <?php if ($cartItemsCount > 0): ?>
                        <span class="absolute -top-2 -right-2 bg-blue-500 text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center">
                            <?= $cartItemsCount ?>
                        </span>
                    <?php endif; ?>
                </button>
            </div>
        </div>
    </nav>

    <?php if($search !== ''): ?>
        <section class="max-w-7xl mx-auto p-8 mt-10">
            <h2 class="text-3xl font-black mb-8 uppercase italic">
                Resultados para: <span class="text-blue-500"><?= htmlspecialchars($search) ?></span>
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <?php foreach($searchResults as $p): renderCard($p, $search); endforeach; ?>
            </div>
        </section>
    <?php else: ?>

        <header class="h-[70vh] flex flex-col items-center justify-center text-center px-4 relative overflow-hidden">
            <div class="absolute inset-0 bg-blue-600/10 radial-gradient blur-3xl rounded-full scale-150"></div>
            <p class="text-blue-500 font-black tracking-[0.3em] text-xs mb-4 uppercase">
                COLEÇÃO EXCLUSIVA MC SHOP USA
            </p>
            <h2 class="text-6xl md:text-9xl font-title leading-none mb-6 neon-text">
                WORLD CUP 2026
            </h2>
            <div class="flex gap-4">
                <a href="#shirts" class="bg-white text-black px-8 py-3 rounded-full font-black text-xs uppercase hover:bg-blue-500 hover:text-white transition">
                    Comprar Camisetas
                </a>
                <a href="#hats" class="glass px-8 py-3 rounded-full font-black text-xs uppercase hover:border-white transition">
                    Comprar Bonés
                </a>
            </div>
        </header>

        <section class="max-w-7xl mx-auto p-8 border-t border-white/5">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <span class="text-blue-500 font-bold text-xs uppercase tracking-widest">Top Performance</span>
                    <h2 class="text-4xl font-black uppercase italic tracking-tighter">Mais Vendidos</h2>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <?php foreach($bestSellers as $p): renderCard($p, $search); endforeach; ?>
            </div>
        </section>

        <section id="shirts" class="max-w-7xl mx-auto p-8 mt-12">
            <div class="flex justify-between items-end mb-8">
                <h2 class="text-4xl font-black uppercase italic tracking-tighter">Camisetas</h2>
                <a href="catalog.php?cat=shirt" class="text-xs font-bold text-blue-500 border-b border-blue-500 pb-1">Ver Todos</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <?php foreach($shirtsSection as $p): renderCard($p, $search); endforeach; ?>
            </div>
        </section>

        <section id="hats" class="max-w-7xl mx-auto p-8 mt-12">
            <div class="flex justify-between items-end mb-8">
                <h2 class="text-4xl font-black uppercase italic tracking-tighter">Bonés</h2>
                <a href="catalog.php?cat=hat" class="text-xs font-bold text-blue-500 border-b border-blue-500 pb-1">Ver Todos</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <?php foreach($hatsSection as $p): renderCard($p, $search); endforeach; ?>
            </div>
        </section>

    <?php endif; ?>

    <!-- CART DRAWER -->
    <div id="cartOverlay" class="fixed inset-0 bg-black/60 z-[120] hidden" onclick="toggleCart()"></div>

    <aside id="cartDrawer" class="fixed top-0 right-0 h-full w-full max-w-md bg-zinc-950 border-l border-white/10 z-[130] transform translate-x-full transition duration-300 flex flex-col">
        <div class="p-5 border-b border-white/10 flex justify-between items-center">
            <h2 class="text-xl font-black uppercase">Seu Carrinho</h2>
            <button onclick="toggleCart()" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
        </div>

        <div class="flex-1 overflow-y-auto p-5 space-y-4">
            <?php if (empty($cart)): ?>
                <div class="glass rounded-2xl p-6 text-center text-white/70">
                    Seu carrinho está vazio.
                </div>
            <?php else: ?>
                <?php foreach ($cart as $id => $qty): ?>
                    <?php $product = findProductById($allProducts, $id); ?>
                    <?php if ($product): ?>
                        <div class="glass rounded-2xl p-4 flex gap-4">
                            <img src="<?= htmlspecialchars($product['image']) ?>" class="w-20 h-20 object-cover rounded-xl" alt="">
                            <div class="flex-1">
                                <h3 class="font-bold mb-1"><?= htmlspecialchars(getProductName($product)) ?></h3>
                                <p class="text-blue-400 font-black mb-3">$<?= number_format((float)$product['price'], 2) ?></p>

                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2">
                                        <form method="POST">
                                            <input type="hidden" name="action" value="decrease">
                                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                                            <button class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20">-</button>
                                        </form>

                                        <span class="font-bold min-w-[24px] text-center"><?= $qty ?></span>

                                        <form method="POST">
                                            <input type="hidden" name="action" value="increase">
                                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                                            <button class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20">+</button>
                                        </form>
                                    </div>

                                    <form method="POST">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                                        <button class="text-xs text-red-400 hover:text-red-300">Remover</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="p-5 border-t border-white/10 space-y-4">
            <div class="flex justify-between items-center text-lg">
                <span class="font-bold">Total</span>
                <span class="text-blue-400 font-black">$<?= number_format($cartItemsTotal, 2) ?></span>
            </div>

            <button class="w-full bg-blue-600 hover:bg-blue-500 transition rounded-2xl py-3 font-black uppercase">
                Finalizar Pedido
            </button>
        </div>
    </aside>

    <?php
    function renderCard($p, $search = '') { ?>
        <div class="glass rounded-2xl overflow-hidden group">
            <div class="h-64 overflow-hidden bg-zinc-900">
                <img src="<?= htmlspecialchars($p['image']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="<?= htmlspecialchars(getProductName($p)) ?>">
            </div>
            <div class="p-4">
                <h3 class="font-bold text-sm truncate mb-1"><?= htmlspecialchars(getProductName($p)) ?></h3>
                <div class="flex justify-between items-center gap-2">
                    <span class="text-blue-400 font-black">$<?= number_format((float)$p['price'], 2) ?></span>

                    <form method="POST">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($p['id']) ?>">
                        <?php if ($search !== ''): ?>
                            <input type="hidden" name="q" value="<?= htmlspecialchars($search) ?>">
                        <?php endif; ?>
                        <button type="submit" class="bg-white/10 px-3 py-2 rounded-lg hover:bg-blue-600 transition text-xs font-bold">
                            Adicionar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>

    <script>
        function toggleCart() {
            const drawer = document.getElementById('cartDrawer');
            const overlay = document.getElementById('cartOverlay');

            drawer.classList.toggle('translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function googleTranslateElementInit() {
            new google.translate.TranslateElement(
                {
                    pageLanguage: 'pt',
                    includedLanguages: 'pt,en,es',
                    autoDisplay: false
                },
                'google_translate_element'
            );
        }
    </script>

    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>
</html>