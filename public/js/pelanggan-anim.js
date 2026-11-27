/**
 * OMYU Pelanggan - Scroll Fade-Up Reveal
 * Replays animation every time element enters viewport.
 */
document.addEventListener('DOMContentLoaded', () => {
  initPelangganReveal();
});

function initPelangganReveal() {
  const selector = [
    '.catalog-banner',
    '.category-scroll',
    '.product-card',
    '.section-card',
    '.summary-card',
    '.empty-box',
    '.search-card',
    '.ticket-main-box',
    '.pay-card',
    '.alert-sukses',
    '.hero-copy',
    '.hero-visual',
    '.about-copy',
    '.about-visual',
    '.menu-showcase',
    '.contact-card',
    '.footer-brand',
    '.footer-links',
    '.footer-contact',
    '.catalog-wrapper > .catalog-banner',
    '.cart-wrapper > *',
    '.checkout-wrapper > *',
    '.pay-wrapper > *',
    '.ticket-wrapper > *',
    '.hero-left',
    '.hero-right',
    '.booth-card',
    '.mutu-card-box',
    '.features-capsule',
    '.pure-fruit-pill'
  ].join(', ');

  const elements = Array.from(document.querySelectorAll(selector));
  const unique = [...new Set(elements)];
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  unique.forEach((el, index) => {
    if (el.dataset.pelangganRevealReady === 'true') return;
    if (el.closest('.mobile-bottom-nav') || el.classList.contains('mobile-bottom-nav')) return;
    el.dataset.pelangganRevealReady = 'true';
    el.classList.add('pelanggan-reveal');

    // Stagger small delay for grids/lists
    if (el.classList.contains('product-card') || el.classList.contains('section-card')) {
      const delay = Math.min((index % 6) * 0.06, 0.3);
      el.style.animationDelay = `${delay}s`;
    } else {
      el.style.animationDelay = '';
    }

    if (reduceMotion) {
      el.style.opacity = '1';
      el.style.transform = 'none';
      el.style.animation = 'none';
      return;
    }

    el.classList.add('pelanggan-reveal-pending');
    observePelangganReveal(el);
  });
}

function observePelangganReveal(el) {
  if (el.dataset.pelangganRevealBound === 'true') return;
  el.dataset.pelangganRevealBound = 'true';

  if (!('IntersectionObserver' in window)) {
    replayPelangganReveal(el);
    return;
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        replayPelangganReveal(entry.target);
      } else {
        resetPelangganReveal(entry.target);
      }
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -10% 0px' });

  observer.observe(el);
}

function resetPelangganReveal(el) {
  el.classList.add('pelanggan-reveal-pending');
  el.style.animation = 'none';
  el.style.opacity = '0';
  // force reflow so next replay always restarts cleanly
  void el.offsetHeight;
  el.style.animation = '';
}

function replayPelangganReveal(el) {
  el.style.opacity = '';
  el.classList.remove('pelanggan-reveal-pending');
  el.style.animation = 'none';
  void el.offsetHeight;
  el.style.animation = '';
}

/**
 * ==========================================================================
 * OMYU Pelanggan - Enhanced Interactive Add to Cart & Flying Animation
 * ==========================================================================
 */

window.pilihVarianKartu = function(chip, event) {
  if (event) event.stopPropagation();
  const container = chip.closest('.variant-chips-container');
  const card = chip.closest('.product-card');
  if (!container || !card) return;

  // Toggle active chip
  container.querySelectorAll('.variant-chip').forEach(c => c.classList.remove('active'));
  chip.classList.add('active');

  const varianId = chip.getAttribute('data-id');
  const hargaFormat = chip.getAttribute('data-harga-format');

  // Update hidden input in form
  const inputVarian = card.querySelector('.input-varian-id');
  if (inputVarian) {
    inputVarian.value = varianId || '';
  }

  // Update price display
  const priceEl = card.querySelector('.product-price-main');
  if (priceEl && hargaFormat) {
    priceEl.textContent = hargaFormat;
  }

  // Update strikethrough price dynamically based on card's discount amount
  const strikeEl = card.querySelector('.product-price-strike');
  const harga = parseInt(chip.getAttribute('data-harga') || '0', 10);
  const potongan = parseInt(card.getAttribute('data-potongan') || '3000', 10);
  if (strikeEl && harga > 0 && potongan > 0) {
    const strikeHarga = harga + potongan;
    strikeEl.textContent = 'Rp ' + Number(strikeHarga).toLocaleString('id-ID');
  }
};

window.ubahQtyKartu = function(btn, delta, maxStok, event) {
  if (event) {
    event.preventDefault();
    event.stopPropagation();
  }
  const row = btn.closest('.card-qty-row');
  const card = btn.closest('.product-card') || btn.closest('.favorit-card');
  if (!row || !card) return;

  const displayInput = row.querySelector('.card-qty-input');
  const hiddenInput = card.querySelector('.input-qty');
  if (!displayInput) return;

  let current = parseInt(displayInput.value || '1', 10);
  if (isNaN(current)) current = 1;

  let next = current + delta;
  if (next < 1) next = 1;
  if (maxStok && next > maxStok) next = maxStok;

  displayInput.value = next;
  if (hiddenInput) hiddenInput.value = next;
};

window.tambahKeranjangAnimasi = async function(btn, event) {
  if (event) {
    event.preventDefault();
    event.stopPropagation();
  }

  if (btn.classList.contains('is-loading') || btn.classList.contains('is-success')) {
    return;
  }

  const card = btn.closest('.product-card') || btn.closest('.favorit-card') || btn.closest('.product-modal');
  if (!card) return;
  const form = btn.closest('form') || card.querySelector('form');
  if (!form) return;

  const imgEl = card.querySelector('img');

  // Find target cart in viewport
  const isMobile = window.innerWidth <= 768;
  const desktopCart = document.querySelector('.nav-right .icon-circle-btn[href*="keranjang"]');
  const mobileCart = document.querySelector('.mobile-bottom-nav a[href*="keranjang"]');
  const targetCart = (isMobile && mobileCart) ? mobileCart : (desktopCart || mobileCart);

  // 1. Enter Loading State
  btn.classList.add('is-loading');
  const overlayBtn = btn.closest('.product-img-overlay-btn');
  if (overlayBtn) overlayBtn.classList.add('active-state');
  const originalHtml = btn.innerHTML;
  btn.innerHTML = `<span class="cart-btn-spinner"></span>`;

  // 2. Launch Flying Ghost Image
  if (imgEl && targetCart) {
    spawnFlyToCart(imgEl, targetCart);
  }

  // 3. Send AJAX Request to existing route
  try {
    const formData = new FormData(form);
    formData.set('langsung', '0');

    const qty = parseInt(formData.get('qty') || '1', 10);

    await fetch(form.action, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'text/html,application/xhtml+xml,application/xml'
      }
    });

    // 4. Update cart badges instantly
    updateAllCartBadges(qty);

    // 5. Success state on button
    btn.classList.remove('is-loading');
    btn.classList.add('is-success');
    btn.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;"><polyline points="20 6 9 17 4 12"></polyline></svg> Ditambahkan`;

    // 6. Reset button after 1.8s
    setTimeout(() => {
      btn.classList.remove('is-success');
      btn.innerHTML = originalHtml;
      if (overlayBtn) overlayBtn.classList.remove('active-state');
    }, 1800);

  } catch (err) {
    console.error('Gagal menambahkan ke keranjang:', err);
    btn.classList.remove('is-loading');
    btn.innerHTML = originalHtml;
    if (overlayBtn) overlayBtn.classList.remove('active-state');
  }
};

function spawnFlyToCart(sourceEl, targetCart) {
  const sourceRect = sourceEl.getBoundingClientRect();
  const cartRect = targetCart.getBoundingClientRect();

  const flyer = document.createElement('div');
  flyer.className = 'omyu-cart-flyer';

  const img = document.createElement('img');
  img.src = sourceEl.src;
  img.alt = 'Flying Item';
  flyer.appendChild(img);

  const startSize = Math.min(Math.max(sourceRect.width * 0.5, 60), 90);
  const startX = sourceRect.left + (sourceRect.width / 2) - (startSize / 2);
  const startY = sourceRect.top + (sourceRect.height / 2) - (startSize / 2);

  flyer.style.width = `${startSize}px`;
  flyer.style.height = `${startSize}px`;
  flyer.style.left = `${startX}px`;
  flyer.style.top = `${startY}px`;

  document.body.appendChild(flyer);

  const endX = cartRect.left + (cartRect.width / 2) - 18;
  const endY = cartRect.top + (cartRect.height / 2) - 18;

  const deltaX = endX - startX;
  const deltaY = endY - startY;

  // Parabolic flight: rise up at mid-flight
  const midDeltaX = deltaX * 0.45;
  const midDeltaY = Math.min(0, deltaY) - 60;

  const anim = flyer.animate([
    {
      transform: 'translate3d(0, 0, 0) scale(1) rotate(0deg)',
      opacity: 1
    },
    {
      offset: 0.45,
      transform: `translate3d(${midDeltaX}px, ${midDeltaY}px, 0) scale(0.8) rotate(-12deg)`,
      opacity: 0.95
    },
    {
      transform: `translate3d(${deltaX}px, ${deltaY}px, 0) scale(0.2) rotate(18deg)`,
      opacity: 0.15
    }
  ], {
    duration: 650,
    easing: 'cubic-bezier(0.2, 0.85, 0.35, 1)',
    fill: 'forwards'
  });

  anim.onfinish = () => {
    flyer.remove();
    triggerCartBounce(targetCart);
  };
}

function triggerCartBounce(cartEl) {
  if (!cartEl) return;
  cartEl.classList.remove('cart-bounce-active');
  void cartEl.offsetWidth;
  cartEl.classList.add('cart-bounce-active');

  const badges = document.querySelectorAll('.cart-red-badge, .mobile-cart-badge, .cart-banner-badge');
  badges.forEach(b => {
    b.classList.remove('badge-pop-active');
    void b.offsetWidth;
    b.classList.add('badge-pop-active');
  });
}

function updateAllCartBadges(qtyAdded) {
  const badges = document.querySelectorAll('.cart-red-badge, .mobile-cart-badge, .cart-banner-badge');
  badges.forEach(badge => {
    let current = parseInt(badge.textContent.trim(), 10);
    if (isNaN(current)) current = 0;
    const next = current + qtyAdded;
    badge.textContent = next;
    badge.style.display = next > 0 ? 'inline-flex' : 'none';
  });
}
