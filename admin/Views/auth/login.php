<?php
ob_start();
?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900 p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Admin Girişi</h1>
                <p class="text-gray-600">Yönetim paneline erişmek için giriş yapın</p>
            </div>

            <?php if (!empty($error)): ?>
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="/admin/login">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Kullanıcı Adı</label>
                    <input 
                        type="text" 
                        name="username" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Kullanıcı adınızı girin"
                    >
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Şifre</label>
                    <input 
                        type="password" 
                        name="password" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Şifrenizi girin"
                    >
                </div>

                <button 
                    type="submit"
                    class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors"
                >
                    Giriş Yap
                </button>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Admin Girişi';
$showSidebar = false;
include __DIR__ . '/../layout.php';
?>

