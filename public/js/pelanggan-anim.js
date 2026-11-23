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
