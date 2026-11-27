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

/**
 * ==========================================================================
 * OMYU Modern Confirmation Modal & Global Interceptor
 * ==========================================================================
 */
let pendingStafConfirmCallback = null;

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

  // Extract order code from text if not provided
  if (!kodePesanan && pesan) {
    const matchCode = pesan.match(/#([A-Z0-9\-]+)/i);
    if (matchCode) {
      kodePesanan = matchCode[0];
    }
  }

  const lowerPesan = (pesan || '').toLowerCase();

  // Smart type detection
  if (!tipe) {
    if (lowerPesan.includes('tolak') || lowerPesan.includes('batal') || lowerPesan.includes('hapus') || lowerPesan.includes('nonaktif') || lowerPesan.includes('keluar')) {
      tipe = 'danger';
    } else if (lowerPesan.includes('ubah') || lowerPesan.includes('edit') || lowerPesan.includes('perbarui') || lowerPesan.includes('simpan')) {
      tipe = 'warning';
    } else {
      tipe = 'success';
    }
  }

  // Smart title detection
  if (!judul) {
    if (lowerPesan.includes('tolak')) {
      judul = 'Tolak Pembayaran?';
    } else if (lowerPesan.includes('batal')) {
      judul = 'Batalkan Pesanan?';
    } else if (lowerPesan.includes('diverifikasi') || lowerPesan.includes('verifikasi')) {
      judul = 'Verifikasi Pembayaran?';
    } else if (lowerPesan.includes('menutup') && lowerPesan.includes('racikan')) {
      judul = 'Tutup Racikan?';
    } else if (lowerPesan.includes('serahkan')) {
      judul = 'Serahkan Pesanan?';
    } else if (lowerPesan.includes('menutup') && lowerPesan.includes('pesanan')) {
      judul = 'Tutup Pesanan?';
    } else if (lowerPesan.includes('mengaktifkan') && lowerPesan.includes('pesanan')) {
      judul = 'Aktifkan Pesanan?';
    } else if (lowerPesan.includes('keluar')) {
      judul = 'Keluar dari Akun?';
    } else if (lowerPesan.includes('nonaktif')) {
      judul = 'Nonaktifkan Akun?';
    } else if (lowerPesan.includes('aktifkan')) {
      judul = 'Aktifkan Pesanan?';
    } else if (lowerPesan.includes('konfirmasi')) {
      judul = 'Konfirmasi Pesanan?';
    } else if (lowerPesan.includes('ubah') || lowerPesan.includes('edit') || lowerPesan.includes('perbarui') || lowerPesan.includes('simpan')) {
      judul = 'Simpan Perubahan?';
    } else {
      judul = 'Konfirmasi Tindakan';
    }
  }

  // Smart confirm button text detection
  if (!teksKonfirmasi) {
    if (lowerPesan.includes('tolak')) {
      teksKonfirmasi = 'Ya, Tolak';
    } else if (lowerPesan.includes('batal')) {
      teksKonfirmasi = 'Ya, Batalkan';
    } else if (lowerPesan.includes('diverifikasi') || lowerPesan.includes('verifikasi')) {
      teksKonfirmasi = 'Ya, Verifikasi';
    } else if (lowerPesan.includes('serahkan')) {
      teksKonfirmasi = 'Ya, Serahkan';
    } else if (lowerPesan.includes('keluar')) {
      teksKonfirmasi = 'Ya, Keluar';
    } else if (lowerPesan.includes('mengaktifkan') || lowerPesan.includes('aktifkan')) {
      teksKonfirmasi = 'Ya, Aktifkan';
    } else if (lowerPesan.includes('menutup')) {
      teksKonfirmasi = 'Ya, Tutup';
    } else if (lowerPesan.includes('konfirmasi')) {
      teksKonfirmasi = 'Ya, Konfirmasi';
    } else if (lowerPesan.includes('ubah') || lowerPesan.includes('edit') || lowerPesan.includes('perbarui') || lowerPesan.includes('simpan')) {
      teksKonfirmasi = 'Ya, Simpan';
    } else {
      teksKonfirmasi = 'Ya, Lanjutkan';
    }
  }

  // Set theme classes
  modal.classList.remove('theme-success', 'theme-danger', 'theme-warning');
  modal.classList.add('theme-' + tipe);

  // Set SVG Icon
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

  // Set contents
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

  pendingStafConfirmCallback = onConfirm;

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
  pendingStafConfirmCallback = null;
};

// Handle submit action in modal. Delegated so the button keeps working even if
// the modal element is recreated by another script.
document.addEventListener('click', function(e) {
  const submitBtn = e.target.closest('#omyuConfirmBtnSubmit');
  if (!submitBtn) return;

  if (typeof pendingStafConfirmCallback === 'function') {
    const cb = pendingStafConfirmCallback;
    pendingStafConfirmCallback = null;
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

// Global capture interceptor for all confirmation dialogs
document.addEventListener('click', function(e) {
  const trigger = e.target.closest('button, a, input[type="submit"]');
  if (!trigger) return;

  // Don't intercept clicks inside the confirmation modal itself
  if (trigger.closest('#omyuConfirmModal')) return;

  const form = trigger.closest('form');
  if (form && form.dataset.confirmed === 'true') {
    return; // Already confirmed, proceed normally
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
    const inputStatus = form ? form.querySelector('input[name="status_pesanan"]') : null;
    const formStatus = inputStatus ? inputStatus.value : '';

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

        // Jika form sempat diganti pembaruan otomatis, cari form yang masih aktif.
        if (!formTujuan && formAction) {
          const kandidat = Array.prototype.slice.call(document.querySelectorAll('form'))
            .filter((item) => item.getAttribute('action') === formAction);
          const statusSama = kandidat.find((item) => {
            const statusEl = item.querySelector('input[name="status_pesanan"]');

            return !formStatus || (statusEl && statusEl.value === formStatus);
          });
          formTujuan = statusSama || kandidat[0] || null;
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
}, true); // Use capture phase to run before inline onclick!

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
