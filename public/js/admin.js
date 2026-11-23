/**
 * OMYU Admin V35 Interactive Animations
 * Handles Number Counting, Stagger, Sparklines, and Micro-interactions
 */

document.addEventListener('DOMContentLoaded', () => {
  initScrollReveal();
  initNumberCounters();
  initStaggerAnimations();
  initSparklineDraw();
  initMobileBottomBar();
});

/**
 * Format number with Indonesian thousand separators (dot)
 */
function formatIndonesianNumber(num) {
  return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

/**
 * Animate single counter element smoothly (Framer-motion style easing)
 */
function animateSingleCounter(el) {
  if (el.dataset.counterAnimated === 'true') return;
  el.dataset.counterAnimated = 'true';

  let target = parseFloat(el.getAttribute('data-counter-target'));
  if (isNaN(target)) {
    const text = el.innerText || '';
    const cleaned = text.replace(/[^0-9]/g, '');
    target = parseFloat(cleaned) || 0;
  }

  const prefix = el.getAttribute('data-counter-prefix') || '';
  const suffix = el.getAttribute('data-counter-suffix') || '';
  const duration = parseFloat(el.getAttribute('data-counter-duration')) || 900; // ms
  const delay = parseFloat(el.getAttribute('data-counter-delay')) || 80; // ms

  const easeOutExpo = (t) => (t === 1 ? 1 : 1 - Math.pow(2, -10 * t));

  const timer = setTimeout(() => {
    const startTime = performance.now();

    function update(currentTime) {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);
      const currentVal = target * easeOutExpo(progress);

      el.innerText = `${prefix}${formatIndonesianNumber(currentVal)}${suffix}`;

      if (progress < 1) {
        el.dataset.counterFrame = String(requestAnimationFrame(update));
      } else {
        el.innerText = `${prefix}${formatIndonesianNumber(target)}${suffix}`;
        delete el.dataset.counterFrame;
      }
    }

    el.dataset.counterFrame = String(requestAnimationFrame(update));
  }, delay);
  el.dataset.counterTimer = String(timer);
}

/**
 * Animate all counters on the page (triggered when visible)
 */
function initNumberCounters() {
  const counterElements = document.querySelectorAll('[data-counter]');
  counterElements.forEach((el) => {
    observeReveal(el, () => animateSingleCounter(el), {
      threshold: 0.2,
      onLeave: () => resetCounter(el),
    });
  });
}

/**
 * Automatically assign staggered entry animation to container children
 */
function initStaggerAnimations() {
  const staggerContainers = document.querySelectorAll('[data-stagger-container]');

  staggerContainers.forEach((container) => {
    const children = Array.from(container.children);
    const baseDelay = parseFloat(container.getAttribute('data-stagger-base')) || 0.04;
    const step = parseFloat(container.getAttribute('data-stagger-step')) || 0.06;

    children.forEach((child, index) => {
      if (!child.classList.contains('anim-fade-up') && !child.classList.contains('anim-fade-scale')) {
        child.classList.add('anim-fade-up');
        const calculatedDelay = (baseDelay + index * step).toFixed(2);
        child.style.animationDelay = `${calculatedDelay}s`;
      }
    });
  });
}

/**
 * Draw single SVG sparkline
 */
function drawSingleSparkline(path) {
  try {
    const length = path.getTotalLength();
    path.style.transition = 'none';
    path.style.strokeDasharray = length;
    path.style.strokeDashoffset = length;
    path.getBoundingClientRect();
    path.style.transition = 'stroke-dashoffset 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.1s';
    path.style.strokeDashoffset = '0';
  } catch (e) {
    // Fallback
  }
}

function resetSparkline(path) {
  try {
    const length = path.getTotalLength();
    path.style.transition = 'none';
    path.style.strokeDasharray = length;
    path.style.strokeDashoffset = length;
  } catch (e) {
    // Fallback
  }
}

function resetCounter(el) {
  if (el.dataset.counterTimer) {
    clearTimeout(Number(el.dataset.counterTimer));
    delete el.dataset.counterTimer;
  }
  if (el.dataset.counterFrame) {
    cancelAnimationFrame(Number(el.dataset.counterFrame));
    delete el.dataset.counterFrame;
  }
  el.dataset.counterAnimated = 'false';
  const prefix = el.getAttribute('data-counter-prefix') || '';
  const suffix = el.getAttribute('data-counter-suffix') || '';
  el.innerText = `${prefix}0${suffix}`;
}

function replayRevealAnimation(el) {
  el.classList.remove('reveal-pending');
  // Restart CSS animation so it can replay on re-entry.
  el.style.animation = 'none';
  // Force reflow
  void el.offsetHeight;
  el.style.animation = '';
}

/**
 * Draw SVG sparklines dynamically if present (triggered when visible)
 */
function initSparklineDraw() {
  const visibleSparklines = document.querySelectorAll('.sparkline-path');
  visibleSparklines.forEach((path) => {
    const trigger = path.closest('.glass-card, .admin-card, svg') || path;
    observeReveal(trigger, () => drawSingleSparkline(path), {
      threshold: 0.15,
      onLeave: () => resetSparkline(path),
    });
  });
}

/**
 * Observe an element and run callbacks when it enters/leaves viewport.
 */
function observeReveal(el, onEnter, opts = {}) {
  if (!el || el.dataset.revealBound === 'true') return;
  el.dataset.revealBound = 'true';

  const threshold = opts.threshold ?? 0.12;
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
 * Reveal fade-up/down/scale animations on scroll.
 */
function initScrollReveal() {
  const selector = '.anim-fade-up, .anim-fade-down, .anim-fade-scale';
  const elements = document.querySelectorAll(selector);

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  elements.forEach((el) => {
    if (el.dataset.scrollRevealReady === 'true') return;
    el.dataset.scrollRevealReady = 'true';

    if (reduceMotion) {
      el.style.opacity = '1';
      el.style.transform = 'none';
      el.style.filter = 'none';
      el.style.animation = 'none';
      return;
    }

    el.classList.add('reveal-pending');

    observeReveal(el, () => {
      replayRevealAnimation(el);
      el.classList.add('is-revealed');
    }, {
      threshold: 0.08,
      onLeave: () => {
        el.classList.add('reveal-pending');
        el.classList.remove('is-revealed');
      },
    });
  });
}

/**
 * Ensure active mobile bottom tab is scrolled into view smoothly
 */
function initMobileBottomBar() {
  const activeTab = document.querySelector('.admin-bottom-tab.active');
  const scrollContainer = document.querySelector('.admin-bottom-scroll');
  if (activeTab && scrollContainer) {
    setTimeout(() => {
      activeTab.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }, 150);
  }
}
