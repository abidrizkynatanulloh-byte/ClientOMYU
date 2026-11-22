import {
    BarcodeFormat,
    BrowserMultiFormatReader,
    BrowserQRCodeSvgWriter,
    DecodeHintType,
} from '@zxing/library';

let reader = null;
let modal = null;
let videoElement = null;
let lastScan = { value: '', time: 0 };

const barcodeFormats = [
    BarcodeFormat.EAN_13,
    BarcodeFormat.EAN_8,
    BarcodeFormat.CODE_128,
    BarcodeFormat.CODE_39,
    BarcodeFormat.UPC_A,
    BarcodeFormat.UPC_E,
    BarcodeFormat.ITF,
];

const css = `
.opencam-overlay { position: fixed; inset: 0; z-index: 9999; background: rgba(9,20,16,.72); display: none; align-items: center; justify-content: center; padding: 18px; backdrop-filter: blur(4px); }
.opencam-overlay.active { display: flex; animation: opencamFade .18s ease-out; }
@keyframes opencamFade { from { opacity: 0; } to { opacity: 1; } }
.opencam-dialog { width: min(520px, 100%); background: #fff; border-radius: 20px; box-shadow: 0 24px 70px rgba(9,40,28,.35); overflow: hidden; animation: opencamPop .24s cubic-bezier(.2,.8,.2,1); }
@keyframes opencamPop { from { opacity: 0; transform: translateY(12px) scale(.97); } to { opacity: 1; transform: none; } }
.opencam-head { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid #e7eee9; }
.opencam-head strong { font-size: 16px; color: #153e2b; }
.opencam-close { border: 0; background: #eef5f0; color: #153e2b; width: 34px; height: 34px; border-radius: 10px; cursor: pointer; font-size: 18px; transition: background-color .2s ease, transform .15s ease; }
.opencam-close:active { transform: scale(.9); }
.opencam-body { padding: 16px 20px 20px; }
.opencam-video { width: 100%; aspect-ratio: 4 / 3; background: #0d1b16; border-radius: 14px; object-fit: cover; }
.opencam-frame { display: none; }
.opencam-status { margin: 12px 0; padding: 10px 12px; border-radius: 10px; background: #f2f7f4; color: #3f5f50; font-size: 13px; min-height: 38px; }
.opencam-status.error { background: #fff1f1; color: #a43838; }
.opencam-status.success { background: #eaf8ef; color: #1c7a45; }
.opencam-manual { display: flex; gap: 8px; }
.opencam-manual input { flex: 1; min-width: 0; border: 1px solid #d7e4dc; border-radius: 10px; padding: 11px 12px; font-size: 14px; outline: none; }
.opencam-manual input:focus { border-color: #198754; box-shadow: 0 0 0 3px rgba(25,135,84,.12); }
.opencam-manual button { border: 0; border-radius: 10px; padding: 0 16px; background: #198754; color: white; font-weight: 700; cursor: pointer; }
.opencam-hint { margin: 9px 0 0; font-size: 12px; color: #7a8f84; }
.opencam-hint-mobile { display: none; }

@media (max-width: 640px) {
    .opencam-overlay { padding: 0; background: #05110c; align-items: stretch; justify-content: stretch; backdrop-filter: none; }
    .opencam-dialog { --opencam-frame: min(78vw, 320px, 42vh); --opencam-frame: min(78vw, 320px, 42dvh); width: 100%; height: 100%; height: 100dvh; border-radius: 0; background: transparent; box-shadow: none; display: flex; flex-direction: column; animation: opencamSlide .26s cubic-bezier(.2,.8,.2,1); }
    @keyframes opencamSlide { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: none; } }
    .opencam-head { position: absolute; top: 0; left: 0; right: 0; z-index: 3; padding: calc(env(safe-area-inset-top) + 14px) 16px 18px; border: 0; background: linear-gradient(to bottom, rgba(0,0,0,.55), transparent); }
    .opencam-head strong { color: #fff; font-size: 15px; text-shadow: 0 1px 3px rgba(0,0,0,.5); }
    .opencam-close { background: rgba(255,255,255,.18); color: #fff; border: 1px solid rgba(255,255,255,.35); width: 36px; height: 36px; border-radius: 50%; font-size: 22px; line-height: 1; }
    .opencam-body { position: relative; flex: 1; padding: 0; overflow: hidden; }
    .opencam-video { position: absolute; inset: 0; width: 100%; height: 100%; aspect-ratio: auto; border-radius: 0; object-fit: cover; z-index: 0; }
    .opencam-frame { display: block; position: absolute; z-index: 1; top: 55%; left: 50%; width: var(--opencam-frame); aspect-ratio: 1 / 1; transform: translate(-50%, -50%); border-radius: 20px; box-shadow: 0 0 0 2000px rgba(3,12,8,.58); }
    .opencam-corner { position: absolute; width: 36px; height: 36px; border: 4px solid #34d399; }
    .opencam-corner.tl { top: 0; left: 0; border-right: 0; border-bottom: 0; border-radius: 20px 0 0 0; }
    .opencam-corner.tr { top: 0; right: 0; border-left: 0; border-bottom: 0; border-radius: 0 20px 0 0; }
    .opencam-corner.bl { bottom: 0; left: 0; border-right: 0; border-top: 0; border-radius: 0 0 0 20px; }
    .opencam-corner.br { bottom: 0; right: 0; border-left: 0; border-top: 0; border-radius: 0 0 20px 0; }
    .opencam-scanline { position: absolute; left: 14px; right: 14px; top: 14px; height: 2px; border-radius: 2px; background: linear-gradient(90deg, transparent, #34d399, transparent); box-shadow: 0 0 12px #34d399; animation: opencam-scan 2.4s ease-in-out infinite; }
    @keyframes opencam-scan { 0%, 100% { top: 14px; opacity: .4; } 50% { top: calc(100% - 16px); opacity: 1; } }
    .opencam-status { position: absolute; z-index: 3; top: calc(55% + var(--opencam-frame) / 2 + 16px); left: 20px; right: 20px; margin: 0; padding: 8px 12px; border-radius: 10px; background: rgba(0,0,0,.42); color: #fff; font-size: 12px; text-align: center; min-height: 0; }
    .opencam-status.error { background: rgba(180,40,40,.75); color: #fff; }
    .opencam-status.success { background: rgba(25,135,84,.8); color: #fff; }
    .opencam-manual { position: absolute; z-index: 3; left: 16px; right: 16px; bottom: calc(env(safe-area-inset-bottom) + 16px); }
    .opencam-manual input { border: 1px solid rgba(255,255,255,.35); background: rgba(255,255,255,.14); color: #fff; border-radius: 12px; padding: 12px 14px; font-size: 14px; backdrop-filter: blur(6px); }
    .opencam-manual input::placeholder { color: rgba(255,255,255,.7); }
    .opencam-manual input:focus { border-color: #34d399; box-shadow: 0 0 0 3px rgba(52,211,153,.2); }
    .opencam-manual button { background: #34d399; color: #052e1c; border-radius: 12px; padding: 0 16px; }
    .opencam-hint { position: absolute; z-index: 3; left: 24px; right: 24px; bottom: calc(env(safe-area-inset-bottom) + 66px); margin: 0; color: rgba(255,255,255,.75); font-size: 11px; text-align: center; }
    .opencam-hint-desktop { display: none; }
    .opencam-hint-mobile { display: inline; }
}
`;

function ensureModal() {
    if (modal) {
        return modal;
    }

    const style = document.createElement('style');
    style.textContent = css;
    document.head.appendChild(style);

    modal = document.createElement('div');
    modal.className = 'opencam-overlay';
    modal.innerHTML = `
        <div class="opencam-dialog" role="dialog" aria-modal="true" aria-label="Pemindai">
            <div class="opencam-head">
                <strong class="opencam-title">Pemindai</strong>
                <button type="button" class="opencam-close" aria-label="Tutup scanner">&times;</button>
            </div>
            <div class="opencam-body">
                <video class="opencam-video" playsinline muted></video>
                <div class="opencam-frame" aria-hidden="true">
                    <span class="opencam-corner tl"></span>
                    <span class="opencam-corner tr"></span>
                    <span class="opencam-corner bl"></span>
                    <span class="opencam-corner br"></span>
                    <span class="opencam-scanline"></span>
                </div>
                <div class="opencam-status">Menyiapkan kamera...</div>
                <form class="opencam-manual">
                    <input class="opencam-input" type="text" inputmode="text" autocomplete="off" placeholder="Atau ketik / scan USB di sini" />
                    <button type="submit">Kirim</button>
                </form>
                <p class="opencam-hint">
                    <span class="opencam-hint-desktop">Scanner USB berfungsi seperti keyboard. Arahkan kamera ke kode atau tekan Enter setelah memindai dengan USB.</span>
                    <span class="opencam-hint-mobile">Arahkan kamera ke dalam kotak untuk memindai</span>
                </p>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    videoElement = modal.querySelector('.opencam-video');
    modal.querySelector('.opencam-close').addEventListener('click', () => tutup());
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            tutup();
        }
    });

    const form = modal.querySelector('.opencam-manual');
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const value = modal.querySelector('.opencam-input').value.trim();

        if (value) {
            terimaHasil(value);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modal.classList.contains('active')) {
            tutup();
        }
    });

    return modal;
}

function setStatus(pesan, tipe = '') {
    const status = modal.querySelector('.opencam-status');
    status.textContent = pesan;
    status.className = 'opencam-status' + (tipe ? ' ' + tipe : '');
}

function kameraTersedia() {
    return Boolean(window.isSecureContext && navigator.mediaDevices && typeof navigator.mediaDevices.getUserMedia === 'function');
}

function pesanErrorKamera(error) {
    if (!kameraTersedia()) {
        return 'Kamera hanya dapat dibuka lewat HTTPS. Buka aplikasi dengan alamat https://, izinkan kamera, atau gunakan input manual / USB scanner.';
    }

    switch (error && error.name) {
        case 'NotAllowedError':
        case 'PermissionDeniedError':
            return 'Izin kamera ditolak. Izinkan akses kamera di browser lalu coba lagi, atau gunakan input manual / USB scanner.';
        case 'NotFoundError':
        case 'DevicesNotFoundError':
        case 'OverconstrainedError':
        case 'ConstraintNotSatisfiedError':
            return 'Kamera tidak ditemukan atau tidak sesuai. Gunakan kamera belakang lalu coba lagi, atau pakai input manual / USB scanner.';
        case 'NotReadableError':
        case 'TrackStartError':
            return 'Kamera sedang dipakai aplikasi lain. Tutup aplikasi tersebut lalu coba lagi, atau gunakan input manual / USB scanner.';
        default:
            return 'Kamera tidak dapat dibuka. Gunakan input manual / USB scanner.';
    }
}

function bisaCobaUlang(error) {
    return ['NotFoundError', 'DevicesNotFoundError', 'OverconstrainedError', 'ConstraintNotSatisfiedError'].includes(error && error.name);
}

function stopCamera() {
    if (reader) {
        reader.reset();
        reader = null;
    }

    if (videoElement) {
        videoElement.srcObject = null;
    }
}

export function tutup() {
    if (!modal) {
        return;
    }

    stopCamera();
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

function terimaHasil(nilai) {
    const sekarang = Date.now();

    if (lastScan.value === nilai && sekarang - lastScan.time < 1800) {
        return;
    }

    lastScan = { value: nilai, time: sekarang };
    setStatus('Kode terbaca: ' + nilai, 'success');
    tutup();

    if (typeof window.StafScanner.onResult === 'function') {
        window.StafScanner.onResult(nilai);
    }
}

export async function buka(options = {}) {
    ensureModal();
    stopCamera();

    window.StafScanner.onResult = options.onResult || (() => {});
    lastScan = { value: '', time: 0 };
    modal.querySelector('.opencam-title').textContent = options.judul || 'Pemindai';
    modal.querySelector('.opencam-input').placeholder = options.placeholder || 'Ketik atau scan USB di sini';
    setStatus(options.pesanAwal || 'Menyiapkan kamera...');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';

    if (!window.matchMedia('(max-width: 640px)').matches) {
        modal.querySelector('.opencam-input').focus();
    }

    if (!kameraTersedia()) {
        setStatus(pesanErrorKamera(), 'error');
        return;
    }

    const hints = new Map();
    hints.set(DecodeHintType.POSSIBLE_FORMATS, options.formats || barcodeFormats);

    try {
        reader = new BrowserMultiFormatReader(hints);
        const devices = await reader.listVideoInputDevices();
        const kameraBelakang = devices.find((device) => device.deviceId && /back|environment|rear/i.test(device.label));
        const pilihan = kameraBelakang || devices.find((device) => device.deviceId);

        const mulai = (deviceId) => {
            if (!reader) {
                return Promise.resolve();
            }

            return reader.decodeFromVideoDevice(deviceId, videoElement, (result) => {
                if (result) {
                    terimaHasil(result.getText());
                }
            });
        };

        try {
            await mulai(pilihan ? pilihan.deviceId : undefined);
        } catch (error) {
            if (!pilihan || !bisaCobaUlang(error)) {
                throw error;
            }

            await mulai(undefined);
        }

        setStatus('Arahkan kamera ke kode. Scanner aktif.');
    } catch (error) {
        setStatus(pesanErrorKamera(error), 'error');
    }
}

export function renderQr(element, nilai) {
    if (!element || !nilai) {
        return;
    }

    const writer = new BrowserQRCodeSvgWriter();
    const svg = writer.write(nilai, 220, 220);
    svg.setAttribute('style', 'width:100%;max-width:220px;height:auto;display:block;margin:0 auto;');
    element.innerHTML = '';
    element.appendChild(svg);
}

window.StafScanner = {
    bukaBarcode(options = {}) {
        return buka({
            judul: 'Scan Barcode Produk',
            placeholder: 'Ketik / scan barcode produk',
            pesanAwal: 'Arahkan kamera ke barcode produk...',
            formats: barcodeFormats,
            ...options,
        });
    },
    bukaQr(options = {}) {
        return buka({
            judul: 'Scan QR Pengambilan',
            placeholder: 'Ketik / scan token QR pesanan',
            pesanAwal: 'Arahkan kamera ke QR pengambilan pesanan...',
            formats: [BarcodeFormat.QR_CODE],
            ...options,
        });
    },
    tutup,
    renderQr,
};
