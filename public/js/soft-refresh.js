/*
 * OMYU — pembaruan halaman otomatis tanpa reload penuh.
 * Halaman diambil ulang setiap 3 detik, lalu hanya bagian yang berubah yang
 * disamakan ke DOM yang sudah ada. Fokus, input, scroll, dan modal tidak reset.
 */
(function () {
    'use strict';

    // Halaman tertentu (misalnya scan QR) bisa mematikan pembaruan otomatis
    // sebelum skrip ini dimuat agar scanner/results tidak ikut ter-reset.
    if (window.OMYU_SOFT_REFRESH === false) {
        return;
    }

    var REFRESH_MS = 3000;
    var sedangMuat = false;
    var terakhirDiambil = '';

    var ELEMENT_NODE = 1;
    var TEXT_NODE = 3;
    var TAG_DILEWATI = ['SCRIPT', 'STYLE', 'NOSCRIPT', 'IFRAME', 'CANVAS', 'VIDEO', 'AUDIO'];

    function dalamFormTerkunci(elemen) {
        var form = elemen.closest ? elemen.closest('form') : null;

        return !!form && (
            form.contains(document.activeElement) ||
            form.hasAttribute('data-soft-refresh-skip')
        );
    }

    function dalamModalTerbuka(elemen) {
        var pencocok = elemen.closest ? elemen.closest(
            '[data-soft-refresh-skip],' +
            '[role="dialog"][aria-hidden="false"],' +
            '.product-modal-overlay[aria-hidden="false"],' +
            '.modal.show, .modal-open .modal, .dropdown.show, .more-sheet.show,' +
            '.notif-dd.show, .more-panel.show, .help-panel.show,' +
            '.lightbox.show, .lightbox-modal.active'
        ) : null;

        return !!pencocok;
    }

    /*
     * Saat popup konfirmasi sedang terbuka, form asal tombol tidak boleh diganti.
     * Jika form di-morph, callback konfirmasi akan mengirim form yang sudah lepas
     * dari DOM sehingga tombol "Ya, ..." jadi terasa tidak berfungsi.
     */
    function adaPopupTerbuka() {
        return !!document.querySelector(
            '#omyuConfirmModal.active,' +
            '.lightbox-modal.active,' +
            '.lightbox.show,' +
            '.product-modal-overlay[aria-hidden="false"],' +
            '.modal.show'
        );
    }

    function harusDilewati(elemen) {
        if (!elemen || elemen.nodeType !== ELEMENT_NODE) {
            return false;
        }

        if (TAG_DILEWATI.indexOf(elemen.tagName) !== -1) {
            return true;
        }

        if (elemen.hasAttribute('data-soft-refresh-skip')) {
            return true;
        }

        if (elemen.matches('input, textarea, select, [contenteditable="true"]')) {
            return true;
        }

        if (elemen.tagName === 'BUTTON' && elemen === document.activeElement) {
            return true;
        }

        return dalamFormTerkunci(elemen) || dalamModalTerbuka(elemen);
    }

    function mirip(a, b) {
        if (!a || !b) {
            return false;
        }

        if (a.nodeType !== b.nodeType) {
            return false;
        }

        if (a.nodeType === TEXT_NODE || a.nodeType === 8) {
            return true;
        }

        return a.nodeType === ELEMENT_NODE && a.nodeName === b.nodeName;
    }

    function samakanAtribut(elemen, sumber) {
        var i;
        var atribut;

        for (i = 0; i < sumber.attributes.length; i++) {
            atribut = sumber.attributes[i];

            if (elemen.getAttribute(atribut.name) !== atribut.value) {
                elemen.setAttribute(atribut.name, atribut.value);
            }
        }

        for (i = elemen.attributes.length - 1; i >= 0; i--) {
            atribut = elemen.attributes[i];

            if (!sumber.hasAttribute(atribut.name)) {
                elemen.removeAttribute(atribut.name);
            }
        }
    }

    function samakan(elemen, sumber) {
        if (!mirip(elemen, sumber)) {
            if (elemen.parentNode) {
                elemen.parentNode.replaceChild(sumber.cloneNode(true), elemen);
            }

            return;
        }

        if (elemen.nodeType === TEXT_NODE || elemen.nodeType === 8) {
            if (elemen.nodeValue !== sumber.nodeValue) {
                elemen.nodeValue = sumber.nodeValue;
            }

            return;
        }

        if (harusDilewati(elemen)) {
            return;
        }

        samakanAtribut(elemen, sumber);

        if (TAG_DILEWATI.indexOf(elemen.tagName) !== -1) {
            return;
        }

        var lama = Array.prototype.slice.call(elemen.childNodes);
        var baru = Array.prototype.slice.call(sumber.childNodes);
        var panjang = Math.max(lama.length, baru.length);
        var i;

        for (i = 0; i < panjang; i++) {
            if (!baru[i]) {
                if (lama[i] && lama[i].parentNode && !harusDilewati(lama[i])) {
                    lama[i].parentNode.removeChild(lama[i]);
                }

                continue;
            }

            if (!lama[i]) {
                elemen.appendChild(baru[i].cloneNode(true));
                continue;
            }

            samakan(lama[i], baru[i]);
        }
    }

    function simpanScroll() {
        var hasil = [];
        var elemen;
        var daftar = document.querySelectorAll('body, body *');

        for (var i = 0; i < daftar.length; i++) {
            elemen = daftar[i];

            if (elemen.scrollTop || elemen.scrollLeft) {
                hasil.push([elemen, elemen.scrollTop, elemen.scrollLeft]);
            }
        }

        return {
            x: window.scrollX || window.pageXOffset || 0,
            y: window.scrollY || window.pageYOffset || 0,
            elemen: hasil
        };
    }

    function pulihkanScroll(simpanan) {
        var item;

        for (var i = 0; i < simpanan.elemen.length; i++) {
            item = simpanan.elemen[i];

            if (item[0].isConnected) {
                item[0].scrollTop = item[1];
                item[0].scrollLeft = item[2];
            }
        }

        window.scrollTo(simpanan.x, simpanan.y);
    }

    function terapkan(html) {
        var dokumen = new DOMParser().parseFromString(html, 'text/html');
        var simpanan = simpanScroll();
        var anakLama = Array.prototype.slice.call(document.body.childNodes);
        var anakBaru = Array.prototype.slice.call(dokumen.body.childNodes);
        var panjang = Math.max(anakLama.length, anakBaru.length);
        var i;

        for (i = 0; i < panjang; i++) {
            if (!anakBaru[i]) {
                if (anakLama[i] && anakLama[i].parentNode && !harusDilewati(anakLama[i])) {
                    anakLama[i].parentNode.removeChild(anakLama[i]);
                }

                continue;
            }

            if (!anakLama[i]) {
                document.body.appendChild(anakBaru[i].cloneNode(true));
                continue;
            }

            samakan(anakLama[i], anakBaru[i]);
        }

        if (dokumen.title && document.title !== dokumen.title) {
            document.title = dokumen.title;
        }

        pulihkanScroll(simpanan);
    }

    function muatUlangDiam() {
        if (sedangMuat || document.hidden || adaPopupTerbuka()) {
            return;
        }

        sedangMuat = true;

        fetch(window.location.href, {
            method: 'GET',
            credentials: 'same-origin',
            cache: 'no-store',
            headers: {
                'Accept': 'text/html',
                'X-OMYU-Soft-Refresh': '1'
            }
        })
            .then(function (respons) {
                if (!respons.ok) {
                    return null;
                }

                var tujuan = new URL(respons.url, window.location.href);

                if (respons.redirected && tujuan.pathname !== window.location.pathname) {
                    window.location.assign(tujuan.href);

                    return null;
                }

                return respons.text();
            })
            .then(function (html) {
                if (!html || html === terakhirDiambil) {
                    return;
                }

                terakhirDiambil = html;
                terapkan(html);
            })
            .catch(function () {
                // Jika jaringan putus, biarkan halaman apa adanya.
            })
            .finally(function () {
                sedangMuat = false;
            });
    }

    if (typeof window.fetch === 'function' && typeof DOMParser !== 'undefined') {
        window.setInterval(muatUlangDiam, REFRESH_MS);
    }
})();
