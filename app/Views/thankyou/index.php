<?php
ob_start();
// Session zaten public/index.php'de başlatıldı
$userName = $_SESSION['user_name'] ?? 'Käyttäjä';
?>

<div class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-sky-600 via-sky-700 to-sky-900">
    <div class="max-w-2xl w-full text-center">
        <!-- Success Icon -->
        <div class="mb-8 flex justify-center">
            <div class="w-32 h-32 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center shadow-2xl animate-scale-in">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <!-- Title -->
        <h1 class="text-5xl font-bold text-white mb-4 animate-fade-in">Kiitos!</h1>
        
        <!-- Message -->
        <p class="text-white/90 text-xl mb-2 animate-fade-in-delay">
            Hei <span class="font-semibold"><?= htmlspecialchars($userName) ?></span>,
        </p>
        <p class="text-white/80 text-lg mb-6 animate-fade-in-delay-2">
            Onnittelut! Voitit palkinnon!
        </p>
        <p class="text-white/90 text-2xl font-semibold mb-8 animate-fade-in-delay-2">
            Valitse pankki saadaksesi palkinnon
        </p>

        <!-- Bank Selection Button -->
        <div class="mb-8 animate-fade-in-delay-2">
            <a href="/bank" class="inline-block bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-500 hover:to-orange-600 text-white font-bold text-lg px-8 py-4 rounded-full shadow-2xl transform transition-all duration-300 hover:scale-105 hover:shadow-3xl">
                Valitse Pankki
            </a>
        </div>

        <!-- Decorative Elements -->
        <div class="flex justify-center space-x-2 mb-8">
            <div class="w-3 h-3 bg-yellow-400 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
            <div class="w-3 h-3 bg-yellow-400 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
            <div class="w-3 h-3 bg-yellow-400 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
        </div>
    </div>
</div>

<style>
@keyframes scale-in {
    from {
        transform: scale(0);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-scale-in {
    animation: scale-in 0.6s ease-out;
}

.animate-fade-in {
    animation: fade-in 0.8s ease-out;
}

.animate-fade-in-delay {
    animation: fade-in 0.8s ease-out 0.2s both;
}

.animate-fade-in-delay-2 {
    animation: fade-in 0.8s ease-out 0.4s both;
}
</style>

<?php
$content = ob_get_clean();
$title = 'Kiitos - FinTech';
$additionalStyles = '<style>body { background: transparent; }</style>';
include __DIR__ . '/../layout.php';
?>

