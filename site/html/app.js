const state = {
  products: [],
  cart: JSON.parse(localStorage.getItem("mc_shop_cart")) || {},
  modalProduct: null,
  modalSelectedSize: "M"
};

const bestSellersGrid = document.getElementById("bestSellersGrid");
const shirtsGrid = document.getElementById("shirtsGrid");
const hatsGrid = document.getElementById("hatsGrid");
const searchResultsGrid = document.getElementById("searchResultsGrid");
const searchResultsSection = document.getElementById("searchResultsSection");
const searchTermLabel = document.getElementById("searchTermLabel");
const defaultSections = document.getElementById("defaultSections");

const searchForm = document.getElementById("searchForm");
const searchInput = document.getElementById("searchInput");
const seeAllShirtsBtn = document.getElementById("seeAllShirtsBtn");
const seeAllHatsBtn = document.getElementById("seeAllHatsBtn");
const backHomeBtn = document.getElementById("backHomeBtn");
const brandHomeBtn = document.getElementById("brandHomeBtn");

const cartToggleBtn = document.getElementById("cartToggleBtn");
const closeCartBtn = document.getElementById("closeCartBtn");
const cartOverlay = document.getElementById("cartOverlay");
const cartDrawer = document.getElementById("cartDrawer");
const cartItems = document.getElementById("cartItems");
const cartTotal = document.getElementById("cartTotal");
const cartCountBadge = document.getElementById("cartCountBadge");
const checkoutBtn = document.getElementById("checkoutBtn");
const checkoutForm = document.getElementById("checkoutForm");
const checkoutCartInput = document.getElementById("checkoutCartInput");

const productModal = document.getElementById("productModal");
const productModalOverlay = document.getElementById("productModalOverlay");
const closeModalBtn = document.getElementById("closeModalBtn");
const modalImage = document.getElementById("modalImage");
const modalSoldOutVisual = document.getElementById("modalSoldOutVisual");
const modalCategory = document.getElementById("modalCategory");
const modalTitle = document.getElementById("modalTitle");
const modalCountry = document.getElementById("modalCountry");
const modalPrice = document.getElementById("modalPrice");
const modalSizes = document.getElementById("modalSizes");
const modalAddToCartBtn = document.getElementById("modalAddToCartBtn");

function saveCart() {
  localStorage.setItem("mc_shop_cart", JSON.stringify(state.cart));
}

function formatPrice(value) {
  return `$${Number(value).toFixed(2)}`;
}

function categoryLabel(cat) {
  return cat === "hat" ? "Hat" : "Shirt";
}

function getCartKey(productId, size) {
  return `${productId}__${size}`;
}

function getCartCount() {
  return Object.values(state.cart).reduce((sum, qty) => sum + qty, 0);
}

function getCartTotal() {
  return Object.entries(state.cart).reduce((total, [key, qty]) => {
    const [productId] = key.split("__");
    const product = state.products.find(p => String(p.id) === String(productId));
    if (!product) return total;
    return total + Number(product.price) * qty;
  }, 0);
}

function updateCartBadge() {
  const count = getCartCount();
  cartCountBadge.textContent = count;
  cartCountBadge.classList.toggle("hidden", count <= 0);
}

function showToast(message) {
  let toast = document.querySelector(".toast");

  if (!toast) {
    toast = document.createElement("div");
    toast.className = "toast";
    document.body.appendChild(toast);
  }

  toast.textContent = message;
  toast.classList.add("show");

  clearTimeout(window.__toastTimer);
  window.__toastTimer = setTimeout(() => {
    toast.classList.remove("show");
  }, 1800);
}

function toggleCart(force = null) {
  const shouldOpen = force !== null ? force : cartDrawer.classList.contains("translate-x-full");

  if (shouldOpen) {
    cartDrawer.classList.remove("translate-x-full");
    cartOverlay.classList.remove("hidden");
  } else {
    cartDrawer.classList.add("translate-x-full");
    cartOverlay.classList.add("hidden");
  }
}

function showHome() {
  searchInput.value = "";
  searchResultsGrid.innerHTML = "";
  searchResultsSection.classList.add("hidden");
  defaultSections.classList.remove("hidden");
  renderHome();
  window.scrollTo({ top: 0, behavior: "smooth" });
}

function openModal(product) {
  state.modalProduct = product;
  state.modalSelectedSize = "M";

  modalCategory.textContent = categoryLabel(product.cat);
  modalTitle.textContent = product.name;
  modalCountry.textContent = product.country;
  modalPrice.textContent = formatPrice(product.price);

  if (product.available) {
    modalImage.src = product.image;
    modalImage.alt = product.name;
    modalImage.classList.remove("hidden");
    modalSoldOutVisual.classList.add("hidden");
  } else {
    modalImage.src = "";
    modalImage.alt = product.name;
    modalImage.classList.add("hidden");
    modalSoldOutVisual.classList.remove("hidden");
  }

  const sizes = ["S", "M", "L", "XL", "XXL"];
  modalSizes.innerHTML = "";

  sizes.forEach(size => {
    const btn = document.createElement("button");
    btn.type = "button";
    btn.className = `size-btn ${size === "M" ? "active" : ""}`;
    btn.textContent = size;
    btn.disabled = !product.available;

    btn.addEventListener("click", () => {
      if (!product.available) return;
      state.modalSelectedSize = size;
      modalSizes.querySelectorAll(".size-btn").forEach(item => item.classList.remove("active"));
      btn.classList.add("active");
    });

    modalSizes.appendChild(btn);
  });

  if (product.available) {
    modalAddToCartBtn.disabled = false;
    modalAddToCartBtn.textContent = "Add to Cart";
    modalAddToCartBtn.className = "w-full bg-white text-black py-3 rounded-2xl font-black uppercase hover:bg-blue-500 hover:text-white transition";
  } else {
    modalAddToCartBtn.disabled = true;
    modalAddToCartBtn.textContent = "Sold Out";
    modalAddToCartBtn.className = "w-full bg-white/10 text-white/50 py-3 rounded-2xl font-black uppercase cursor-not-allowed";
  }

  productModal.classList.remove("hidden");
  document.body.style.overflow = "hidden";
}

function closeModal() {
  productModal.classList.add("hidden");
  document.body.style.overflow = "";
}

function addToCart(productId, size) {
  const product = state.products.find(p => String(p.id) === String(productId));
  if (!product || !product.available) {
    showToast("This product is sold out");
    return;
  }

  const key = getCartKey(productId, size);
  state.cart[key] = (state.cart[key] || 0) + 1;
  saveCart();
  updateCartBadge();
  renderCart();

  showToast(`${product.name} added - Size ${size}`);
}

function increaseQty(cartKey) {
  state.cart[cartKey] = (state.cart[cartKey] || 0) + 1;
  saveCart();
  updateCartBadge();
  renderCart();
}

function decreaseQty(cartKey) {
  if (!state.cart[cartKey]) return;
  state.cart[cartKey] -= 1;

  if (state.cart[cartKey] <= 0) {
    delete state.cart[cartKey];
  }

  saveCart();
  updateCartBadge();
  renderCart();
}

function removeFromCart(cartKey) {
  delete state.cart[cartKey];
  saveCart();
  updateCartBadge();
  renderCart();
}

function createProductCard(product) {
  const soldOutBadge = !product.available
    ? `<span class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-black uppercase px-3 py-1 rounded-full z-10">Sold Out</span>`
    : "";

  const visual = product.available
    ? `<img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover product-card-image">`
    : `<div class="sold-out-block">SOLD OUT</div>`;

  const div = document.createElement("div");
  div.className = "glass rounded-2xl overflow-hidden product-card";

  div.innerHTML = `
    <button type="button" class="product-open-btn block w-full text-left">
      <div class="h-64 overflow-hidden bg-zinc-900 relative">
        ${soldOutBadge}
        ${visual}
      </div>
      <div class="p-4">
        <h3 class="font-bold text-sm truncate mb-1">${product.name}</h3>
        <div class="flex justify-between items-center">
          <span class="text-blue-400 font-black">${formatPrice(product.price)}</span>
          <span class="text-white/55 text-[11px] uppercase">${categoryLabel(product.cat)}</span>
        </div>
      </div>
    </button>
  `;

  div.querySelector(".product-open-btn").addEventListener("click", () => {
    openModal(product);
  });

  return div;
}

function renderGrid(target, products) {
  if (!target) return;
  target.innerHTML = "";
  products.forEach(product => target.appendChild(createProductCard(product)));
}

function shuffleArray(arr) {
  const copy = [...arr];
  for (let i = copy.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [copy[i], copy[j]] = [copy[j], copy[i]];
  }
  return copy;
}

function getBestSellersCustom(products) {
  const availableShirts = products.filter(p => p.cat === "shirt" && p.available).slice(0, 3);
  const brazilHat = products.find(p => p.cat === "hat" && /brazil/i.test(p.name));
  const soldOutItem = products.find(p => !p.available);

  const result = [...availableShirts];
  if (brazilHat) result.push(brazilHat);
  if (soldOutItem) result.push(soldOutItem);

  return result.slice(0, 5);
}

function renderHome() {
  const shirts = state.products.filter(p => p.cat === "shirt");
  const hats = state.products.filter(p => p.cat === "hat");
  const bestSellers = getBestSellersCustom(state.products);

  renderGrid(bestSellersGrid, bestSellers);
  renderGrid(shirtsGrid, shirts.slice(0, 5));
  renderGrid(hatsGrid, hats.slice(0, 5));
}

function renderSearch(term) {
  const value = term.trim().toLowerCase();

  if (!value) {
    showHome();
    return;
  }

  const results = state.products.filter(product => {
    const data = `${product.name} ${product.country} ${product.cat} ${(product.tags || []).join(" ")}`.toLowerCase();
    return data.includes(value);
  });

  searchTermLabel.textContent = term;
  searchResultsGrid.innerHTML = "";
  results.forEach(product => {
    searchResultsGrid.appendChild(createProductCard(product));
  });

  defaultSections.classList.add("hidden");
  searchResultsSection.classList.remove("hidden");
  window.scrollTo({ top: 0, behavior: "smooth" });
}

function showAllByCategory(cat) {
  const results = state.products.filter(product => product.cat === cat);

  searchTermLabel.textContent = cat === "shirt" ? "Shirts" : "Hats";
  searchResultsGrid.innerHTML = "";

  results.forEach(product => {
    searchResultsGrid.appendChild(createProductCard(product));
  });

  defaultSections.classList.add("hidden");
  searchResultsSection.classList.remove("hidden");
  window.scrollTo({ top: 0, behavior: "smooth" });
}

function renderCart() {
  cartItems.innerHTML = "";

  const entries = Object.entries(state.cart);

  if (!entries.length) {
    cartItems.innerHTML = `
      <div class="glass rounded-2xl p-6 text-center text-white/70">
        Your cart is empty.
      </div>
    `;
    cartTotal.textContent = "$0.00";
    return;
  }

  entries.forEach(([cartKey, qty]) => {
    const [productId, size] = cartKey.split("__");
    const product = state.products.find(p => String(p.id) === String(productId));
    if (!product) return;

    const thumb = product.available
      ? `<img src="${product.image}" alt="${product.name}" class="w-20 h-20 object-cover rounded-xl">`
      : `<div class="w-20 h-20 rounded-xl sold-out-block text-[10px]">SOLD OUT</div>`;

    const item = document.createElement("div");
    item.className = "glass rounded-2xl p-4 flex gap-4";

    item.innerHTML = `
      ${thumb}
      <div class="flex-1">
        <h3 class="font-bold mb-1">${product.name}</h3>
        <p class="text-white/60 text-xs mb-1">${product.country}</p>
        <p class="text-blue-400 font-black mb-2">${formatPrice(product.price)}</p>
        <p class="text-xs text-white/70 mb-3">Size: <strong>${size}</strong></p>

        <div class="flex items-center justify-between gap-2">
          <div class="flex items-center gap-2">
            <button type="button" class="decrease-btn w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20">-</button>
            <span class="font-bold min-w-[24px] text-center">${qty}</span>
            <button type="button" class="increase-btn w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20">+</button>
          </div>

          <button type="button" class="remove-btn text-xs text-red-400 hover:text-red-300">Remove</button>
        </div>
      </div>
    `;

    item.querySelector(".increase-btn").addEventListener("click", () => increaseQty(cartKey));
    item.querySelector(".decrease-btn").addEventListener("click", () => decreaseQty(cartKey));
    item.querySelector(".remove-btn").addEventListener("click", () => removeFromCart(cartKey));

    cartItems.appendChild(item);
  });

  cartTotal.textContent = formatPrice(getCartTotal());
}

function startCheckout() {
  const entries = Object.entries(state.cart);

  if (!entries.length) {
    alert("Your cart is empty.");
    return;
  }

  const items = entries.map(([cartKey, qty]) => {
    const [productId, size] = cartKey.split("__");
    const product = state.products.find(p => String(p.id) === String(productId));

    return {
      id: product.id,
      name: product.name,
      country: product.country,
      category: product.cat,
      image: product.image,
      price: Number(product.price),
      size,
      qty
    };
  }).filter(Boolean);

  checkoutCartInput.value = JSON.stringify(items);
  checkoutForm.submit();
}

function setLanguage(lang) {
  document.querySelectorAll(".flag-btn").forEach(btn => {
    btn.classList.toggle("active", btn.dataset.lang === lang);
  });

  const applyLanguage = () => {
    const combo = document.querySelector(".goog-te-combo");
    if (!combo) {
      setTimeout(applyLanguage, 250);
      return;
    }

    combo.value = lang;
    combo.dispatchEvent(new Event("change"));
  };

  applyLanguage();
}

async function loadProducts() {
  try {
    const response = await fetch("products.json");

    if (!response.ok) {
      throw new Error(`HTTP ${response.status}`);
    }

    const products = await response.json();
    state.products = Array.isArray(products) ? products : [];

    renderHome();
    renderCart();
    updateCartBadge();
  } catch (error) {
    console.error("Error loading products:", error);

    if (bestSellersGrid) {
      bestSellersGrid.innerHTML = `<p class="text-white/60 col-span-full">Could not load products.json</p>`;
    }
  }
}

document.addEventListener("DOMContentLoaded", () => {
  searchForm.addEventListener("submit", (e) => {
    e.preventDefault();
    renderSearch(searchInput.value);
  });

  searchInput.addEventListener("input", () => {
    renderSearch(searchInput.value);
  });

  seeAllShirtsBtn.addEventListener("click", () => showAllByCategory("shirt"));
  seeAllHatsBtn.addEventListener("click", () => showAllByCategory("hat"));
  backHomeBtn.addEventListener("click", showHome);
  brandHomeBtn.addEventListener("click", showHome);

  cartToggleBtn.addEventListener("click", () => toggleCart(true));
  closeCartBtn.addEventListener("click", () => toggleCart(false));
  cartOverlay.addEventListener("click", () => toggleCart(false));

  productModalOverlay.addEventListener("click", closeModal);
  closeModalBtn.addEventListener("click", closeModal);

  modalAddToCartBtn.addEventListener("click", () => {
    if (!state.modalProduct) return;
    addToCart(state.modalProduct.id, state.modalSelectedSize);
    closeModal();
    toggleCart(true);
  });

  checkoutBtn.addEventListener("click", startCheckout);

  document.querySelectorAll(".flag-btn").forEach(btn => {
    btn.addEventListener("click", () => setLanguage(btn.dataset.lang));
  });

  loadProducts();
});