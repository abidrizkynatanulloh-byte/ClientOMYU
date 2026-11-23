/**
 * OMYU Staf V35 Interactive Animations
 * Handles Number Counting, Micro-interactions, and Smooth UI Dynamics
 */

document.addEventListener('DOMContentLoaded', () => {
  initStafScrollReveal();
  initStafNumberCounters();
  initStafQuickInteractions();
});

/**
 * Format number with Indonesian thousand separators (dot)
 */
function formatStafNumber(num, decimals = 0) {
  if (decimals > 0) {
    const parts = num.toFixed(decimals).split('.');
    const integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    return `${integerPart},${parts[1]}`;
  }
  return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

/**
 * Observe an element and run callbacks when it enters/leaves viewport.
 */
function observeStafReveal(el, onEnter, opts = {}) {
  if (!el || el.dataset.stafRevealBound === 'true') return;
  el.dataset.stafRevealBound = 'true';

  const threshold = opts.threshold ?? 0.1;
  const rootMargin = opts.rootMargin ?? '0px 0px -10% 0px';
  const once = opts.once ?? false;
  const onLeave = opts.onLeave;

  if (!('IntersectionObserver' in window)) {
    onEnter(el);
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          onEnter(entry.target);
          if (once) observer.unobserve(entry.target);
        } else if (typeof onLeave === 'function') {
          onLeave(entry.target);
        }
      });
    },
    { threshold, rootMargin }
  );

  observer.observe(el);
}

/**
 * Reveal staf cards/widgets on scroll.
 */
function initStafScrollReveal() {
  const selector = [
    '.dash-hero',
    '.stat-card',
    '.dash-card',
    '.quick-link',
    '.queue-card',
    '.stok-cell',
    '.card',
    '.bahan-card',
    '.order-card',
    '.prod-card',
    '.metric',
    '.metric-card',
    '.menu-card',
    '.bantuan-card',
    '.detail-card',
    '.log-card',
    '.struk-card',
    '.scan-card',
    '.staf-anim-card',
  ].join(', ');

  const elements = document.querySelectorAll(selector);
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  elements.forEach((el) => {
    if (el.dataset.stafScrollRevealReady === 'true') return;
    el.dataset.stafScrollRevealReady = 'true';

    if (reduceMotion) {
      el.style.opacity = '1';
      el.style.transform = 'none';
      el.style.animation = 'none';
      return;
    }

    el.classList.add('staf-reveal-pending');
    observeStafReveal(el, () => {
      replayStafRevealAnimation(el);
    }, {
      threshold: 0.08,
      onLeave: () => {
        el.classList.add('staf-reveal-pending');
      },
    });
  });
}

/**
 * Animate numbers counting up smoothly (Framer-motion style easing)
 */
function initStafNumberCounters() {
  // Target numbers inside stat-num, hero-count strong, stok-cell strong, metric strong, and stok-utama
  const counterElements = document.querySelectorAll(
    '.stat-num, .dash-hero-count strong, .stok-cell strong, .metric strong, .stok-utama, [data-counter]'
  );

  counterElements.forEach((el) => {
    const trigger = el.closest('.stat-card, .dash-hero, .stok-cell, .metric, .card, .staf-anim-card') || el;
    observeStafReveal(trigger, () => animateStafCounter(el), {
      threshold: 0.2,
      onLeave: () => resetStafCounter(el),
    });
  });
}

function animateStafCounter(el) {
  if (el.dataset.counterAnimated === 'true') return;
  el.dataset.counterAnimated = 'true';

  // Check if element has small tag for unit (e.g. <small>g</small> or <small>pcs</small>)
  const smallUnit = el.querySelector('small');
  const unitHtml = smallUnit ? smallUnit.outerHTML : '';

  let textContent = el.innerText || '';
  if (smallUnit) {
    textContent = textContent.replace(smallUnit.innerText, '').trim();
  }

  // Determine target value
  let target = parseFloat(el.getAttribute('data-counter-target'));
  let decimalPlaces = 0;

  if (isNaN(target)) {
    // Clean string (e.g., "4.900,00" -> "4900.00")
    if (textContent.includes(',')) {
      const normalized = textContent.replace(/\./g, '').replace(',', '.');
      target = parseFloat(normalized) || 0;
      decimalPlaces = 2;
    } else {
      const cleaned = textContent.replace(/[^0-9]/g, '');
      target = parseFloat(cleaned) || 0;
    }
  }

  if (target === 0) return; // Skip if 0

  const prefix = el.getAttribute('data-counter-prefix') || '';
  const suffix = el.getAttribute('data-counter-suffix') || '';
  const duration = parseFloat(el.getAttribute('data-counter-duration')) || 850; // ms
  const delay = parseFloat(el.getAttribute('data-counter-delay')) || 80; // ms

  const easeOutExpo = (t) => (t === 1 ? 1 : 1 - Math.pow(2, -10 * t));

  const timer = setTimeout(() => {
    const startTime = performance.now();

    function update(currentTime) {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);
      const currentVal = target * easeOutExpo(progress);

      const formatted = formatStafNumber(currentVal, decimalPlaces);
      el.innerHTML = `${prefix}${formatted}${suffix} ${unitHtml}`.trim();

      if (progress < 1) {
        el.dataset.counterFrame = String(requestAnimationFrame(update));
      } else {
        const finalFormatted = formatStafNumber(target, decimalPlaces);
        el.innerHTML = `${prefix}${finalFormatted}${suffix} ${unitHtml}`.trim();
        delete el.dataset.counterFrame;
      }
    }

    el.dataset.counterFrame = String(requestAnimationFrame(update));
  }, delay);
  el.dataset.counterTimer = String(timer);
}

function resetStafCounter(el) {
  if (el.dataset.counterTimer) {
    clearTimeout(Number(el.dataset.counterTimer));
    delete el.dataset.counterTimer;
  }
  if (el.dataset.counterFrame) {
    cancelAnimationFrame(Number(el.dataset.counterFrame));
    delete el.dataset.counterFrame;
  }
  el.dataset.counterAnimated = 'false';

  const smallUnit = el.querySelector('small');
  const unitHtml = smallUnit ? smallUnit.outerHTML : '';
  const prefix = el.getAttribute('data-counter-prefix') || '';
  const suffix = el.getAttribute('data-counter-suffix') || '';
  el.innerHTML = `${prefix}0${suffix} ${unitHtml}`.trim();
}

function replayStafRevealAnimation(el) {
  el.classList.remove('staf-reveal-pending');
  el.style.animation = 'none';
  void el.offsetHeight;
  el.style.animation = '';
}

/**
 * Additional interactive flourishes
 */
function initStafQuickInteractions() {
  // Smooth scroll active bottom nav tab if overflowed
  const activeTab = document.querySelector('.bnav-tab.active');
  const navContainer = document.querySelector('.bottom-nav');
  if (activeTab && navContainer && navContainer.scrollWidth > navContainer.clientWidth) {
    activeTab.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
  }
}
