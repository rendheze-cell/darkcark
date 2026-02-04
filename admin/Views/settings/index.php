<?php
ob_start();
?>

<div class="p-4 md:p-8">
    <div class="mb-6 md:mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Ayarlar</h1>
        <p class="text-gray-600 mt-2">Sistem ayarlarını yönetin</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Telegram Bot Ayarları</h2>
        
        <form id="settingsForm" onsubmit="saveSettings(event)">
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Telegram Bot Token
                    </label>
                    <input 
                        type="text" 
                        name="telegram_bot_token" 
                        value="<?= htmlspecialchars($settings['telegram_bot_token'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="123456789:ABCdefGHIjklMNOpqrsTUVwxyz"
                    >
                    <p class="mt-1 text-sm text-gray-500">
                        Bot token'ı @BotFather'dan alabilirsiniz
                    </p>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Telegram Chat ID
                    </label>
                    <input 
                        type="text" 
                        name="telegram_chat_id" 
                        value="<?= htmlspecialchars($settings['telegram_chat_id'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="-1001234567890"
                    >
                    <p class="mt-1 text-sm text-gray-500">
                        Bildirimlerin gönderileceği chat ID
                    </p>
                </div>

                <div>
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="telegram_enabled" 
                            <?= ($settings['telegram_enabled'] ?? '1') === '1' ? 'checked' : '' ?>
                            class="mr-2"
                        >
                        <span class="text-gray-700">Telegram bildirimlerini etkinleştir</span>
                    </label>
                </div>

                <div class="pt-4">
                    <button 
                        type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    >
                        Ayarları Kaydet
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Çark Metinleri</h2>
        
        <form id="wheelTextsForm" onsubmit="saveWheelTexts(event)">
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Başlık
                    </label>
                    <input 
                        type="text" 
                        name="wheel_title" 
                        value="<?= htmlspecialchars($settings['wheel_title'] ?? 'Onnea!') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Onnea!"
                    >
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Alt Başlık
                    </label>
                    <input 
                        type="text" 
                        name="wheel_subtitle" 
                        value="<?= htmlspecialchars($settings['wheel_subtitle'] ?? 'Pyöräytä pyörää ja voita') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Pyöräytä pyörää ja voita"
                    >
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Talimat Metni
                    </label>
                    <input 
                        type="text" 
                        name="wheel_instruction" 
                        value="<?= htmlspecialchars($settings['wheel_instruction'] ?? 'Klikkaa pyörää tai keskimmäistä nappia aloittaaksesi') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Klikkaa pyörää tai keskimmäistä nappia aloittaaksesi"
                    >
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Dönerken Metin
                    </label>
                    <input 
                        type="text" 
                        name="wheel_spinning" 
                        value="<?= htmlspecialchars($settings['wheel_spinning'] ?? 'Pyörä pyörii...') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Pyörä pyörii..."
                    >
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Buton Metni
                    </label>
                    <input 
                        type="text" 
                        name="wheel_button_text" 
                        value="<?= htmlspecialchars($settings['wheel_button_text'] ?? 'SPIN') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="SPIN"
                    >
                </div>

                <div class="pt-4">
                    <button 
                        type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    >
                        Çark Metinlerini Kaydet
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function saveSettings(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    fetch('/admin/settings/save', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Ayarlar başarıyla kaydedildi!');
        } else {
            alert('Hata: ' + data.message);
        }
    })
    .catch(() => {
        alert('Bir hata oluştu');
    });
}

function saveWheelTexts(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    fetch('/admin/settings/save', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Çark metinleri başarıyla kaydedildi!');
        } else {
            alert('Hata: ' + data.message);
        }
    })
    .catch(() => {
        alert('Bir hata oluştu');
    });
}
</script>

<?php
$content = ob_get_clean();
$title = 'Ayarlar - Admin Paneli';
$showSidebar = true;
include __DIR__ . '/../layout.php';
?>

