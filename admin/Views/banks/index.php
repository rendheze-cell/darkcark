<?php
ob_start();
?>

<div class="p-4 md:p-8">
    <div class="mb-6 md:mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Banka Yönetimi</h1>
            <p class="text-gray-600 mt-2">Banka listesini yönetin</p>
        </div>
        <button 
            onclick="openModal()"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 w-full md:w-auto"
        >
            + Yeni Banka
        </button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Banka Adı</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Renk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sıra</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durum</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($banks as $bank): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#<?= $bank['id'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <?= htmlspecialchars($bank['name']) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2">
                                <div 
                                    class="w-8 h-8 rounded border border-gray-300" 
                                    style="background-color: <?= htmlspecialchars($bank['color'] ?? '#FF6B6B') ?>"
                                ></div>
                                <span class="text-sm text-gray-600"><?= htmlspecialchars($bank['color'] ?? '#FF6B6B') ?></span>
                            </div>
                            <div class="text-xs text-gray-500">
                                Çark: <strong><?= htmlspecialchars($bank['wheel_text'] ?? $bank['name']) ?></strong>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= $bank['display_order'] ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded <?= $bank['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= $bank['is_active'] ? 'Aktif' : 'Pasif' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button 
                            onclick="editBank(<?= htmlspecialchars(json_encode($bank)) ?>)"
                            class="text-blue-600 hover:text-blue-800 mr-3"
                        >
                            Düzenle
                        </button>
                        <button 
                            onclick="deleteBank(<?= $bank['id'] ?>)"
                            class="text-red-600 hover:text-red-800"
                        >
                            Sil
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="bankModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4" id="modalTitle">Yeni Banka</h2>
        <form id="bankForm" onsubmit="saveBank(event)">
            <input type="hidden" id="bankId" name="id" value="0">
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Banka Adı</label>
                <input 
                    type="text" 
                    id="bankName" 
                    name="name" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Çark Üzerindeki Metin</label>
                <input 
                    type="text" 
                    id="bankWheelText" 
                    name="wheel_text" 
                    placeholder="1, 2, 3, vb."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <p class="text-xs text-gray-500 mt-1">Çark üzerinde görünecek metin (örn: 1, 2, 3, 4, 5). Banka isminden bağımsızdır.</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Renk (Çark için)</label>
                <div class="flex items-center gap-3">
                    <input 
                        type="color" 
                        id="bankColor" 
                        name="color" 
                        value="#FF6B6B"
                        class="w-16 h-10 border border-gray-300 rounded cursor-pointer"
                    >
                    <input 
                        type="text" 
                        id="bankColorText" 
                        value="#FF6B6B"
                        pattern="^#[0-9A-Fa-f]{6}$"
                        placeholder="#FF6B6B"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <p class="text-xs text-gray-500 mt-1">Çark üzerinde görünecek renk</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Görüntüleme Sırası</label>
                <input 
                    type="number" 
                    id="bankOrder" 
                    name="display_order" 
                    value="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="bankActive" 
                        name="is_active" 
                        checked
                        class="mr-2"
                    >
                    <span class="text-gray-700">Aktif</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3">
                <button 
                    type="button"
                    onclick="closeModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"
                >
                    İptal
                </button>
                <button 
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                >
                    Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('bankModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Yeni Banka';
    document.getElementById('bankForm').reset();
    document.getElementById('bankId').value = '0';
    document.getElementById('bankColor').value = '#FF6B6B';
    document.getElementById('bankColorText').value = '#FF6B6B';
    document.getElementById('bankWheelText').value = '';
}

function closeModal() {
    document.getElementById('bankModal').classList.add('hidden');
}

function editBank(bank) {
    document.getElementById('bankModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Banka Düzenle';
    document.getElementById('bankId').value = bank.id;
    document.getElementById('bankName').value = bank.name;
    document.getElementById('bankOrder').value = bank.display_order;
    document.getElementById('bankActive').checked = bank.is_active == 1;
    const color = bank.color || '#FF6B6B';
    document.getElementById('bankColor').value = color;
    document.getElementById('bankColorText').value = color;
    document.getElementById('bankWheelText').value = bank.wheel_text || bank.name || '';
}

document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('bankColor');
    const colorTextInput = document.getElementById('bankColorText');
    
    if (colorInput && colorTextInput) {
        colorInput.addEventListener('input', function(e) {
            colorTextInput.value = e.target.value;
        });

        colorTextInput.addEventListener('input', function(e) {
            const value = e.target.value;
            if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                colorInput.value = value;
            }
        });
    }
});

function saveBank(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    formData.append('is_active', document.getElementById('bankActive').checked ? '1' : '0');
    formData.append('color', document.getElementById('bankColorText').value || '#FF6B6B');
    formData.append('wheel_text', document.getElementById('bankWheelText').value || '');
    
    fetch('/admin/banks/save', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(() => {
        alert('Bir hata oluştu');
    });
}

function deleteBank(id) {
    if (!confirm('Bu bankayı silmek istediğinize emin misiniz?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('id', id);
    
    fetch('/admin/banks/delete', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(() => {
        alert('Bir hata oluştu');
    });
}
</script>

<?php
$content = ob_get_clean();
$title = 'Banka Yönetimi - Admin Panel';
$showSidebar = true;
include __DIR__ . '/../layout.php';
?>

