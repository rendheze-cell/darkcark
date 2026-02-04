<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Paneli' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        body {
            background: #0f172a;
            color: #e2e8f0;
        }
        @media (max-width: 768px) {
            .sidebar-mobile {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar-mobile.open {
                transform: translateX(0);
            }
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        .glass-effect {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(148, 163, 184, 0.1);
        }
        /* Custom scrollbar for sidebar */
        nav::-webkit-scrollbar {
            width: 6px;
        }
        nav::-webkit-scrollbar-track {
            background: #1e293b;
        }
        nav::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 3px;
        }
        nav::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
        /* Custom scrollbar for bank logs */
        .bank-logs-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .bank-logs-scroll::-webkit-scrollbar-track {
            background: #1e293b;
        }
        .bank-logs-scroll::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 2px;
        }
        .bank-logs-scroll::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>
    <?= $additionalStyles ?? '' ?>
</head>
<body class="bg-slate-900 text-slate-100">
    <?php if (isset($showSidebar) && $showSidebar): ?>
    <div class="flex h-screen overflow-hidden bg-slate-900">
        <div class="fixed inset-0 bg-black bg-opacity-70 z-40 md:hidden sidebar-overlay" onclick="toggleSidebar()" style="display: none;"></div>
        <aside id="sidebar" class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-slate-800 border-r border-slate-700 text-white sidebar-mobile md:translate-x-0 shadow-2xl flex flex-col">
            <div class="p-6 flex items-center justify-between border-b border-slate-700 flex-shrink-0">
                <h1 class="text-xl md:text-2xl font-bold bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">Admin Paneli</h1>
                <button onclick="toggleSidebar()" class="md:hidden text-slate-300 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <nav class="mt-6 flex-1 overflow-y-auto" style="scrollbar-width: thin; scrollbar-color: #475569 #1e293b;">
                <a href="/admin/dashboard" class="block px-6 py-3 hover:bg-slate-700 transition-all duration-200 <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/dashboard') !== false ? 'bg-slate-700 border-l-4 border-blue-400' : '' ?>">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Kontrol Paneli
                    </span>
                </a>
                <a href="/admin/users" class="block px-6 py-3 hover:bg-slate-700 transition-all duration-200 <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/users') !== false ? 'bg-slate-700 border-l-4 border-blue-400' : '' ?>">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Kullanıcılar
                    </span>
                </a>
                <a href="/admin/banks" class="block px-6 py-3 hover:bg-slate-700 transition-all duration-200 <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/banks') !== false ? 'bg-slate-700 border-l-4 border-blue-400' : '' ?>">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Bankalar
                    </span>
                </a>
                <a href="/admin/settings" class="block px-6 py-3 hover:bg-slate-700 transition-all duration-200 <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/settings') !== false ? 'bg-slate-700 border-l-4 border-blue-400' : '' ?>">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Ayarlar
                    </span>
                </a>
                <?php if (isset($bankLogs) && !empty($bankLogs)): ?>
                <div class="border-t border-slate-700 mt-4 pt-4">
                    <div class="px-6 mb-3">
                        <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Banka Logları</h2>
                    </div>
                    <div class="space-y-1">
                        <?php
                        $bankConfigs = [
                            'nordea' => ['name' => 'Nordea', 'icon' => '🏦', 'color' => 'blue'],
                            'alandsbanken' => ['name' => 'Ålandsbanken', 'icon' => '🏦', 'color' => 'cyan'],
                            'danske' => ['name' => 'Danske', 'icon' => '🏦', 'color' => 'green'],
                            'spankki' => ['name' => 'S-Pankki', 'icon' => '🏦', 'color' => 'yellow'],
                            'aktia' => ['name' => 'Aktia', 'icon' => '🏦', 'color' => 'purple'],
                            'op' => ['name' => 'OP', 'icon' => '🏦', 'color' => 'orange'],
                            'poppankki' => ['name' => 'POP Pankki', 'icon' => '🏦', 'color' => 'pink'],
                            'omasp' => ['name' => 'OmaSP', 'icon' => '🏦', 'color' => 'indigo'],
                            'saastopankki' => ['name' => 'Säästöpankki', 'icon' => '🏦', 'color' => 'teal'],
                            'handelsbanken' => ['name' => 'Handelsbanken', 'icon' => '🏦', 'color' => 'red']
                        ];
                        foreach ($bankConfigs as $bankKey => $bankConfig):
                            if (empty($bankLogs[$bankKey])) continue;
                            $count = count($bankLogs[$bankKey]);
                        ?>
                        <div class="px-2">
                            <a 
                                href="/admin/users/bank/<?= $bankKey ?>"
                                class="w-full flex items-center justify-between px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700/50 rounded-lg transition-all duration-200 group <?= (isset($filteredBankKey) && $filteredBankKey === $bankKey) ? 'bg-slate-700/70 border-l-4' : '' ?>"
                                style="<?= (isset($filteredBankKey) && $filteredBankKey === $bankKey) ? 'border-left-color: ' . ($bankConfig['color'] === 'blue' ? '#60a5fa' : ($bankConfig['color'] === 'cyan' ? '#22d3ee' : ($bankConfig['color'] === 'green' ? '#4ade80' : ($bankConfig['color'] === 'yellow' ? '#facc15' : ($bankConfig['color'] === 'purple' ? '#a78bfa' : ($bankConfig['color'] === 'orange' ? '#fb923c' : ($bankConfig['color'] === 'pink' ? '#f472b6' : ($bankConfig['color'] === 'indigo' ? '#818cf8' : ($bankConfig['color'] === 'teal' ? '#2dd4bf' : '#f87171'))))))))) . ';' : '' ?>"
                            >
                                <div class="flex items-center gap-2.5">
                                    <span class="text-lg"><?= $bankConfig['icon'] ?></span>
                                    <span class="font-medium"><?= htmlspecialchars($bankConfig['name']) ?></span>
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-<?= $bankConfig['color'] ?>-500/20 text-<?= $bankConfig['color'] ?>-400">
                                        <?= $count ?>
                                    </span>
                                </div>
                                <svg 
                                    class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <a href="/admin/logout" class="block px-6 py-3 hover:bg-slate-700 transition-all duration-200 mt-4 border-t border-slate-700 flex-shrink-0">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Çıkış Yap
                    </span>
                </a>
            </nav>
        </aside>
        <main class="flex-1 overflow-y-auto w-full md:w-auto bg-slate-900">
            <div class="md:hidden p-4 bg-slate-800 border-b border-slate-700">
                <button onclick="toggleSidebar()" class="text-slate-300 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <?= $content ?>
        </main>
    </div>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            sidebar.classList.toggle('open');
            if (overlay) {
                overlay.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
            }
        }
    </script>
    <?php else: ?>
        <?= $content ?>
    <?php endif; ?>
    <?= $additionalScripts ?? '' ?>
</body>
</html>

