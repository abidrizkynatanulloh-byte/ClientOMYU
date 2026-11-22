/**
 * OMYU Admin V35 Interactive Animations
 * Handles Number Counting, Stagger, Sparklines, and Micro-interactions
 */

document.addEventListener('DOMContentLoaded', () => {
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

  setTimeout(() => {
    const startTime = performance.now();

    function update(currentTime) {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);
      const currentVal = target * easeOutExpo(progress);

      el.innerText = `${prefix}${formatIndonesianNumber(currentVal)}${suffix}`;

      if (progress < 1) {
        requestAnimationFrame(update);
      } else {
        el.innerText = `${prefix}${formatIndonesianNumber(target)}${suffix}`;
      }
    }

    requestAnimationFrame(update);
  }, delay);
}

/**
 * Animate all counters on the page
 */
function initNumberCounters() {
  const counterElements = document.querySelectorAll('[data-counter]');
  counterElements.forEach(animateSingleCounter);
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
  if (path.dataset.sparklineAnimated === 'true') return;
  path.dataset.sparklineAnimated = 'true';
  try {
    const length = path.getTotalLength();
    path.style.strokeDasharray = length;
    path.style.strokeDashoffset = length;
    path.getBoundingClientRect();
    path.style.transition = 'stroke-dashoffset 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.1s';
    path.style.strokeDashoffset = '0';
  } catch (e) {
    // Fallback
  }
}

/**
 * Draw SVG sparklines dynamically if present
 */
function initSparklineDraw() {
  const visibleSparklines = document.querySelectorAll('.sparkline-path');
  visibleSparklines.forEach(drawSingleSparkline);
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
