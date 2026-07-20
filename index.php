<!DOCTYPE html>
<html lang="en" class="scroll-smooth bg-white">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>MC SHOP USA | Premium Football Jerseys</title>

  <meta name="description" content="Shop MC SHOP USA for premium football jerseys, soccer shirts, club kits, national team apparel, hats and fan gear." />
  <meta name="keywords" content="football jerseys, soccer jerseys USA, club jerseys, soccer shirts, replica jerseys, MC SHOP USA" />
  <meta name="author" content="MC SHOP USA" />

  <link rel="icon" type="image/png" href="https://mcshopusa.com/img/favicon.png" />

  <!-- Tailwind & Fonts -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Syncopate:wght@400;700;900&family=Inter:wght@300;400;600;800;900&display=swap" rel="stylesheet">

  <style>
    /* =========================================
       TEMA PREMIUM CLEAN / EDITORIAL CLARO
       ========================================= */
    :root {
      --bg: #ffffff;          /* Branco absoluto */
      --surface: #fafafa;     /* Cinza ultra leve para contraste nas imagens */
      --border: #e5e5e5;      /* Linhas finas, limpas e elegantes */
      --border-dark: #111111; /* Linhas de hover (Preto) */
      --text: #0a0a0a;        /* Preto profundo */
      --muted: #737373;       /* Cinza para textos secundários */
      --accent: #000000;      /* Destaques em preto puro */
      
      /* Cores vibrantes do tema futebol premium (usadas nas animações) */
      --wc-purple: rgba(139, 92, 246, 0.15);
      --wc-green: rgba(16, 185, 129, 0.15);
      --wc-blue: rgba(59, 130, 246, 0.15);
      --wc-red: rgba(239, 68, 68, 0.15);
    }

    * { box-sizing: border-box; }

    body {
      background-color: var(--bg);
      color: var(--text);
      font-family: 'Inter', sans-serif;
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
    }

    /* Tipografia de Grife */
    h1, h2, h3, h4, .font-title {
      font-family: 'Syncopate', sans-serif;
      text-transform: uppercase;
    }

    /* Ocultar barra de rolagem mas permitir scroll */
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: var(--bg); }
    ::-webkit-scrollbar-thumb { background: #d4d4d4; }

    /* =========================================
       ESTRUTURA DE COMPONENTES (LINHAS FINAS)
       ========================================= */
    .border-b-thin { border-bottom: 1px solid var(--border); }
    .border-t-thin { border-top: 1px solid var(--border); }
    .border-l-thin { border-left: 1px solid var(--border); }
    .border-r-thin { border-right: 1px solid var(--border); }

    /* Botões Sharp (Retos) - Clean */
    .btn-sharp {
      background: transparent;
      color: var(--text);
      border: 1px solid var(--text);
      text-transform: uppercase;
      letter-spacing: 0.1em;
      font-weight: 800;
      transition: all 0.3s ease;
      cursor: pointer;
    }
    .btn-sharp:hover {
      background: var(--text);
      color: var(--bg);
    }
    
    .btn-solid {
      background: var(--text);
      color: var(--bg);
      text-transform: uppercase;
      letter-spacing: 0.1em;
      font-weight: 900;
      transition: all 0.3s ease;
    }
    .btn-solid:hover {
      background: #333;
    }

    /* =========================================
       CARTÕES DE PRODUTO (INJETADOS PELO JS)
       ========================================= */
    .product-card, .dashboard-card, .card {
      background: var(--bg);
      border: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      position: relative;
      transition: border-color 0.4s ease, box-shadow 0.4s ease;
      height: 100%;
    }
    .product-card:hover {
      border-color: var(--border-dark);
      box-shadow: 0 20px 40px rgba(0,0,0,0.04);
      z-index: 10; /* Fica por cima da grid na borda */
    }

    .product-card img, .card img {
      width: 100%;
      height: 380px;
      object-fit: contain;
      padding: 2rem;
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      transition: transform 0.8s cubic-bezier(0.25, 1, 0.5, 1);
      /* Removido o mix-blend-mode que escurecia as fotos */
    }
    .product-card:hover img {
      transform: scale(1.08) translateY(-5px);
    }

    #hatsGrid .product-card img {
      height: 300px;
      padding: 1rem;
    }

    .card-content, .product-card-content {
      padding: 1.5rem;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    /* Botões de Tamanho */
    .size-btn {
      background: transparent;
      border: 1px solid var(--border);
      color: var(--muted);
      font-weight: 700;
      text-transform: uppercase;
      padding: 8px 0;
      font-size: 11px;
      transition: all 0.2s;
    }
    .size-btn:hover, .size-btn.active {
      background: var(--text);
      color: var(--bg);
      border-color: var(--text);
    }

    /* =========================================
       ANIMAÇÕES PREMIUM FOOTBALL (HERO)
       ========================================= */
    .hero-wc-bg {
      position: absolute;
      inset: 0;
      overflow: hidden;
      z-index: 0;
      pointer-events: none;
    }

    .wc-blob {
      position: absolute;
      filter: blur(80px);
      border-radius: 50%;
      animation: floatWC 20s infinite alternate cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .blob-1 { width: 50vw; height: 50vw; background: var(--wc-purple); top: -20%; left: -10%; }
    .blob-2 { width: 40vw; height: 40vw; background: var(--wc-green); bottom: -10%; right: -10%; animation-delay: -5s; }
    .blob-3 { width: 45vw; height: 45vw; background: var(--wc-blue); top: 30%; left: 40%; animation-delay: -10s; }
    .blob-4 { width: 35vw; height: 35vw; background: var(--wc-red); top: -10%; right: 20%; animation-delay: -15s; }

    @keyframes floatWC {
      0% { transform: translate(0, 0) scale(1) rotate(0deg); }
      100% { transform: translate(100px, 100px) scale(1.2) rotate(45deg); }
    }

    /* Linhas abstratas de campo */
    .field-lines {
      position: absolute;
      inset: 0;
      background-image: 
        linear-gradient(rgba(0,0,0,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,0,0,0.03) 1px, transparent 1px);
      background-size: 80px 80px;
      z-index: 0;
      opacity: 0.8;
    }



    /* =========================================
       HERO VIDEO BACKGROUND + OVERLAY
       ========================================= */
    .hero-video-bg {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 0;
      pointer-events: none;
    }

    .hero-video-overlay {
      position: absolute;
      inset: 0;
      z-index: 1;
      background:
        linear-gradient(180deg, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0.10) 38%, rgba(0,0,0,0.82) 100%),
        radial-gradient(circle at center, rgba(255,255,255,0.04), rgba(0,0,0,0.35) 72%);
      pointer-events: none;
    }

    .hero-video-grain {
      position: absolute;
      inset: 0;
      z-index: 2;
      background-image:
        linear-gradient(rgba(255,255,255,0.045) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.035) 1px, transparent 1px);
      background-size: 80px 80px;
      opacity: 0.35;
      pointer-events: none;
    }

    /* =========================================
       BADGES, FLAGS & MARQUEE (PAINEL DE LED)
       ========================================= */
    .cart-badge {
      position: absolute;
      top: -6px;
      right: -10px;
      background: #ef4444; /* Vermelho vibrante */
      color: #fff;
      font-size: 10px;
      font-weight: 900;
      width: 20px;
      height: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      border: 2px solid var(--bg);
    }

    .flag-btn {
      font-size: 18px;
      filter: grayscale(100%) opacity(0.4);
      transition: all 0.3s;
      background: none; border: none; cursor: pointer;
    }
    .flag-btn:hover, .flag-btn.active { 
      filter: grayscale(0%) opacity(1);
      transform: scale(1.1);
    }

    .ticker-wrap {
      width: 100%;
      overflow: hidden;
      background: var(--text);
      color: var(--bg);
      padding: 16px 0;
      border-top: 1px solid var(--border);
    }
    .ticker {
      display: inline-block;
      white-space: nowrap;
      animation: ticker 30s linear infinite;
      font-family: 'Syncopate', sans-serif;
      font-weight: 900;
      font-size: 0.9rem;
      letter-spacing: 0.3em;
    }
    @keyframes ticker {
      0% { transform: translate3d(0, 0, 0); }
      100% { transform: translate3d(-50%, 0, 0); }
    }

    /* GTranslate */
    #google_translate_element, .skiptranslate { display: none !important; }


    /* =========================================
       ADMIN MINIMAL / HIDE SHOP BUTTONS
       ========================================= */
    .admin-gear-btn {
      position: fixed;
      right: 22px;
      bottom: 22px;
      z-index: 180;
      width: 42px;
      height: 42px;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: rgba(255,255,255,0.92);
      color: #111;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 12px 30px rgba(0,0,0,0.08);
      backdrop-filter: blur(14px);
      cursor: pointer;
      transition: all 0.25s ease;
    }
    .admin-gear-btn:hover { transform: rotate(35deg) scale(1.04); border-color: #111; }
    .admin-gear-btn svg { width: 18px; height: 18px; }

    .admin-panel-overlay {
      position: fixed;
      inset: 0;
      z-index: 190;
      background: rgba(0,0,0,0.35);
      backdrop-filter: blur(8px);
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .admin-panel-overlay.active { display: flex; }
    .admin-panel {
      width: 100%;
      max-width: 380px;
      background: #fff;
      border: 1px solid var(--border);
      box-shadow: 0 30px 80px rgba(0,0,0,0.18);
      padding: 28px;
    }
    .admin-panel input {
      width: 100%;
      border: 1px solid var(--border);
      padding: 13px 14px;
      outline: none;
      font-size: 13px;
      margin-top: 10px;
      transition: border-color 0.2s ease;
    }
    .admin-panel input:focus { border-color: #111; }
    .admin-admin-box { display: none; }
    .admin-panel.is-logged .admin-login-box { display: none; }
    .admin-panel.is-logged .admin-admin-box { display: block; }
    .admin-error { display: none; color: #dc2626; font-size: 12px; margin-top: 10px; font-weight: 700; }
    .admin-error.active { display: block; }

    body.website-hidden .shop-action-btn,
    body.website-hidden #modalAddToCartBtn,
    body.website-hidden #checkoutBtn,
    body.website-hidden #cartToggleBtn {
      display: none !important;
    }
  </style>
</head>

<body class="flex flex-col min-h-screen">

  <!-- =========================================
       NAVBAR (CLEAN EDITORIAL)
       ========================================= -->
  <nav class="sticky top-0 z-[100] bg-white/90 backdrop-blur-xl border-b-thin">
    <div class="max-w-[1800px] mx-auto px-6 py-4 flex justify-between items-center">
      
      <!-- Logo -->
      <div id="brandHomeBtn" class="cursor-pointer hover:opacity-70 transition-opacity">
        <img src="img/logo-mc.png" alt="MC Shop USA" class="h-8 md:h-10 object-contain">
      </div>

      <!-- Search Centralizado Minimalista -->
      <form id="searchForm" class="hidden md:flex relative w-full max-w-md mx-8">
        <input
          id="searchInput"
          type="text"
          placeholder="SEARCH COLLECTION..."
          class="w-full bg-transparent border-b border-gray-300 py-2 px-0 text-sm focus:border-black outline-none transition-colors text-black placeholder-gray-400 font-title tracking-widest uppercase"
        >
        <span class="absolute right-0 top-2.5 text-gray-400 text-sm font-title tracking-widest">SEARCH</span>
      </form>

      <!-- Actions -->
      <div class="flex gap-6 items-center">
        <div class="language-flags notranslate flex gap-3">
          <button type="button" class="flag-btn active" data-lang="en">🇺🇸</button>
          <button type="button" class="flag-btn" data-lang="pt">🇧🇷</button>
          <button type="button" class="flag-btn" data-lang="es">🇪🇸</button>
        </div>

        <button id="cartToggleBtn" type="button" class="relative text-black hover:text-gray-500 transition-colors uppercase font-title text-sm tracking-widest font-bold flex items-center gap-2">
          CART
          <span id="cartCountBadge" class="cart-badge hidden">0</span>
        </button>
      </div>
    </div>
  </nav>

  <main class="flex-grow w-full">
    
    <!-- =========================================
         SEARCH RESULTS
         ========================================= -->
    <section id="searchResultsSection" class="hidden max-w-[1800px] mx-auto px-6 py-12">
      <div class="flex flex-wrap justify-between items-end mb-12 pb-4 border-b-thin">
        <h2 class="text-2xl md:text-4xl font-title text-black">
          SEARCH: <span id="searchTermLabel" class="text-gray-400"></span>
        </h2>
        <button id="backHomeBtn" type="button" class="text-xs font-title tracking-widest uppercase text-gray-500 hover:text-black pb-1">
          [ CLOSE SEARCH ]
        </button>
      </div>
      <div id="searchResultsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-0 border-t-thin border-l-thin"></div>
    </section>

    <div id="defaultSections">
      
      <!-- =========================================
           HERO SECTION (VIDEO HOME BACKGROUND)
           ========================================= -->
      <header class="relative w-full h-[72vh] min-h-[560px] flex flex-col justify-between border-b-thin overflow-hidden bg-black">
        
        <!-- Home video background -->
        <video
          class="hero-video-bg"
          autoplay
          muted
          loop
          playsinline
          preload="metadata"
          poster="img/video-thumb.jpg">
          <source src="video/mcshop.mp4" type="video/mp4">
        </video>
        <div class="hero-video-overlay"></div>
        <div class="hero-video-grain"></div>

        <div class="relative z-10 w-full max-w-[1800px] mx-auto px-6 pt-10 flex justify-between items-start">
          <p class="text-white/75 font-title text-[10px] md:text-xs tracking-[0.3em] font-bold">MC SHOP USA</p>
          <p class="text-white/75 font-title text-[10px] md:text-xs tracking-[0.3em] text-right font-bold flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> LIVE STORE
          </p>
        </div>

        <!-- Empty center: video stays clean and visible -->
        <div class="relative z-10 flex-grow"></div>

        <!-- Bottom content: does not cover the main video -->
        <div class="relative z-10 w-full max-w-[1800px] mx-auto px-6 pb-10 md:pb-12">
          <div class="flex flex-col md:flex-row justify-between items-end gap-8 border-t border-white/20 pt-6">
            <div class="max-w-lg">
              <p class="font-title text-[10px] md:text-xs tracking-[0.35em] text-white/60 uppercase mb-3">Premium Store</p>
              <h1 class="font-title text-2xl md:text-4xl text-white tracking-tight leading-tight mb-4">
                Jerseys for true fans
              </h1>
              <p class="text-sm md:text-base text-white/80 font-medium leading-relaxed">
                Premium football jerseys from clubs and national teams, with fast shipping and exclusive collections.
              </p>
            </div>

            <div class="flex gap-4 w-full md:w-auto">
              <a href="#shirts" class="btn-solid px-12 py-4 text-center w-full md:w-auto rounded-full bg-white text-black hover:bg-gray-200">
                Shop Now
              </a>
              <a href="#hats" class="btn-sharp px-12 py-4 text-center w-full md:w-auto hidden sm:block rounded-full bg-white/10 text-white border-white/70 hover:bg-white hover:text-black">
                Caps
              </a>
            </div>
          </div>
        </div>
      </header>
      
      <!-- VIDEO SECTION REMOVED: video is now the Home background -->

      <!-- TICKER ESTILO PAINEL DE LED -->
      <div class="ticker-wrap shadow-xl">
        <div class="ticker flex items-center gap-8">
          <span>⚽ PREMIUM JERSEYS</span>
          <span>///</span>
          <span>FAST SHIPPING</span>
          <span>///</span>
          <span>TOP CLUBS</span>
          <span>///</span>
          <span>MC SHOP USA</span>
          <span>///</span>
          <span>PREMIUM CAPS</span>
          <span>///</span>
          <span>WORLDWIDE DELIVERY</span>
          <span>///</span>
          <span>⚽ PREMIUM JERSEYS</span>
          <span>///</span>
          <span>FAST SHIPPING</span>
          <span>///</span>
          <span>TOP CLUBS</span>
          <span>///</span>
          <span>MC SHOP USA</span>
          <span>///</span>
          <span>PREMIUM CAPS</span>
          <span>///</span>
          <span>WORLDWIDE DELIVERY</span>
        </div>
      </div>

      <!-- =========================================
           BEST SELLERS
           ========================================= -->
      <section class="w-full max-w-[1800px] mx-auto px-6 py-24">
        <div class="flex justify-between items-end mb-12 border-b-thin pb-4">
          <h2 class="text-3xl md:text-5xl font-title text-black tracking-tighter">BEST SELLERS</h2>
          <span class="text-xs text-gray-500 tracking-[0.2em] font-title hidden md:flex items-center gap-2">
            TOP PERFORMANCE
          </span>
        </div>
        
        <div id="bestSellersGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-0 border-t-thin border-l-thin">
          <!-- JS injeta os cards aqui -->
        </div>
      </section>

      <!-- =========================================
           SHIRTS
           ========================================= -->
      <section id="shirts" class="w-full max-w-[1800px] mx-auto px-6 py-12 bg-gray-50/50 border-y-thin">
        <div class="flex justify-between items-end mb-12 border-b-thin pb-4 border-gray-300">
          <h2 class="text-3xl md:text-5xl font-title text-black tracking-tighter">JERSEYS</h2>
          <button type="button" id="seeAllShirtsBtn" class="text-xs font-title tracking-widest text-black hover:text-blue-600 transition-colors uppercase border-b border-black pb-1 hover:border-blue-600">
            VIEW ALL COLLECTION
          </button>
        </div>
        <div id="shirtsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-0 border-t-thin border-l-thin border-gray-200"></div>
      </section>

      <!-- =========================================
           HATS
           ========================================= -->
      <section id="hats" class="w-full max-w-[1800px] mx-auto px-6 py-24">
        <div class="flex justify-between items-end mb-12 border-b-thin pb-4">
          <h2 class="text-3xl md:text-5xl font-title text-black tracking-tighter">Caps</h2>
          <button type="button" id="seeAllHatsBtn" class="text-xs font-title tracking-widest text-black hover:text-blue-600 transition-colors uppercase border-b border-black pb-1 hover:border-blue-600">
            VIEW ALL CAPS
          </button>
        </div>
        <div id="hatsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-0 border-t-thin border-l-thin"></div>
      </section>
      
    </div>
  </main>

  <!-- =========================================
       CART SIDEBAR (CLEAN MINIMAL)
       ========================================= -->
  <div id="cartOverlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[120] hidden transition-opacity"></div>

  <aside id="cartDrawer" class="fixed top-0 right-0 h-full w-full max-w-md bg-white border-l border-gray-200 z-[130] transform translate-x-full transition-transform duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] flex flex-col shadow-2xl">
    <div class="p-8 flex justify-between items-center border-b border-gray-200 bg-gray-50">
      <h2 class="text-2xl font-title uppercase text-black tracking-widest">YOUR CART</h2>
      <button id="closeCartBtn" type="button" class="text-gray-400 hover:text-black transition-colors text-3xl font-light leading-none">&times;</button>
    </div>

    <!-- HTML injetado pelo app.js -->
    <div id="cartItems" class="flex-1 overflow-y-auto p-8 space-y-6"></div>

    <div class="p-8 bg-white border-t border-gray-200 space-y-6">
      <div class="flex justify-between items-end pb-4 border-b border-gray-200">
        <span class="font-title text-xs tracking-widest text-gray-500 uppercase">Estimated Total</span>
        <span id="cartTotal" class="text-black font-title text-2xl tracking-tighter">$0.00</span>
      </div>

      <button id="checkoutBtn" type="button" class="w-full btn-solid py-5 text-sm rounded-none">
        SECURE CHECKOUT
      </button>
    </div>
  </aside>
  
  <!-- =========================================
       VISITOR COUNTER (ESTILO MATCH CLOCK)
       ========================================= -->
  <div class="fixed bottom-6 left-6 z-50">
    <div class="bg-white border border-gray-200 shadow-xl px-4 py-3 flex items-center gap-4 rounded-xl">
      <div class="w-2 h-2 bg-red-500 rounded-full animate-ping absolute"></div>
      <div class="w-2 h-2 bg-red-500 rounded-full relative z-10"></div>
      <div>
        <p class="text-[9px] font-title uppercase tracking-[0.2em] text-gray-400 mb-0.5">
          LIVE FANS
        </p>
        <p class="text-lg font-black text-black leading-none">
          <span id="accessCounter">0</span>
        </p>
      </div>
    </div>
  </div>

  <script>
    fetch("counter.php")
      .then(res => res.json())
      .then(data => {
        document.getElementById("accessCounter").textContent = data.total.toLocaleString("en-US");
      })
      .catch(err => console.log('Counter offline'));
  </script>

  <!-- =========================================
       PRODUCT MODAL (CLEAN LIGHT MODE)
       ========================================= -->
  <div id="productModal" class="fixed inset-0 z-[150] hidden">
    <div id="productModalOverlay" class="absolute inset-0 bg-black/60 backdrop-blur-md"></div>

    <div class="absolute inset-0 flex items-center justify-center p-4 md:p-12">
      <div class="bg-white w-full max-w-6xl h-full max-h-[90vh] border border-gray-200 relative flex flex-col md:flex-row overflow-hidden shadow-2xl rounded-2xl md:rounded-none">
        
        <button id="closeModalBtn" type="button" class="absolute top-6 right-6 z-30 text-gray-400 hover:text-black bg-white rounded-full w-10 h-10 flex items-center justify-center shadow-md transition-all text-2xl font-light leading-none">
          &times;
        </button>

        <!-- Imagem -->
        <div class="w-full md:w-1/2 h-[40vh] md:h-full flex items-center justify-center relative p-8 border-b md:border-b-0 md:border-r border-gray-200 bg-[#fafafa]">
          <!-- Removido o mix-blend-multiply daqui também -->
          <img id="modalImage" src="" alt="" class="w-full h-full object-contain max-h-[500px] drop-shadow-xl">
          
          <button id="prevImageBtn" type="button" class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-600 hover:text-black bg-white shadow-md w-10 h-10 rounded-full transition-all text-2xl font-light flex items-center justify-center">‹</button>
          <button id="nextImageBtn" type="button" class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-600 hover:text-black bg-white shadow-md w-10 h-10 rounded-full transition-all text-2xl font-light flex items-center justify-center">›</button>
          
          <div id="modalSoldOutVisual" class="hidden absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-10">
            <span class="bg-red-600 text-white font-title text-xl tracking-widest uppercase px-8 py-3 rounded-full rotate-[-10deg] shadow-xl">SOLD OUT</span>
          </div>
        </div>

        <!-- Info -->
        <div class="p-8 md:p-16 flex flex-col justify-center w-full md:w-1/2 h-[60vh] md:h-full overflow-y-auto">
          <div class="flex items-center gap-4 mb-4">
            <p id="modalCategory" class="text-blue-600 bg-blue-50 px-3 py-1 font-title text-[10px] uppercase tracking-[0.2em] rounded"></p>
            <p id="modalCountry" class="text-gray-600 bg-gray-100 px-3 py-1 font-title text-[10px] uppercase tracking-[0.2em] rounded"></p>
          </div>
          
          <h3 id="modalTitle" class="text-3xl md:text-5xl font-title mb-6 text-black leading-[1.1] tracking-tighter"></h3>
          
          <div class="flex items-end gap-4 mb-12 pb-8 border-b border-gray-200">
            <p id="modalPrice" class="text-4xl font-black text-black"></p>
            <p class="text-sm text-gray-400 line-through hidden mb-1" id="modalOldPrice"></p>
          </div>

          <div class="mb-12">
            <div class="flex justify-between items-end mb-6">
              <p class="text-[10px] font-title uppercase tracking-[0.2em] text-gray-500">Select Size</p>
              <span class="text-[10px] font-title uppercase tracking-[0.2em] text-blue-600 cursor-pointer hover:underline pb-0.5">Size Guide</span>
            </div>
            <!-- Botões de tamanho via JS -->
            <div id="modalSizes" class="grid grid-cols-4 gap-3"></div>
          </div>

          <button id="modalAddToCartBtn" type="button" class="w-full btn-solid py-5 text-sm mt-auto rounded-xl">
            ADD TO CART
          </button>
        </div>
      </div>
    </div>
  </div>
  
<!-- =========================================
     CONTACT
     ========================================= -->
<section id="contact" class="w-full border-t-thin bg-white py-24">
    <div class="max-w-[1500px] mx-auto px-6">

        <div class="grid lg:grid-cols-2 gap-20 items-center">

            <!-- LEFT -->
            <div>
                <p class="font-title text-xs tracking-[0.35em] text-gray-400 uppercase">
                    Contact
                </p>

                <h2 class="font-title text-4xl md:text-6xl mt-4 tracking-tight leading-tight">
                    GET IN TOUCH
                </h2>

                <p class="mt-8 text-gray-600 leading-relaxed max-w-lg">
                    Need help finding the perfect jersey or cap? Have questions
                    about sizing, shipping or your order? Our team is ready to
                    help you.
                </p>

                <div class="mt-12 space-y-6">

                    <div>
                        <p class="font-title text-[10px] tracking-[0.25em] text-gray-400 uppercase">
                            Email
                        </p>

                        <p class="text-lg font-semibold">
                            contact@mcshopusa.com
                        </p>
                    </div>

                    <div>
                        <p class="font-title text-[10px] tracking-[0.25em] text-gray-400 uppercase">
                            Phone
                        </p>

                        <p class="text-lg font-semibold">
                            +1 (848) 383-8929
                        </p>
                    </div>

                    <div>
                        <p class="font-title text-[10px] tracking-[0.25em] text-gray-400 uppercase">
                            Location
                        </p>

                        <p class="text-lg font-semibold">
                            United States
                        </p>
                    </div>

                </div>
            </div>

            <!-- RIGHT -->
            <div>

                <form action="contact.php" method="POST" class="space-y-6">

                    <input
                        type="text"
                        name="name"
                        placeholder="Full Name"
                        required
                        class="w-full border border-gray-300 px-5 py-4 outline-none focus:border-black transition">

                    <input
                        type="email"
                        name="email"
                        placeholder="Email Address"
                        required
                        class="w-full border border-gray-300 px-5 py-4 outline-none focus:border-black transition">

                    <input
                        type="tel"
                        name="phone"
                        placeholder="Phone Number"
                        class="w-full border border-gray-300 px-5 py-4 outline-none focus:border-black transition">

                    <textarea
                        name="message"
                        rows="6"
                        placeholder="How can we help you?"
                        required
                        class="w-full border border-gray-300 px-5 py-4 outline-none resize-none focus:border-black transition"></textarea>

                    <button
                        type="submit"
                        class="btn-solid w-full py-5 rounded-none text-sm">
                        SEND MESSAGE
                    </button>

                </form>

            </div>

        </div>

    </div>
</section>  
  
  <!-- =========================================
       FOOTER (CLEAN & TIPOGRÁFICO)
       ========================================= -->
  <footer class="bg-gray-50 border-t border-gray-200 pt-24 pb-12 mt-auto relative overflow-hidden">
    
    <div class="max-w-[1800px] mx-auto px-6 relative z-10">
      
      <!-- Logo Gigante do Footer -->
      <div class="w-full flex justify-center mb-24 border-b border-gray-200 pb-12">
         <h2 class="text-[10vw] font-title leading-none text-black tracking-tighter text-center opacity-10">MC SHOP</h2>
      </div>

      <div class="grid md:grid-cols-3 gap-16 lg:gap-24 mb-24">
        <!-- Info -->
        <div>
          <p class="font-title text-xs tracking-[0.2em] text-black mb-6 uppercase">About</p>
          <p class="text-gray-500 text-sm leading-relaxed mb-8 font-medium max-w-sm">
            Setting the global standard for football apparel. Premium football jerseys and apparel from the world's biggest clubs and national teams, shipped worldwide with absolute precision and speed.
          </p>
        </div>

        <!-- Links -->
        <div>
          <p class="font-title text-xs tracking-[0.2em] text-black mb-6 uppercase">Navigation</p>
          <ul class="space-y-4 font-medium text-sm text-gray-500">
            <li><a href="#shirts" class="hover:text-blue-600 transition-colors">Shop Jerseys</a></li>
            <li><a href="#hats" class="hover:text-blue-600 transition-colors">Shop Caps</a></li>
            <li><a href="#" class="hover:text-blue-600 transition-colors">Track Order</a></li>
            <li><a href="#" class="hover:text-blue-600 transition-colors">Returns & Exchanges</a></li>
          </ul>
        </div>

        <!-- Support -->
        <div>
          <p class="font-title text-xs tracking-[0.2em] text-black mb-6 uppercase">Support</p>
          <ul class="space-y-4 font-medium text-sm text-gray-500">
            <li class="flex flex-col">
              <span class="text-[10px] font-title tracking-widest text-gray-400 mb-1">EMAIL</span>
              <a href="mailto:contact@mcshopusa.com" class="hover:text-blue-600 transition-colors text-black font-bold">contact@mcshopusa.com</a>
            </li>
            <li class="flex flex-col mt-4">
              <span class="text-[10px] font-title tracking-widest text-gray-400 mb-1">PHONE</span>
              <span class="text-black font-bold">+1 (848) 383-8929</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-8 border-t border-gray-200">
        <p class="text-[10px] font-title tracking-[0.2em] text-gray-400 uppercase">
          © 2026 MC Shop USA. All rights reserved.
        </p>
        <div class="flex gap-4">
          <a href="#" class="text-[10px] font-title tracking-[0.2em] text-gray-400 hover:text-black uppercase">Privacy</a>
          <a href="#" class="text-[10px] font-title tracking-[0.2em] text-gray-400 hover:text-black uppercase">Terms</a>
        </div>
      </div>
    </div>
  </footer>



  <!-- =========================================
       ADMIN MINIMAL GEAR / HIDE WEBSITE BUTTONS
       Login: admin / admin
       ========================================= -->
  <button id="adminGearBtn" type="button" class="admin-gear-btn" aria-label="Admin settings">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <circle cx="12" cy="12" r="3"></circle>
      <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06A1.65 1.65 0 0 0 15 19.4a1.65 1.65 0 0 0-1 .6 1.65 1.65 0 0 0-.4 1.08V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 8.6 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-.6-1 1.65 1.65 0 0 0-1.08-.4H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 8.6a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-.6 1.65 1.65 0 0 0 .4-1.08V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9c.24.3.38.67.4 1.06H21a2 2 0 1 1 0 4h-.09A1.65 1.65 0 0 0 19.4 15z"></path>
    </svg>
  </button>

  <div id="adminPanelOverlay" class="admin-panel-overlay">
    <div id="adminPanel" class="admin-panel">
      <div class="flex justify-between items-start gap-4 mb-6">
        <div>
          <p class="font-title text-xs tracking-[0.2em] text-gray-400 uppercase mb-2">Admin Area</p>
          <h3 class="font-title text-xl text-black tracking-tighter">Website Control</h3>
        </div>
        <button id="adminCloseBtn" type="button" class="text-3xl leading-none text-gray-400 hover:text-black">&times;</button>
      </div>

      <div class="admin-login-box">
        <input id="adminUser" type="text" placeholder="Username" autocomplete="username">
        <input id="adminPass" type="password" placeholder="Password" autocomplete="current-password">
        <p id="adminError" class="admin-error">Invalid login. Use admin / admin.</p>
        <button id="adminLoginBtn" type="button" class="btn-solid w-full py-4 text-xs mt-5">LOGIN</button>
      </div>

      <div class="admin-admin-box">
        <p class="text-sm text-gray-500 leading-relaxed mb-6">
          Hide the store buttons so visitors can only view the products.
        </p>
        <button id="hideWebsiteBtn" type="button" class="btn-solid w-full py-4 text-xs mb-3">HIDE WEBSITE</button>
        <button id="showWebsiteBtn" type="button" class="btn-sharp w-full py-4 text-xs">SHOW WEBSITE</button>
        <button id="adminLogoutBtn" type="button" class="w-full mt-5 text-[10px] font-title tracking-[0.2em] text-gray-400 hover:text-black uppercase">Logout</button>
      </div>
    </div>
  </div>

  <!-- Checkout Form Oculto para o JS -->
  <form id="checkoutForm" action="checkout.php" method="POST" class="hidden">
    <input type="hidden" name="cart_json" id="checkoutCartInput">
  </form>


  <script>
    /* =========================================
       PRODUCT SIZE PATCH: ADD XXXL WITH STOCK 1
       Works with products.json loaded by app.js.
       ========================================= */
    (function () {
      const originalFetch = window.fetch.bind(window);

      function normalizeProductSizes(product) {
        if (!product || typeof product !== "object") return product;

        const sizeKeys = ["sizes", "availableSizes", "size", "tamanhos"];
        sizeKeys.forEach((key) => {
          if (Array.isArray(product[key]) && !product[key].map(String).includes("XXXL")) {
            product[key].push("XXXL");
          }
        });

        const stockKeys = ["stock", "stocks", "inventory", "estoque"];
        stockKeys.forEach((key) => {
          if (product[key] && typeof product[key] === "object" && !Array.isArray(product[key])) {
            product[key]["XXXL"] = product[key]["XXXL"] ?? 1;
          }
        });

        if (Array.isArray(product.variants)) {
          const hasXXXL = product.variants.some((variant) => String(variant.size || variant.name || variant.label || "").toUpperCase() === "XXXL");
          if (!hasXXXL) product.variants.push({ size: "XXXL", stock: 1 });
        }

        return product;
      }

      window.fetch = async function (...args) {
        const response = await originalFetch(...args);
        const url = String(args[0] && args[0].url ? args[0].url : args[0]);
        if (!url.includes("products.json")) return response;
        const cloned = response.clone();
        return new Response(await cloned.text(), {
          status: response.status,
          statusText: response.statusText,
          headers: response.headers
        });
      };

      const originalJson = Response.prototype.json;
      Response.prototype.json = async function () {
        const data = await originalJson.call(this);
        try {
          if (Array.isArray(data)) data.forEach(normalizeProductSizes);
          if (data && Array.isArray(data.products)) data.products.forEach(normalizeProductSizes);
        } catch (error) {
          console.warn("XXXL size patch skipped", error);
        }
        return data;
      };
    })();
  </script>

  <script src="app.js?v=45454"></script>


  <script>
    /* Fallback visual: if the modal size buttons are rendered without XXXL, add the option for testing. */
    (function () {
      function ensureXXXLButton() {
        const box = document.getElementById("modalSizes");
        if (!box) return;
        const labels = Array.from(box.children).map((el) => (el.textContent || "").trim().toUpperCase());
        if (!labels.length || labels.includes("XXXL")) return;

        const btn = document.createElement("button");
        btn.type = "button";
        btn.className = "size-btn";
        btn.textContent = "XXXL";
        btn.dataset.size = "XXXL";
        btn.addEventListener("click", function () {
          box.querySelectorAll(".size-btn").forEach((item) => item.classList.remove("active"));
          btn.classList.add("active");
        });
        box.appendChild(btn);
      }

      const observer = new MutationObserver(ensureXXXLButton);
      observer.observe(document.body, { childList: true, subtree: true });
      document.addEventListener("click", () => setTimeout(ensureXXXLButton, 80));
      ensureXXXLButton();
    })();
  </script>



  <script>
    (function () {
      const ADMIN_USER = "admin";
      const ADMIN_PASS = "admin";

      const gearBtn = document.getElementById("adminGearBtn");
      const overlay = document.getElementById("adminPanelOverlay");
      const panel = document.getElementById("adminPanel");
      const closeBtn = document.getElementById("adminCloseBtn");
      const loginBtn = document.getElementById("adminLoginBtn");
      const logoutBtn = document.getElementById("adminLogoutBtn");
      const hideBtn = document.getElementById("hideWebsiteBtn");
      const showBtn = document.getElementById("showWebsiteBtn");
      const userInput = document.getElementById("adminUser");
      const passInput = document.getElementById("adminPass");
      const errorBox = document.getElementById("adminError");

      function isLogged() {
        return localStorage.getItem("mcshop_admin_logged") === "yes";
      }

      function isHiddenMode() {
        return localStorage.getItem("mcshop_website_hidden") === "yes";
      }

      function applyHiddenMode() {
        document.body.classList.toggle("website-hidden", isHiddenMode());
        markShopButtons();
      }

      function markShopButtons() {
        const buttons = document.querySelectorAll("button, a");
        buttons.forEach((el) => {
          const text = (el.textContent || "").trim().toUpperCase();
          if (text === "ADD TO CART" || text.includes("ADD TO CART")) {
            el.classList.add("shop-action-btn");
          }
        });
      }

      function openPanel() {
        overlay.classList.add("active");
        panel.classList.toggle("is-logged", isLogged());
        errorBox.classList.remove("active");
        setTimeout(() => (isLogged() ? hideBtn.focus() : userInput.focus()), 80);
      }

      function closePanel() {
        overlay.classList.remove("active");
      }

      gearBtn.addEventListener("click", openPanel);
      closeBtn.addEventListener("click", closePanel);
      overlay.addEventListener("click", (event) => {
        if (event.target === overlay) closePanel();
      });

      loginBtn.addEventListener("click", () => {
        const user = userInput.value.trim();
        const pass = passInput.value.trim();
        if (user === ADMIN_USER && pass === ADMIN_PASS) {
          localStorage.setItem("mcshop_admin_logged", "yes");
          panel.classList.add("is-logged");
          errorBox.classList.remove("active");
          userInput.value = "";
          passInput.value = "";
        } else {
          errorBox.classList.add("active");
        }
      });

      [userInput, passInput].forEach((input) => {
        input.addEventListener("keydown", (event) => {
          if (event.key === "Enter") loginBtn.click();
        });
      });

      logoutBtn.addEventListener("click", () => {
        localStorage.removeItem("mcshop_admin_logged");
        panel.classList.remove("is-logged");
      });

      hideBtn.addEventListener("click", () => {
        localStorage.setItem("mcshop_website_hidden", "yes");
        applyHiddenMode();
        closePanel();
      });

      showBtn.addEventListener("click", () => {
        localStorage.removeItem("mcshop_website_hidden");
        applyHiddenMode();
        closePanel();
      });

      const observer = new MutationObserver(markShopButtons);
      observer.observe(document.body, { childList: true, subtree: true });

      markShopButtons();
      applyHiddenMode();
    })();
  </script>
  
    <div id="google_translate_element" style="display:none;"></div>
    
    <script>
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({
        pageLanguage: 'en',
        includedLanguages: 'en,pt,es',
        autoDisplay: false
      }, 'google_translate_element');
    }
    </script>
    
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</body>
</html>