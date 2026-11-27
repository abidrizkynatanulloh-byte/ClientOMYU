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

/**
 * ==========================================================================
 * OMYU Modern Confirmation Modal & Global Interceptor (Admin)
 * ==========================================================================
 */
let pendingAdminConfirmCallback = null;

window.bukaKonfirmasiModal = function(opts) {
  const modal = document.getElementById('omyuConfirmModal');
  if (!modal) return;

  let {
    judul = '',
    pesan = '',
    kodePesanan = '',
    teksKonfirmasi = '',
    teksBatal = 'Batal',
    tipe = '',
    onConfirm = null
  } = opts || {};

  if (!kodePesanan && pesan) {
    const matchCode = pesan.match(/#([A-Z0-9\-]+)/i);
    if (matchCode) {
      kodePesanan = matchCode[0];
    }
  }

  const lowerPesan = (pesan || '').toLowerCase();

  if (!tipe) {
    if (lowerPesan.includes('tolak') || lowerPesan.includes('batal') || lowerPesan.includes('hapus') || lowerPesan.includes('nonaktif') || lowerPesan.includes('keluar')) {
      tipe = 'danger';
    } else if (lowerPesan.includes('ubah') || lowerPesan.includes('edit') || lowerPesan.includes('perbarui') || lowerPesan.includes('simpan')) {
      tipe = 'warning';
    } else {
      tipe = 'success';
    }
  }

  if (!judul) {
    if (lowerPesan.includes('keluar')) {
      judul = 'Keluar dari Akun Admin?';
    } else if (lowerPesan.includes('nonaktifkan')) {
      judul = 'Nonaktifkan Akun / Data?';
    } else if (lowerPesan.includes('mengaktifkan') || lowerPesan.includes('aktifkan')) {
      judul = 'Aktifkan Akun / Data?';
    } else if (lowerPesan.includes('hapus')) {
      judul = 'Hapus Data?';
    } else if (lowerPesan.includes('konfirmasi')) {
      judul = 'Konfirmasi Pesanan?';
    } else if (lowerPesan.includes('ubah') || lowerPesan.includes('edit') || lowerPesan.includes('perbarui') || lowerPesan.includes('simpan')) {
      judul = 'Simpan Perubahan?';
    } else {
      judul = 'Konfirmasi Tindakan';
    }
  }

  if (!teksKonfirmasi) {
    if (lowerPesan.includes('keluar')) {
      teksKonfirmasi = 'Ya, Keluar';
    } else if (lowerPesan.includes('nonaktifkan')) {
      teksKonfirmasi = 'Ya, Nonaktifkan';
    } else if (lowerPesan.includes('mengaktifkan') || lowerPesan.includes('aktifkan')) {
      teksKonfirmasi = 'Ya, Aktifkan';
    } else if (lowerPesan.includes('hapus')) {
      teksKonfirmasi = 'Ya, Hapus';
    } else if (lowerPesan.includes('konfirmasi')) {
      teksKonfirmasi = 'Ya, Konfirmasi';
    } else if (lowerPesan.includes('ubah') || lowerPesan.includes('edit') || lowerPesan.includes('perbarui') || lowerPesan.includes('simpan')) {
      teksKonfirmasi = 'Ya, Simpan';
    } else {
      teksKonfirmasi = 'Ya, Lanjutkan';
    }
  }

  modal.classList.remove('theme-success', 'theme-danger', 'theme-warning');
  modal.classList.add('theme-' + tipe);

  const svgEl = document.getElementById('omyuConfirmIconSvg');
  if (svgEl) {
    if (tipe === 'danger') {
      svgEl.innerHTML = '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>';
    } else if (tipe === 'warning') {
      svgEl.innerHTML = '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line>';
    } else {
      svgEl.innerHTML = '<path d="M20 6L9 17l-5-5"/>';
    }
  }

  const titleEl = document.getElementById('omyuConfirmTitle');
  if (titleEl) titleEl.textContent = judul;

  const msgEl = document.getElementById('omyuConfirmMessage');
  if (msgEl) msgEl.textContent = pesan;

  const submitTextEl = document.getElementById('omyuConfirmBtnSubmitText');
  if (submitTextEl) submitTextEl.textContent = teksKonfirmasi;

  const cancelBtn = document.getElementById('omyuConfirmBtnCancel');
  if (cancelBtn) cancelBtn.textContent = teksBatal;

  const badgeEl = document.getElementById('omyuConfirmCodeBadge');
  const badgeTextEl = document.getElementById('omyuConfirmCodeText');
  if (kodePesanan && badgeEl && badgeTextEl) {
    badgeTextEl.textContent = kodePesanan;
    badgeEl.style.display = 'inline-flex';
  } else if (badgeEl) {
    badgeEl.style.display = 'none';
  }

  const submitBtn = document.getElementById('omyuConfirmBtnSubmit');
  if (submitBtn) {
    submitBtn.classList.remove('loading');
    submitBtn.disabled = false;
  }

  pendingAdminConfirmCallback = onConfirm;

  modal.classList.add('active');
  modal.setAttribute('aria-hidden', 'false');

  setTimeout(() => {
    if (submitBtn) submitBtn.focus();
  }, 60);
};

window.tutupKonfirmasiModal = function() {
  const modal = document.getElementById('omyuConfirmModal');
  if (!modal) return;
  modal.classList.remove('active');
  modal.setAttribute('aria-hidden', 'true');
  pendingAdminConfirmCallback = null;
};

// Handle submit action in modal. Delegated so the button keeps working even if
// the modal element is recreated by another script.
document.addEventListener('click', function(e) {
  const submitBtn = e.target.closest('#omyuConfirmBtnSubmit');
  if (!submitBtn) return;

  if (typeof pendingAdminConfirmCallback === 'function') {
    const cb = pendingAdminConfirmCallback;
    pendingAdminConfirmCallback = null;
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    cb();
  } else {
    window.tutupKonfirmasiModal();
  }
});

document.addEventListener('keydown', function(e) {
  const modal = document.getElementById('omyuConfirmModal');
  if (!modal || !modal.classList.contains('active')) return;
  if (e.key === 'Escape') {
    window.tutupKonfirmasiModal();
  }
});

document.addEventListener('click', function(e) {
  const trigger = e.target.closest('button, a, input[type="submit"]');
  if (!trigger) return;

  if (trigger.closest('#omyuConfirmModal')) return;

  const form = trigger.closest('form');
  if (form && form.dataset.confirmed === 'true') {
    return;
  }

  const customConfirm = trigger.dataset.confirm;
  const onclickAttr = trigger.getAttribute('onclick') || '';
  const hasInlineConfirm = onclickAttr.includes('confirm(');

  if (customConfirm || hasInlineConfirm) {
    e.preventDefault();
    e.stopPropagation();

    let pesan = customConfirm;
    if (!pesan && hasInlineConfirm) {
      const match = onclickAttr.match(/confirm\s*\(\s*(['"`])(.*?)\1\s*\)/);
      if (match) {
        pesan = match[2];
      }
    }

    const judul = trigger.dataset.confirmTitle || '';
    const kodePesanan = trigger.dataset.confirmCode || '';
    const teksKonfirmasi = trigger.dataset.confirmBtn || '';
    const tipe = trigger.dataset.confirmType || '';

    const linkTujuan = trigger.tagName.toLowerCase() === 'a' ? trigger.href : '';
    const formAction = form ? form.getAttribute('action') : '';
    const formMethod = form ? form.getAttribute('method') : '';
    const triggerConfirm = trigger.dataset.confirm || '';

    window.bukaKonfirmasiModal({
      judul,
      pesan,
      kodePesanan,
      teksKonfirmasi,
      tipe,
      onConfirm: () => {
        if (linkTujuan) {
          window.location.href = linkTujuan;
          return;
        }

        let formTujuan = form && form.isConnected ? form : null;

        if (!formTujuan && formAction) {
          const kandidat = Array.prototype.slice.call(document.querySelectorAll('form'))
            .filter((item) => item.getAttribute('action') === formAction &&
              (!formMethod || item.getAttribute('method') === formMethod));
          const sama = kandidat.find((item) => {
            const tombol = item.querySelector('[data-confirm]');

            return !triggerConfirm || (tombol && tombol.dataset.confirm === triggerConfirm);
          });
          formTujuan = sama || kandidat[0] || null;
        }

        if (!formTujuan) {
          window.tutupKonfirmasiModal();
          return;
        }

        formTujuan.dataset.confirmed = 'true';

        let triggerTujuan = trigger && trigger.isConnected && formTujuan.contains(trigger)
          ? trigger
          : formTujuan.querySelector('button[type="submit"], input[type="submit"]');

        if (trigger.name && trigger.value && triggerTujuan !== trigger) {
          const hidden = document.createElement('input');
          hidden.type = 'hidden';
          hidden.name = trigger.name;
          hidden.value = trigger.value;
          formTujuan.appendChild(hidden);
        }

        try {
          if (typeof formTujuan.requestSubmit === 'function') {
            if (triggerTujuan) {
              formTujuan.requestSubmit(triggerTujuan);
            } else {
              formTujuan.requestSubmit();
            }
          } else {
            formTujuan.submit();
          }
        } catch (error) {
          formTujuan.submit();
        }
      }
    });
  }
}, true);

document.addEventListener('submit', function(e) {
  const form = e.target;
  if (form.dataset.confirmed === 'true') {
    return;
  }
  const onsubmitAttr = form.getAttribute('onsubmit') || '';
  const customConfirm = form.dataset.confirm;
  if (customConfirm || onsubmitAttr.includes('confirm(')) {
    e.preventDefault();
    e.stopPropagation();

    let pesan = customConfirm;
    if (!pesan && onsubmitAttr.includes('confirm(')) {
      const match = onsubmitAttr.match(/confirm\s*\(\s*(['"`])(.*?)\1\s*\)/);
      if (match) pesan = match[2];
    }

    const judul = form.dataset.confirmTitle || '';
    const tipe = form.dataset.confirmType || '';
    const teksKonfirmasi = form.dataset.confirmBtn || '';
    const formAction = form.getAttribute('action') || '';
    const formMethod = form.getAttribute('method') || '';

    window.bukaKonfirmasiModal({
      judul,
      pesan,
      teksKonfirmasi,
      tipe,
      onConfirm: () => {
        let formTujuan = form.isConnected ? form : null;

        if (!formTujuan && formAction) {
          const kandidat = Array.prototype.slice.call(document.querySelectorAll('form'))
            .filter((item) => item.getAttribute('action') === formAction &&
              (!formMethod || item.getAttribute('method') === formMethod));
          const sama = kandidat.find((item) => (item.dataset.confirm || '') === pesan);

          formTujuan = sama || kandidat[0] || null;
        }

        if (!formTujuan) {
          window.tutupKonfirmasiModal();
          return;
        }

        formTujuan.dataset.confirmed = 'true';
        formTujuan.submit();
      }
    });
  }
}, true);
