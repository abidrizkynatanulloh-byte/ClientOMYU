/**
 * OMYU Staf V35 Interactive Animations
 * Handles Number Counting, Micro-interactions, and Smooth UI Dynamics
 */

document.addEventListener('DOMContentLoaded', () => {
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
 * Animate numbers counting up smoothly (Framer-motion style easing)
 */
function initStafNumberCounters() {
  // Target numbers inside stat-num, hero-count strong, stok-cell strong, metric strong, and stok-utama
  const counterElements = document.querySelectorAll(
    '.stat-num, .dash-hero-count strong, .stok-cell strong, .metric strong, .stok-utama, [data-counter]'
  );

  counterElements.forEach((el) => {
    // Check if element has small tag for unit (e.g. <small>g</small> or <small>pcs</small>)
    const smallUnit = el.querySelector('small');
    const unitHtml = smallUnit ? smallUnit.outerHTML : '';

    let textContent = el.innerText || '';
    if (smallUnit) {
      textContent = textContent.replace(smallUnit.innerText, '').trim();
    }

    // Determine target value
    let target = parseFloat(el.getAttribute('data-counter-target'));
    let hasDecimals = false;
    let decimalPlaces = 0;

    if (isNaN(target)) {
      // Clean string (e.g., "4.900,00" -> "4900.00")
      if (textContent.includes(',')) {
        hasDecimals = true;
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

    setTimeout(() => {
      const startTime = performance.now();

      function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const currentVal = target * easeOutExpo(progress);

        const formatted = formatStafNumber(currentVal, decimalPlaces);
        el.innerHTML = `${prefix}${formatted}${suffix} ${unitHtml}`.trim();

        if (progress < 1) {
          requestAnimationFrame(update);
        } else {
          const finalFormatted = formatStafNumber(target, decimalPlaces);
          el.innerHTML = `${prefix}${finalFormatted}${suffix} ${unitHtml}`.trim();
        }
      }

      requestAnimationFrame(update);
    }, delay);
  });
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
